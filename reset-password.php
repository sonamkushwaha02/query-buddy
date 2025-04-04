<?php include_once('header.php'); ?>

<h2>Reset Password</h2>
<form id="resetPasswordForm">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <label>New Password:</label>
    <input type="password" name="new_password" required>
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required>
    <button type="submit">Reset Password</button>
</form>

<script>
    $("#resetPasswordForm").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "process-reset-password.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert("Password reset successful! Redirecting to login...");
                    window.location.href = "login.php";
                } else {
                    alert(response.message);
                }
            }
        });
    });
</script>

<?php include_once('footer.php'); ?>
