<?php include_once('header.php'); ?>

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
                <div class="login-box shadow p-4" style="background-color: #ffffff;">
                    <h3 class="form-title text-center font-weight-normal mb-4">Reset your password</h3>
                    <p class="text-center mb-4">Enter your email address and we'll send you a link to reset your password</p>
                    <form id="forgotPasswordForm">
                        <div class="login-form-wrap pt-2">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-item">
                                        <label class="form-label">Email Address*</label>
                                        <input type="email" id="email" name="email" class="form-control">
                                        <small id="error-message" class="text-danger"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row pt-3">
                                <div class="col-md-12 text-center">
                                    <div class="submit-btn">
                                        <button id="submitBtn" class="ed-primary-btn" type="submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                            <p id="success-message" class="text-success text-center"></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $("#forgotPasswordForm").on("submit", function (e) {
            e.preventDefault();
            $("#error-message").text("");
            $("#success-message").text("");
            $("#submitBtn").prop("disabled", true).text("Processing...");

            $.ajax({
                type: "POST",
                url: "process-forgot-password.php",
                data: $(this).serialize(),
                dataType: "json",
                success: function (response) {
                    $("#submitBtn").prop("disabled", false).text("Submit");
                    if (response.status === "error") {
                        $("#error-message").text(response.message);
                    } else {
                        $("#success-message").text(response.message);
                        $("#forgotPasswordForm")[0].reset();
                    }
                }
            });
        });
    });
</script>

<?php include_once('footer.php'); ?>
