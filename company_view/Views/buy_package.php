<?php
include '../database.php';

if (isset($_GET['package_id']) && isset($_GET['amount'])) {
    $package_id = $_GET['package_id'];
    $amount = $_GET['amount'];
    $company_id = $_SESSION['company_id'];
} else {
    die("Invalid request.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Package</title>
    <style>
        .button {
            background-color: #11ca00;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
        /* Modal styling */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
    padding-top: 60px; /* Padding from top */
}

/* Modal content box */
.modal-content {
    background-color: #fff; /* White background */
    margin: 5% auto; /* 5% from the top and centered */
    padding: 20px; /* Padding inside */
    border: 1px solid #888; /* Border color */
    border-radius: 10px; /* Rounded corners */
    width: 80%; /* Width of the box */
    max-width: 600px; /* Maximum width */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow effect */
    animation: fadeIn 0.3s; /* Fade-in effect */
}

/* Close button */
.close {
    color: #aaa; /* Light grey color */
    float: right; /* Align to the right */
    font-size: 28px; /* Larger font size */
    font-weight: bold; /* Bold font */
    cursor: pointer; /* Pointer cursor */
}

/* Close button hover effect */
.close:hover,
.close:focus {
    color: #000; /* Black color on hover/focus */
    text-decoration: none; /* Remove underline */
}

/* Fade-in animation */
@keyframes fadeIn {
    from { opacity: 0; } /* Start with 0 opacity */
    to { opacity: 1; } /* End with full opacity */
}

/* Form styling inside modal */
.modal-content form {
    display: flex; /* Flexbox layout */
    flex-direction: column; /* Stack items vertically */
    gap: 10px; /* Space between items */
}

/* Label styling */
.modal-content label {
    font-weight: bold; /* Bold text */
}

/* Input styling */
.modal-content input[type="text"],
.modal-content input[type="date"] {
    padding: 10px; /* Padding inside */
    border: 1px solid #ccc; /* Light grey border */
    border-radius: 5px; /* Rounded corners */
    width: 100%; /* Full width */
    box-sizing: border-box; /* Include padding and border in width */
}

/* Submit button styling */
.modal-content button[type="submit"] {
    background-color: #11ca00; /* Green background */
    color: white; /* White text */
    border: none; /* No border */
    padding: 10px; /* Padding inside */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor */
    font-size: 16px; /* Font size */
}

/* Submit button hover effect */
.modal-content button[type="submit"]:hover {
    background-color: #0e9e00; /* Darker green on hover */
}

    </style>
</head>
<body>
    <h2>Package Purchase</h2>
    <?php
    if (isset($success_message)) {
        echo "<p style='color: green;'>$success_message</p>";
    } elseif (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
    <p>Amount: $<?php echo htmlspecialchars($amount); ?></p>
    <button class="button" id="payButton">Pay Now</button>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form action="?content=bill&company_id=<?php echo $company_id; ?>&package_id=<?php echo $package_id; ?>&amount=<?php echo $amount; ?>" method="post">
                <input type="hidden" name="package_id" value="<?php echo htmlspecialchars($package_id); ?>">
                <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($company_id); ?>">
                <img src="../img/scan.jpg" style="width: 100%; height: auto; display:flex; justify-content: center; align-item:center;" alt="Scan QR Code">
                <input type="text" disabled name="amount" value="<?php echo htmlspecialchars($amount); ?>">
                <div>
                    <label for="transaction_number">Transaction Number:</label>
                    <input type="text" id="transaction_number" name="transaction_number" required>
                </div>
                <div>
                    <label for="transaction_date">Date of Transfer:</label>
                    <input type="datetime-local" id="transaction_date" name="transaction_date" required step="1">
                </div>

                <button type="submit" class="button">Submit</button>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById("myModal");

        var btn = document.getElementById("payButton");

        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }
        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
