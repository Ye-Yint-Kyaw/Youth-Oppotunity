<?php
include 'database.php';
$user_id = intval($_SESSION['user_id']);

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $user = [
        'name' => 'No Update',
        'email' => 'No Update',
        'phone_number' => 'No Update',
        'dob' => 'No Update',
        'gender' => 'No Update',
        'work_exp' => 'No Update',
        'education' => 'No Update',
        'description' => 'No Update',
        'cv' => '#',
        'profile' => $profileimg
    ];
} else {
    // Handle null values explicitly
    foreach ($user as $key => $value) {
        if (is_null($value) && $key !== "profile" && $key !== "cv") {
            $user[$key] = 'No Update';
        }
    }
    $user['cv'] = !empty($user['cv']) ? $user['cv'] : '#';
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
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
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
                <i class="fas fa-birthday-cake"></i>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
            </div>
            <div class="detail-item">
                <i class="fas fa-venus-mars"></i>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            </div>
            <div class="detail-item">
                <i class="fas fa-briefcase"></i>
                <p><strong>Work Experience:</strong> <?php echo htmlspecialchars($user['work_exp']); ?></p>
            </div>
            <div class="detail-item">
                <i class="fas fa-graduation-cap"></i>
                <p><strong>Education:</strong> <?php echo htmlspecialchars($user['education']); ?></p>
            </div>
            <div class="detail-item">
                <i class="fas fa-info-circle"></i>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($user['description']); ?></p>
            </div>
            <div class="detail-item">
    <i class="fas fa-file-alt"></i>
    <p><strong>CV:</strong>
    <?php echo ($user['cv'] !== '#') ? "<a href='download_cv.php?cv=" . urlencode($user['cv']) . "'>Download CV</a>" : "No CV is uploaded"; ?>
    </p>

</div>


        </div>
        <div class="buttons">
            <button class="btn cancel" onclick="history.back();">Cancel</button>
            <a href="?content=accountupdate&id=<?php echo $user_id; ?>" class = "btn edit">Edit</a>
            <!-- <button class="btn edit">Edit</button> -->
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
