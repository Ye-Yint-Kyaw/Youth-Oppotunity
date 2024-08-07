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

$id = '';
$company_name = '';
$username = '';
$email = '';
$phone_number = '';
$address = '';
$package_id = '';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT company_name, user_name, email, phone_number, address, package_id FROM company WHERE id = ? AND is_delete = 0");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($company_name, $username, $email, $phone_number, $address, $package_id);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phno'];
    $address = $_POST['address'];
    $package_id = $_POST['package_id'];
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($password) {
        $update_query = "UPDATE company SET company_name = ?, user_name = ?, email = ?, phone_number = ?, address = ?, package_id = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssssssi", $company_name, $username, $email, $phone_number, $address, $package_id, $password, $id);
    } else {
        $update_query = "UPDATE company SET company_name = ?, user_name = ?, email = ?, phone_number = ?, address = ?, package_id = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssssi", $company_name, $username, $email, $phone_number, $address, $package_id, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Successfully updated";
        header("Location:?content=company");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<div class="" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Company</h5>
            </div>
            <div class="modal-body">
                <form id="updateForm" method="POST">
                    <div class="form-group">
                        <label for="name">Company Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($company_name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username Name*:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email*:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phno">Phone Number*:</label>
                        <input type="text" class="form-control" id="phno" name="phno" value="<?php echo htmlspecialchars($phone_number); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address*:</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="package_id">Package*:</label>
                        <select class="form-control" id="package_id" name="package_id" required style="padding: 0 5px;">
                            <option value="" disabled>Please choose a package</option>
                            <?php foreach ($packagenames as $package): ?>
                                <option value="<?php echo $package['id']; ?>" <?php echo $package_id == $package['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($package['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password (leave blank if not changing):</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="btn-gp">
                        <button type="button" class="btn field-cancel-btn" id="cancel">CANCEL</button>
                        <button type="submit" class="btn field-update-btn btn-success">UPDATE</button>
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
