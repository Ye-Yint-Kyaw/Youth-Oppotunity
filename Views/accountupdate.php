<?php
include 'database.php';

$user_id = intval($_SESSION['user_id']);

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
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

        $user_folder_img = "uploads/profile/user_" . $user_id;
        if (!file_exists($user_folder_img)) {
            mkdir($user_folder_img, 0755, true);
        }

        $unique_filename_img = 'profile_image_' . time() . '.' . $ext_img;
        $target_file_img = $user_folder_img . "/" . $unique_filename_img;

        if (move_uploaded_file($_FILES['profile_img']['tmp_name'], $target_file_img)) {
            $stmt_img = $conn->prepare("UPDATE users SET profile = ? WHERE id = ?");
            $stmt_img->bind_param('si', $target_file_img, $user_id);
            if (!$stmt_img->execute()) {
                echo "Error: Could not save the profile image information to the database.";
            }
            $stmt_img->close();
        } else {
            die("Error: There was a problem uploading your profile image. Please try again.");
        }
    }

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $allowed_cv_formats = ['pdf'];
        $filename_cv = $_FILES['cv']['name'];
        $filetype_cv = $_FILES['cv']['type'];
        $filesize_cv = $_FILES['cv']['size'];

        $ext_cv = strtolower(pathinfo($filename_cv, PATHINFO_EXTENSION));
        if (!in_array($ext_cv, $allowed_cv_formats)) {
            die("Error: Please select a valid CV file format (PDF).");
        }

        if ($filesize_cv > 5 * 1024 * 1024) {
            die("Error: CV file size is larger than the allowed limit.");
        }

        $user_folder_cv = "uploads/cv/user_" . $user_id;
        if (!file_exists($user_folder_cv)) {
            mkdir($user_folder_cv, 0755, true);
        }

        $unique_filename_cv = 'cv_' . time() . '.' . $ext_cv;
        $target_file_cv = $user_folder_cv . "/" . $unique_filename_cv;

        if (move_uploaded_file($_FILES['cv']['tmp_name'], $target_file_cv)) {
            $stmt_cv = $conn->prepare("UPDATE users SET cv = ? WHERE id = ?");
            $stmt_cv->bind_param('si', $target_file_cv, $user_id);
            if (!$stmt_cv->execute()) {
                echo "Error: Could not save the CV information to the database.";
            }
            $stmt_cv->close();
        } else {
            die("Error: There was a problem uploading your CV. Please try again.");
        }
    }

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $work_exp = $_POST['work_exp'] ?? '';
    $education = $_POST['education'] ?? '';
    $description = $_POST['description'] ?? '';

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone_number = ?, dob = ?, gender = ?, work_exp = ?, education = ?, description = ? WHERE id = ?");
    $stmt->bind_param('ssssssssi', $name, $email, $phone_number, $dob, $gender, $work_exp, $education, $description, $user_id);
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
            <h5 class="form-title">Edit User Information</h5>
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
                    <label for="dob">Date of Birth:</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <input type="text" class="form-control" id="gender" name="gender" value="<?php echo htmlspecialchars($user['gender']); ?>" >
                </div>
                <div class="form-group">
                    <label for="work_exp">Work Experience:</label>
                    <input type="text" class="form-control" id="work_exp" name="work_exp" value="<?php echo htmlspecialchars($user['work_exp']); ?>">
                </div>
                <div class="form-group">
                    <label for="education">Education:</label>
                    <input type="text" class="form-control" id="education" name="education" value="<?php echo htmlspecialchars($user['education']); ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($user['description']); ?></textarea>
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
                <div class="form-group">
                    <label for="cv">Update CV (PDF only):</label>
                    <input type="file" class="form-control" id="cv" name="cv" accept="application/pdf">
                    <p>Current CV:
                        <?php if (!empty($user['cv']) && $user['cv'] != '#'): ?>
                            <a href="download_cv.php?cv=<?php echo urlencode($user['cv']); ?>">View</a>
                        <?php else: ?>
                            No Uploaded CV
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
