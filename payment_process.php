<?php
// Manually include the necessary files from phpseclib
//set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');
include('phpseclib3/Crypt/RSA.php');

use phpseclib3\Crypt\RSA;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $phone = htmlspecialchars($_POST['phone']);
    $total = htmlspecialchars($_POST['amount']);
    $payment = htmlspecialchars($_POST['payment']);
    $method = "Credit Card"; // Assuming payment method is credit card for all options
    $donationID = rand(10000, 99999); // Example of generating a random order ID

    $items_data = array(
        "name" => "Dinger University Donation",
        "amount" => "$total",
        "quantity" => "1"
    );

    $data_array = array(
        "providerName" => $payment,
        "methodName" => $method,
        "totalAmount" => "$total",
        "items" => json_encode(array($items_data)),
        "orderId" => $donationID,
        "customerName" => $name,
        "email" => $email,
        "billToForeName" => "Customer name",
        "billToSurName" => $name,
        "billAddress" => $address,
        "billCity" => $city,
        "customerPhone" => $phone
    );

    if (!in_array($payment, ["Visa", "Master", "JCB"])) {
        $data_array = array(
            "providerName" => $payment,
            "methodName" => $method,
            "totalAmount" => "$total",
            "items" => json_encode(array($items_data)),
            "orderId" => $donationID,
            "customerName" => $name,
            "customerPhone" => $phone
        );
    }

    $data_pay = json_encode($data_array);

    $publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCJtu2coOqkFaaxLtlnb6DAQRvw
+6l9iwm6RZlGrAf6IUnZiJavYi60hTveLkFbeYLvvLcFyIGddQDUJBCvEOIk7Gwg
F6pPRlV9k5g7CDyHbqsjudOix+ElD2XkAiUeYWAK++uRVBqcE/xxwNMDoRwyYqoC
/OifZf0pH7PA3XCUyQIDAQAB
-----END PUBLIC KEY-----
EOD;

    $rsa = new RSA();
    $rsa->loadKey($publicKey);
    $rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);

    $ciphertext = $rsa->encrypt($data_pay);

    $value = base64_encode($ciphertext);

    echo "Encrypted Payment Data: " . $value;
} else {
    echo "Invalid request method.";
}
?>
