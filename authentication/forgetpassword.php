<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Ensure SMTP class is imported

require '../vendor/autoload.php'; // Path to Composer autoload file

$mail = new PHPMailer(true); // Enable verbose debug output
$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
$mail->Debugoutput = function($str, $level) { echo "debug level $level; message: $str"; };

try {
    //Server settings
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com'; // Specify SMTP server
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'youthopportunity.service@gmail.com'; // SMTP username
    $mail->Password = 'youthopportunity@service1'; // SMTP password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption
    $mail->Port = 587; // TCP port to connect to

    //Recipients
    $mail->setFrom('your_email@example.com', 'Your Name');
    $mail->addAddress('recipient@example.com', 'Recipient Name'); // Add a recipient

    //Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Test Email via PHPMailer';
    $mail->Body    = 'This is a test email sent using PHPMailer with Composer.';

    $mail->send();
    echo 'Message has been sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Forgot Password</h2>
    <?php if (!isset($codeSent) && !isset($_SESSION['code_verified'])): ?>
        <form action="forgetpassword.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <button type="submit" name="request_code">Request a code</button>
            </div>
        </form>
    <?php elseif (isset($codeSent) && !isset($_SESSION['code_verified'])): ?>
        <form action="forgetpassword.php" method="post">
            <div class="form-group">
                <label for="code">Enter the code sent to your email</label>
                <input type="text" id="code" name="code" required>
                <button type="submit" name="verify_code">Verify Code</button>
            </div>
        </form>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
</div>

</body>
</html>
