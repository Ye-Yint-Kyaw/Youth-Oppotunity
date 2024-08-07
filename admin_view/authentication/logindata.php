<?php
session_start();
include '../../database.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_type = 'admin';
    $selectuser = "SELECT id, password FROM users WHERE email = ? AND is_delete = 0 AND user_type = ?";
    $stmt = $conn->prepare($selectuser);
    $stmt->bind_param('ss', $email, $user_type);
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows > 0){
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_id'] = $user_id;
            header('Location: ../?content=dashboard');
            exit();
        } else {
            $_SESSION['login_error'] = 'Invalid password.';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['login_error'] = 'Invalid email';
        header('Location: login.php');
        exit();
    }
}


?>
