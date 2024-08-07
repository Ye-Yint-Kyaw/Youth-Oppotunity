<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/login.css">
    <link rel="stylesheet" href="../../common.css">
    <title>Login</title>
</head>
<body>
    <div class="login-box">
        <div class="login">
            <div class="left">
            </div>
            <div class="right ad-right">
                <h1><a href="#"><img src="../../img/green_logo.png" alt="Logo"></a></h1>
                <form class="login-form" id="login-form" method='POST' action='logindata.php'>
                    <div class="input-container">
                        <input type="email" id="email" name="email" required>
                        <label for="email">Email or Username</label>
                    </div>
                    <div class="input-container">
                        <input type="password" id="password" name = "password" required>
                        <label for="password">Password</label>
                    </div>
                    <button type="submit">LOGIN</button>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </form>
                <?php
                if(isset($_SESSION['login_error'])) {
                    echo '<p style="color: red; font-size: 13px; font-style: Roboto;">' . $_SESSION['login_error'] . '</p>';
                    unset($_SESSION['error']); 
                }
                ?>
            </div>
        </div>
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
