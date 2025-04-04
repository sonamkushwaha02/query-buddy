<?php 
include_once('session.php'); 
include_once('header.php');  
?>
<?php
if (isset($_SESSION['user_id'])) {
    header("Location: account.php"); // Redirect logged-in users
    exit();
}
?>

<style>
.login-form-wrap label {
	font-size: 16px;
    font-weight:normal;
	margin-bottom: 8px;
	width: 100%;
}

.login-form-wrap  {
    padding: 0 20px;
}
.login-form-wrap input {
	height: 40px;
	font-size: 14px;
	padding-left: 5px;
	margin-bottom: 15px;
	width: 100%;
}
.login-section .form-title{
    font-weight:normal;
}
.login-section .submit-btn .ed-primary-btn {
    width: 100%;
    justify-content: center;
}
</style>

<section class="login-section pt-100 pb-100" style="background-color: #EBFBF8;">
            <div class="container">
                
                <div class="row">
                    <div class="col-lg-4 offset-lg-4 col-md-4">
                        <div class="login-box shadow p-4 " style="background-color: #ffffff;" >
                            <h3 class=" form-title text-center font-weight-normal mb-4">Login your account</h3>
                            <form id="loginForm">
                                <div class="login-form-wrap pt-2">
                                   
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-item">
                                                <label class="form-label">Email Address*</label>
                                                <input type="email" id="email" name="email" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-item">
                                                <label class="form-label">Password*</label>
                                                <input type="password" id="password" name="password" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="form-group row pt-3">
                                        <div class="col-md-12 text-center">
                                        <div class="submit-btn">
                                        <button id="submit" class="ed-primary-btn" type="submit">Login</button>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="form-group row pt-3">
                                    <p id="message"></p>
                                    </div>
                                    <div class="form-group row pt-3">
                                        <div class="col-md-12 text-center"> <a href="forgot-password.php">Forgot Password?</a></div>
                                    </div>

                                    <div class="form-group row pt-3">
                                        <div class="col-md-12 text-center"> Don't have an account? <a href="signup.php">Create now</a></div>
                                    </div> 
                                   
                                </div>
                            </form>
                        </div>
                    </div>
                   
                </div>
            </div>
        </section>
        <!-- ./ checkout-section -->
<script>
$(document).ready(function () {
    $("#loginForm").on("submit", function (e) {
        e.preventDefault();
        $("#loginBtn").prop("disabled", true).text("Logging in...");
        $.ajax({
            type: "POST",
            url: "process-login.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === "error") {
                    $("#message").text(response.message).css("color", "red");
                    $("#loginBtn").prop("disabled", false).text("Login");
                } else if (response.status === "success") {
                    window.location.href = "account.php";
                }
            }
        });
    });
});
</script>

<?php include_once('footer.php');  ?>
