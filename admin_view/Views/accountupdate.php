<?php
include '../database.php';

$admin_id = intval($_SESSION['admin_id']);

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0) {
        $allowed_img_formats = ['jpg', 'jpeg', 'png'];
        $filename_img = $_FILES['profile_img']['name'];
        $filetype_img = $_FILES['profile_img']['type'];
        $filesize_img = $_FILES['profile_img']['size'];

        $ext_img = strtolower(pathinfo($filename_img, PATHINFO_EXTENSION));
        if (!in_array($ext_img, $allowed_img_formats)) {
            die("Error: Please select a valid image format (JPG, JPEG, PNG).");
        }

        $user_folder_img = "uploads/profile/admin_" . $admin_id;
        if (!file_exists($user_folder_img)) {
            mkdir($user_folder_img, 0755, true);
        }

        $unique_filename_img = 'profile_image_' . time() . '.' . $ext_img;
        $target_file_img = $user_folder_img . "/" . $unique_filename_img;

        if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $target_file_img)) {
            $stmt_img = $conn->prepare("UPDATE users SET profile = ? WHERE id = ?");
            $stmt_img->bind_param('si', $target_file_img, $admin_id);
            if (!$stmt_img->execute()) {
                echo "Error: Could not save the profile image information to the database.";
            }
            $stmt_img->close();
        } else {
            die("Error: There was a problem uploading your profile image. Please try again.");
        }
    }
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone_number = ? WHERE id = ?");
    $stmt->bind_param('sssi', $name, $email, $phone_number, $admin_id);
    if ($stmt->execute()) {
        header("Location: ?content=myaccount");
        exit;
    } else {
        echo "Error: Could not update the information.";
    }
    $stmt->close();
}
?>

<div class="apply-form-container">
    <div class="form-content">
        <div class="form-header">
            <h5 class="form-title">Edit Company Information</h5>
        </div>
        <div class="form-body">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                </div>
                <div class="form-group">
                    <label for="profile_img">Update Profile Image (JPG, PNG, JPEG only):</label>
                    <input type="file" class="form-control" id="profile_img" name="profile_img" accept="image/jpeg, image/png">
                    <p style="margin-top:5px;">Current Profile Image:
                        <?php if (!empty($user['profile'])): ?>
                            <img style="width:80px; height:80px;border:3px solid grey; border-radius:5px;" src="<?php echo htmlspecialchars($user['profile']); ?>" class="current-profile-img" alt="Current Profile Image">
                        <?php else: ?>
                            No Uploaded Profile Image
                        <?php endif; ?>
                    </p>
                </div>
                <div class="btn-gp">
                    <button type="button" class="btn-cancel" id="cancel" onclick="history.back();">CANCEL</button>
                    <button type="submit" class="btn-submit">UPDATE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
