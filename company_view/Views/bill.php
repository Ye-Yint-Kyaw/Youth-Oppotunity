<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transaction_number = $_POST['transaction_number'];
    $transaction_date = $_POST['transaction_date'];
    $package_id = $_POST['package_id'];
    $amount = $_GET['amount'];
    $company_id = $_POST['company_id'];
    $is_new = '1';
    $stmt = $conn->prepare("INSERT INTO bill (transaction_number, amount, package_id, company_id, transaction_date, is_new) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $transaction_number, $amount, $package_id, $company_id, $transaction_date, $is_new);

    if ($stmt->execute()) {
        $success_message = "Payment recorded successfully.";
        header("Location: ?content=packages");
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}


?>