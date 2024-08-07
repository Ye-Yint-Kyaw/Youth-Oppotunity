<?php
session_start();
include '../database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $selectuser = "SELECT id, password, name FROM users WHERE email = ? AND is_delete = 0 AND user_type = 'user'";
    $stmt = $conn->prepare($selectuser);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows > 0){
        $stmt->bind_result($user_id, $hashed_password, $name);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            header('Location: ../index.php?content=home');
            exit();
        } else {
            $_SESSION['login_error'] = 'Invalid username or password.';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['login_error'] = 'Invalid username or password.';
        header('Location: login.php');
        exit();
    }
}
?>
