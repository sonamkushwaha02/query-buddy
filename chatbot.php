<?php 
include_once('session.php'); 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php include_once('header.php');  ?>

<style>
    .chat-container {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
    }
    .chat-message {
        margin-bottom: 10px;
    }
    .user-message {
        text-align: right;
    }
    .bot-message {
        text-align: left;
    }
    .chat-input {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        border-radius: 50px;
        background: #ffffff;
        border: 1px solid #ccc;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .chat-input input {
        border: none;
        outline: none;
        flex: 1;
        padding: 10px;
        border-radius: 50px;
    }
    .chat-input button {
        border-radius: 50px;
        padding: 10px 20px;
        cursor: pointer;
    }
    .muted-text {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 5px;
        text-align: center;
    }
    .btn-primary {
        background-color: var(--ed-color-theme-primary);
        color: #fff;
        border-color: var(--ed-color-theme-primary);
    }
</style>

<div class="container mt-4 d-flex flex-column" style="height: 80vh;">
    <div id="chat-box" class="chat-container"></div>
    <div class="chat-input mt-auto">
        <input type="text" id="user-input" class="form-control" placeholder="Type your message...">
        <button class="btn btn-primary" id="send-btn">Send</button>
    </div>
    <p class="muted-text">This AI-powered chatbot provides information on courses, fees, and durations.</p>
</div>

<script>
    console.log("Chatbot loaded");

    const COHERE_API_KEY = 'klSUU7yvy0RJcosjS0A4zzJKkPYpFDRnc1IscvsY'; 
    const COHERE_API_URL = 'https://api.cohere.ai/v1/generate';

    // Function to display messages
    function displayMessage(message, isUser = false) {
        const chatBox = document.getElementById("chat-box");
        const messageDiv = document.createElement("div");
        messageDiv.className = `chat-message ${isUser ? 'user-message' : 'bot-message'}`;
        messageDiv.innerHTML = `<div class="alert ${isUser ? 'alert-primary' : 'alert-secondary'} d-inline-block">${message}</div>`;
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Normalize text (removes dots, extra spaces, converts to lowercase)
    function normalizeText(text) {
        return text.toLowerCase()
            .replace(/\./g, '')   // Remove dots (B.Sc. -> BSc)
            .replace(/\s+/g, ' ') // Remove extra spaces
            .trim();
    }

    // Fetch course data from database
    async function getCourses() {
        try {
            const response = await fetch('courses-data.php');
            const data = await response.json();
            return data.success ? data.courses : [];
        } catch (error) {
            console.error('Error fetching courses:', error);
            return [];
        }
    }

    // Extract course name & keywords from query
    function extractQueryDetails(query) {
        const normalizedQuery = normalizeText(query);

        // Define common course abbreviations and full names
        const courseMapping = {
            "bachelor of science": ["bsc", "b.sc", "b sc", "b.s.c"],
            "master of science": ["msc", "m.sc", "m sc", "m.s.c"],
            "bachelor of arts": ["ba", "b.a", "b a"],
            "master of arts": ["ma", "m.a", "m a"],
            "computer science": ["cs", "comp sci", "c.s."],
        };

        let matchedCourse = "";
        let keywords = [];

        // Detect course name in query
        for (const [fullName, variations] of Object.entries(courseMapping)) {
            if (variations.some(variant => normalizedQuery.includes(variant)) || normalizedQuery.includes(fullName)) {
                matchedCourse = fullName;
                break;
            }
        }

        // Detect keywords (fee, duration, admission)
        if (normalizedQuery.includes("fee")) keywords.push("fee");
        if (normalizedQuery.includes("duration")) keywords.push("duration");
        if (normalizedQuery.includes("admission")) keywords.push("admission criteria");

        return { matchedCourse, keywords };
    }

    // Find matching course from database
    function findMatchingCourse(courses, courseName) {
        return courses.find(course => normalizeText(course.name).includes(courseName));
    }

    // Format course response dynamically
    function formatCourseResponse(course, keywords) {
        if (!course) return "Sorry, I couldn't find that course.";

        let response = `<strong>Course:</strong> ${course.name}<br>`;
        if (keywords.includes("fee")) response += `<strong>Fee:</strong> $${course.fee}<br>`;
        if (keywords.includes("duration")) response += `<strong>Duration:</strong> ${course.duration}<br>`;
        if (keywords.includes("admission criteria")) response += `<strong>Admission Criteria:</strong> ${course.admission_criteria}<br>`;

        return response;
    }

    // Call Cohere API for AI response
  async function getCohereResponse(courseInfo, query) {
    try {
        let promptMessage = `User asked: "${query}".`;

        if (courseInfo.includes("Sorry")) {
            promptMessage += " Unfortunately, we couldn't find the specific course you were looking for. However, our university offers a vibrant learning environment with experienced faculty, modern facilities, and a great campus culture that supports holistic student development.";
        } else {
            promptMessage += ` Here's the course info: ${courseInfo}. Generate a helpful response.`;
        }

        const response = await fetch(COHERE_API_URL, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${COHERE_API_KEY}`,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                model: 'command',
                prompt: promptMessage,
                max_tokens: 200,
                temperature: 0.7
            })
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const data = await response.json();
        let aiResponse = data.generations[0]?.text.trim() || "I'm sorry, I couldn't generate a response.";

        // Replace any occurrence of $ followed by a number with INR symbol
        aiResponse = aiResponse.replace(/\$\s*(\d+)/g, 'INR $1');
        
        return aiResponse;
    } catch (error) {
        console.error('Error with Cohere API:', error);
        return "I'm sorry, something went wrong with my response generation.";
    }
}




    // Handle user input
    async function sendMessage() {
        const userInput = document.getElementById("user-input");
        const query = userInput.value.trim();

        if (!query) return;

        displayMessage(query, true);
        userInput.value = '';
        displayMessage("Thinking...");

        try {
            const courses = await getCourses();
            const { matchedCourse, keywords } = extractQueryDetails(query);
            const course = findMatchingCourse(courses, matchedCourse);
            const courseInfo = formatCourseResponse(course, keywords);
            const aiResponse = await getCohereResponse(courseInfo, query);

            const chatBox = document.getElementById("chat-box");
            chatBox.removeChild(chatBox.lastChild);
            displayMessage(aiResponse);
        } catch (error) {
            console.error("Error in sendMessage:", error);
            displayMessage("Sorry, something went wrong. Please try again.");
        }
    }

    // Event Listeners
    document.getElementById("send-btn").addEventListener("click", sendMessage);
    document.getElementById("user-input").addEventListener("keypress", (event) => {
        if (event.key === "Enter") sendMessage();
    });

    // Initial message
    displayMessage("Hello! Ask me about courses, fees, durations, or admission criteria.");
</script>

<?php include_once('footer.php');  ?>
