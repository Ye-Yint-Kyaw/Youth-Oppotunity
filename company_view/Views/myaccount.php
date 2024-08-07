<?php
include '../database.php';
$company_id = intval($_SESSION['company_id']);

// Fetch user data
$stmt = $conn->prepare("SELECT company.company_name, company.phone_number, company.email, company.profile, packages.package_name FROM company JOIN packages ON company.package_id = packages.id WHERE company.id = ?;");
$stmt->bind_param('i', $company_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $user = [
        'name' => 'No Update',
        'email' => 'No Update',
        'phone_number' => 'No Update',
        'package' => 'No Update',
        'profile' => $profileimg
    ];
} else {
    // Handle null values explicitly
    foreach ($user as $key => $value) {
        if (is_null($value) && $key !== "profile") {
            $user[$key] = 'No Update';
        }
    }
    $user['profile'] = !empty($user['profile']) ? $user['profile'] : $profileimg;
}

$stmt->close();
?>

<div class="acc-container">
    <div class="acc">
        <div class="profile-picture">
            <img src="<?php echo htmlspecialchars($user['profile']); ?>" class="rounded-circle" alt="Profile Picture">
        </div>
        <div class="details">
            <div class="detail-item">
                <i class="fas fa-user"></i>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['company_name']); ?></p>
            </div>
            <div class="detail-item">
                <i class="fas fa-envelope"></i>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <div class="detail-item">
                <i class="fas fa-phone"></i>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
            </div>
            <div class="detail-item">
                <i class="fas fa-box"></i>
                <p><strong>Package:</strong> <?php echo htmlspecialchars($user['package_name']); ?></p>
            </div>

        </div>
        <div class="buttons">
            <button class="btn cancel" onclick="history.back();">Cancel</button>
            <a href="?content=accountupdate&id=<?php echo $company_id; ?>" class = "btn edit">Edit</a>
            <!-- <button class="btn edit">Edit</button> -->
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
