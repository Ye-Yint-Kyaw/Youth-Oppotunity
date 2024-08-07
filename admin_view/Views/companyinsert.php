<?php
include '../database.php';

$packagenames = [];
$packagesquery = "SELECT id, package_name FROM packages WHERE is_delete = 0";
$stmt = $conn->prepare($packagesquery);
$stmt->execute();
$stmt->bind_result($package_id, $package_name);
while ($stmt->fetch()) {
    $packagenames[] = ['id' => $package_id, 'name' => $package_name];
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $field_name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phno'];
    $address = $_POST['address'];
    $package_id = $_POST['package_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $insert_query = "INSERT INTO company (company_name, user_name, email, password, phone_number, address, package_id, is_delete) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssssssi", $field_name, $username, $email, $password, $phone_number, $address, $package_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Successful";
        header("Location:?content=company");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<div class="" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Company</h5>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST">
                    <div class="form-group">
                        <label for="name">Company Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username Name*:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email*:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phno">Phone Number*:</label>
                        <input type="text" class="form-control" id="phno" name="phno" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password*:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address*:</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="package_id">Package*:</label>
                        <select class="form-control" id="package_id" name="package_id" required style="padding: 0 5px;">
                            <option value="" disabled selected hidden>Please choose a package</option>
                            <?php foreach ($packagenames as $package): ?>
                                <option value="<?php echo $package['id']; ?>"><?php echo htmlspecialchars($package['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="btn-gp">
                        <button type="button" class="btn field-cancel-btn" id="cancel">CANCEL</button>
                        <button type="submit" class="btn field-create-btn btn-success">CREATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('cancel').addEventListener('click', function () {
        window.history.back();
    });
</script>
</body>
</html>
