<?php
session_start();
include '../../database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $selectuser = "SELECT id, password FROM company WHERE email = ? AND is_delete = 0";
    $stmt = $conn->prepare($selectuser);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    
    if($stmt->num_rows > 0){
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['company_id'] = $user_id;
            header('Location: ../index.php');
            exit();
        } else {
            $_SESSION['login_error'] = 'Invalid password.';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['login_error'] = 'Invalid username or email.';
        header('Location: login.php');
        exit();
    }
}


// header('Location: ../index.php');
?>
