<?php
include_once('session.php');  

if (isset($_SESSION['user_id'])) {
    header("Location: account.php"); // Redirect logged-in users
    exit();
}

include_once('header.php');  

?>
<style>
.signup-form-wrap label {
	font-size: 16px;
    font-weight:normal;
	margin-bottom: 6px;
	width: 100%;
}

.form-item{
    margin-bottom: 15px;
}

.signup-form-wrap {
padding: 0px 20px;
}
.signup-form-wrap input {
	height: 40px;
	font-size: 14px;
	padding-left: 5px;
	width: 100%;
}
.signup-section .form-title{
    font-weight:normal;
}
.signup-section .submit-btn .ed-primary-btn {
    width: 100%;
    justify-content: center;
}
</style>

<section class="signup-section pt-100 pb-80" style="background-color: #EBFBF8;">
            <div class="container">
                
                <div class="row">
                    <div class="col-lg-6 offset-lg-3 col-md-12">
                        <div class="signup-box shadow p-4 " style="background-color: #ffffff;" >
                            <h3 class="form-title text-center mt-2 mb-4">Create your account</h3>
                            <form id="registerForm">
                                <div class="signup-form-wrap mt-2 pt-2">
                                   
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-item name">
                                                <label class="form-title">First Name<span class="text-danger">*</span></label>
                                                <input type="text" id="first_name" name="first_name" class="form-control">
                                                <small class="text-danger" id="error-first_name"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-item">
                                                <label class="form-title">Last Name</label>
                                                <input type="text" id="last_name" name="last_name" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-item">
                                                <label class="form-title">Email Address<span class="text-danger">*</span></label>
                                                <input type="email" id="email" name="email" class="form-control">
                                                <small class="text-danger" id="error-email"></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-item">
                                                <label class="form-title">Password<span class="text-danger">*</span></label>
                                                <input type="password" id="password" name="password" class="form-control">
                                                <small class="text-danger" id="error-password"></small>
                                            </div>
                                        </div>
                                    </div>
                                      <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-item">
                                                <label class="form-title">Confirm Password<span class="text-danger">*</span></label>
                                                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                                <small class="text-danger" id="error-confirm_password"></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-item">
                                                <label class="form-title">Phone<span class="text-danger">*</span></label>
                                                <input type="text" id="phone" name="phone" class="form-control">
                                                <small class="text-danger" id="error-phone"></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row pt-4">
                                        <div class="col-md-12 text-center">
                                            <div class="submit-btn">
                                            <button id="submit" class="ed-primary-btn " type="submit">Signup</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row pt-3 ">
                                        <div class="col-md-12 text-center"> <a href="login.php">Already have an account</a></div>
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
    $("#registerForm").on("submit", function (e) {
        e.preventDefault();
        
        $(".text-danger").text(""); // Clear previous errors
        $("#submit").prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Processing...'); // Show loading
        
        $.ajax({
            type: "POST",
            url: "register-process.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {

                $("#submit").prop("disabled", false).html("Signup"); // Restore button text
                
                if (response.status == "error") {
                    $.each(response.errors, function (key, value) {
                        $("#error-" + key).text(value);
                    });
                } else if (response.status == "success") {
                    $("#registerForm")[0].reset();
                    $("#success-message").text(response.message);
                    //now redirect to verification page
                    window.location.href = "verify-email.php"; 
                }
            },
            error: function () {
                $("#submit").prop("disabled", false).html("Signup"); // Restore button text
                alert("Something went wrong. Please try again.");
            }
        });
    });
});
</script>


<?php include_once('footer.php');  ?>
