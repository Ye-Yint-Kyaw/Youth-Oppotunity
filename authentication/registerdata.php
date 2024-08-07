<?php
session_start();
include '../database.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phno'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $education = $_POST['education'];
    $gender = $_POST['gender'];
    $is_delete = 0;
    $user_type = "user";
    if ($password !== $cpassword) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: register.php');
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $selectemail = "SELECT id FROM users WHERE email = ? AND is_delete = 0";
    $stmt = $conn->prepare($selectemail);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = 'Email already exists.';
        header('Location: register.php');
        exit();
    } else {
        $insertuser = "INSERT INTO users (name, email, phone_number, password, education, gender, dob, user_type, is_delete) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertuser);

        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param('ssssssssi', $name, $email, $phone, $hashed_password, $education, $gender, $dob, $user_type, $is_delete);

        if ($stmt->execute()) {
            // $_SESSION['user_id'] = $stmt->insert_id;
            header('Location: login.php');
            exit();
        } else {
            echo "Execute failed: " . $stmt->error;
        }

        $stmt->close();
    }
} else {
    $name = "No";
}
?>
