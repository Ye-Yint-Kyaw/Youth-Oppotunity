<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/reg.css">
    <link rel="stylesheet" href="../common.css">
    <title>Register</title>
</head>
<body>
    <div class="login-box">
        <form action="registerdata.php" method="POST">
            <div class="left-pic"></div>
            <div class="login">
                <h1><a href="#"><img src="../img/green_logo.png" alt=""></a></h1>
                <div class="lg">
                    <div class="left">
                        <div class="input-container">
                            <input type="text" id="name" name="name" required>
                            <label for="name">Name*</label>
                        </div>
                        <div class="input-container">
                            <input type="email" id="email" name="email" required>
                            <label for="email">Email*</label>
                        </div>
                        <div class="input-container">
                            <input type="text" id="phno" name="phno" required>
                            <label for="phno">Phone Number*</label>
                        </div>
                        <div class="input-container">
                            <input type="date" id="dob" name="dob" required>
                        </div>
                    </div>
                    <div class="right">
                        <div class="input-container">
                            <input type="password" id="password" name="password" required>
                            <label for="password">Password*</label>
                        </div>
                        <div class="input-container">
                            <input type="password" id="cpassword" name="cpassword" required>
                            <label for="cpassword">Confirm Password*</label>
                        </div>
                        <div class="input-container">
                            <input type="text" id="education" name="education">
                            <label for="education">Education</label>
                        </div>
                        <div class="input-container">
                            <select id="gender" name="gender" required>
                                <option value="" disabled selected hidden>Gender*</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit">REGISTER</button>
                <a href="login.php" class="forgot-password">Or have an account?</a>
                <?php if(isset($_SESSION['error'])): ?>
                    <p style="color: red; font-size: 13px;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll(".input-container input");
            inputs.forEach(input => {
                input.addEventListener("focus", function() {
                    input.style.borderBottomColor  = "#11ca00";
                    const label = this.nextElementSibling;
                    label.style.top = "-10px";
                    label.style.fontSize = "12px";
                    label.style.color = "#aaa";
                });

                input.addEventListener("blur", function() {
                    if (this.value === "") {
                        input.style.borderBottomColor  = "#333";
                        const label = this.nextElementSibling;
                        label.style.top = "10px";
                        label.style.fontSize = "16px";
                        label.style.color = "#aaa";
                    }
                });
            });
        });
    </script>
</body>
</html>
