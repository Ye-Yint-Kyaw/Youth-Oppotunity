<?php
ob_start(); // Start output buffering at the very beginning
include 'database.php';

$user_id = intval($_SESSION['user_id']);
$post_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $allowed = ['pdf' => 'application/pdf'];
        $filename = $_FILES['cv']['name'];
        $filetype = $_FILES['cv']['type'];
        $filesize = $_FILES['cv']['size'];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            die("Error: Please select a valid file format.");
        }

        if (in_array($filetype, $allowed)) {
            if ($filesize > 5 * 1024 * 1024) {
                die("Error: File size is larger than the allowed limit.");
            }
            $user_folder = "uploads/cv/user_" . $user_id;
            if (!file_exists($user_folder)) {
                mkdir($user_folder, 0755, true);
            }
            $unique_filename = pathinfo($filename, PATHINFO_FILENAME) . '_' . time() . '.' . $ext;
            $target_file = $user_folder . "/" . $unique_filename;
            if (move_uploaded_file($_FILES['cv']['tmp_name'], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO user_post (candidate_id, post_id, cv) VALUES (?, ?, ?)");
                $stmt->bind_param('iis', $user_id, $post_id, $target_file);
                if ($stmt->execute()) {
                    ob_clean(); // Clean the output buffer to ensure no output before header
                    header("Location: ?content=home");
                    exit;
                } else {
                    echo "Error: Could not save the file information to the database.";
                }
                $stmt->close();
            } else {
                die("Error: There was a problem uploading your file. Please try again.");
            }
        } else {
            die("Error: There was a problem uploading your file. Please try again.");
        }
    } else {
        die("Error: " . $_FILES['cv']['error']);
    }
}
ob_end_flush(); // End output buffering and flush
?>

<div class="apply-form-container">
    <div class="form-content">
        <div class="form-header">
            <h5 class="form-title">Create New Job</h5>
        </div>
        <div class="apply-txt">
            <p>*Some information from your profile will also be submitted to the company you are applying.</p>
        </div>
        <div class="form-body">
            <form id="createForm" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="cv">Please Attach your CV (PDF only)*:</label>
                    <input type="file" class="form-control" id="cv" name="cv" accept="application/pdf" required>
                </div>
                <div class="btn-gp">
                    <button type="button" class="btn-cancel" id="cancel" onclick="history.back();">CANCEL</button>
                    <button type="submit" class="btn-submit">APPLY NOW</button>
                </div>
            </form>
        </div>
    </div>
</div>
