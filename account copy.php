<?php 
include_once('session.php'); 
include_once('config/db.php'); 

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
$stmt = $pdo->prepare("SELECT first_name, last_name, email, phone, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: logout.php"); // Logout if user data not found
    exit();
}

?>
<?php include_once('header.php'); ?>

<style>
.sidebar {
    min-height: 88vh;
    position: absolute;
    top: 85px;
    left: 0;
    width: 250px;
}
.main-content {
    margin-left: 250px;
}
@media (max-width: 768px) {
    .sidebar {
        position: relative;
        width: 100%;
        min-height: auto;
    }
    .main-content {
        margin-left: 0;
    }
}
</style>

<section class="signup-section pt-100 pb-80" style="background-color: #EBFBF8;">
    <div class="container">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar bg-secondary bg-gradient border-end">
                <div class="p-3">
                    <h4 class="mb-4 text-white">My Account</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" href="account.php">My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="container py-5">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-10">
                            <div class="card shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">My Account</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <img src="https://via.placeholder.com/100" class="rounded-circle" alt="Profile Picture">
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></li>
                                        <li class="list-group-item"><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></li>
                                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                                        <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></li>
                                        <li class="list-group-item"><strong>Member Since:</strong> <?php echo date("F j, Y", strtotime($user['created_at'])); ?></li>
                                    </ul>
                                    <div class="mt-3 text-center">
                                        <button class="btn btn-primary btn-sm">Edit Profile</button>
                                        <a href="logout.php" class="btn btn-outline-secondary btn-sm">Log Out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</section>

<?php include_once('footer.php'); ?>
