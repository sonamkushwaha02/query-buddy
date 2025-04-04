<?php include_once('header.php');  ?>
<?php
if (!isset($_SESSION['pending_verification_email'])) {
    ob_start();
    header("Location: register.php");
    exit();
}
$email = $_SESSION['pending_verification_email']; 
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
                            <h3 class=" form-title text-center font-weight-normal mb-4">Verify your email</h3>
                            <p class="text-center mb-4">Enter 6 digit code sent to your email! </p>
                            <form id="verifyForm">
                                <div class="login-form-wrap pt-2">
                                   
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <div class="form-item">
                                                <label class="form-label">Enter Verification Code:</label>
                                                <input type="text" id="verification_code" name="verification_code" required class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="form-group row pt-3">
                                        <div class="col-md-12 text-center">
                                        <div class="submit-btn">
                                        <button id="submit" class="ed-primary-btn" type="submit">Submit</button>
                                        </div>
                                        </div>
                                    </div>

                                    <div class="form-group row pt-3">
                                        <p id="message"></p>
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
    $("#verifyForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "process-verification.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status == "error") {
                    $("#message").text(response.message).css("color", "red");
                } else if (response.status == "success") {
                    window.location.href = response.redirect; // Redirect to dashboard
                }
            }
        });
    });
});

</script>

<?php include_once('footer.php');  ?>
