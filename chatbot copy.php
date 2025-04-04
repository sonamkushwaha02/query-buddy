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
        pointer-events: auto; /* Ensure button is clickable */
        position: relative;
        z-index: 1;
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
    .btn-primary:hover {
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
    <p class="muted-text">This AI-powered chatbot uses machine learning to generate responses based on user input.</p>
</div>

<script>
    console.log("Script loaded"); // Debug: Confirm script runs

    // Cohere API configuration
    const COHERE_API_KEY = 'klSUU7yvy0RJcosjS0A4zzJKkPYpFDRnc1IscvsY'; // Replace with your actual key
    const COHERE_API_URL = 'https://api.cohere.ai/v1/generate';

    // Function to display messages
    function displayMessage(message, isUser = false) {
        console.log(`Displaying message: ${message}, isUser: ${isUser}`); // Debug
        const chatBox = document.getElementById("chat-box");
        const messageDiv = document.createElement("div");
        messageDiv.className = `chat-message ${isUser ? 'user-message' : 'bot-message'}`;
        messageDiv.innerHTML = `<div class="alert ${isUser ? 'alert-primary' : 'alert-secondary'} d-inline-block">${message}</div>`;
        chatBox.appendChild(messageDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Function to fetch courses from backend
    async function getCourses() {
        console.log("Fetching courses from api.php"); // Debug
        try {
            const response = await fetch('api.php');
            const data = await response.json();
            console.log("Courses response:", data); // Debug
            if (data.success) {
                return data.courses;
            }
            throw new Error(data.error || 'Failed to fetch courses');
        } catch (error) {
            console.error('Error fetching courses:', error);
            return [];
        }
    }

    // Function to format course response
    function formatCourseResponse(courses, query) {
        console.log("Formatting response for query:", query, "with courses:", courses); // Debug
        if (!courses || courses.length === 0) {
            return "Sorry, I couldn't find any courses matching your query.";
        }

        const lowercaseQuery = query.toLowerCase();
        let response = "Here's what I found:\n";
        courses.forEach(course => {
            if (course.name.toLowerCase().includes(lowercaseQuery) || 
                lowercaseQuery.includes(course.name.toLowerCase())) {
                response += `\nCourse: ${course.name}`;
                if (lowercaseQuery.includes('fee')) {
                    response += `\nFee: $${course.fee}`;
                }
                if (lowercaseQuery.includes('duration')) {
                    response += `\nDuration: ${course.duration}`;
                }
                response += '\n';
            }
        });
        const finalResponse = response.length > "Here's what I found:\n".length ? response : "No specific matches found.";
        console.log("Formatted response:", finalResponse); // Debug
        return finalResponse;
    }

    // Process query with Cohere API
    async function processQuery(query) {
        console.log("Processing query:", query); // Debug
        try {
            const courses = await getCourses();
            const courseInfo = formatCourseResponse(courses, query);

            console.log("Sending request to Cohere API with courseInfo:", courseInfo); // Debug
            const response = await fetch(COHERE_API_URL, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${COHERE_API_KEY}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    model: 'command',
                    prompt: `Given this course information: ${courseInfo}\nGenerate a friendly response to the query: "${query}"`,
                    max_tokens: 200,
                    temperature: 0.7
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
            }

            const data = await response.json();
            console.log("Cohere API response:", data); // Debug
            if (!data.generations || !data.generations[0]) {
                throw new Error('Invalid response format from Cohere API');
            }
            return data.generations[0].text.trim();
        } catch (error) {
            console.error('Error in processQuery:', error);
            return `Sorry, I couldn't process your request due to an error: ${error.message}`;
        }
    }

    // Send message handler
    async function sendMessage() {
        console.log("sendMessage function called"); // Debug
        const userInput = document.getElementById("user-input");
        const query = userInput.value.trim();

        if (!query) {
            console.log("No input provided, exiting sendMessage"); // Debug
            return;
        }

        try {
            displayMessage(query, true);
            userInput.value = '';
            displayMessage("Processing...");

            const botResponse = await processQuery(query);
            console.log("Bot response received:", botResponse); // Debug
            
            const chatBox = document.getElementById("chat-box");
            chatBox.removeChild(chatBox.lastChild);
            displayMessage(botResponse);
        } catch (error) {
            console.error("Error in sendMessage:", error);
            const chatBox = document.getElementById("chat-box");
            if (chatBox.lastChild && chatBox.lastChild.textContent === "Processing...") {
                chatBox.removeChild(chatBox.lastChild);
            }
            displayMessage("Sorry, something went wrong. Please try again.");
        }
    }

    // Event listeners
    const sendBtn = document.getElementById("send-btn");
    if (sendBtn) {
        sendBtn.addEventListener("click", function() {
            console.log("Send button clicked"); // Debug
            sendMessage();
        });
    } else {
        console.error("Send button not found in DOM"); // Debug
    }

    const userInput = document.getElementById("user-input");
    if (userInput) {
        userInput.addEventListener("keypress", function(event) {
            console.log("Key pressed:", event.key); // Debug
            if (event.key === "Enter") {
                console.log("Enter key detected, calling sendMessage"); // Debug
                sendMessage();
            }
        });
    } else {
        console.error("User input not found in DOM"); // Debug
    }

    // Initial message
    console.log("Displaying initial message"); // Debug
    displayMessage("Hello! I'm here to help you with course information. Ask me about courses, fees, or durations!");
</script>

<?php include_once('footer.php');  ?>