<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Form</title>
</head>
<body>
    <form action="payment_process.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" required><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br>

        <label for="amount">Donation Amount:</label>
        <input type="number" id="amount" name="amount" required><br>

        <label for="payment">Payment Method:</label>
        <select id="payment" name="payment" required>
            <option value="Visa">Visa</option>
            <option value="Master">Master</option>
            <option value="JCB">JCB</option>
        </select><br>

        <button type="submit">Donate</button>
    </form>
</body>
</html>
