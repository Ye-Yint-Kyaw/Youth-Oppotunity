<?php
session_start();
include 'database.php';

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
            if (file_exists("uploads/cv" . $filename)) {
                die("Error: File already exists.");
            }
            if (move_uploaded_file($_FILES['cv']['tmp_name'], "uploads/" . $filename)) {
                // Save file information to the database
                $user_id = 46; // Assuming you have user sessions
                $stmt = $conn->prepare("UPDATE users SET cv = ? WHERE id = ?");
                $stmt->bind_param('si', $filename, $user_id);

                if ($stmt->execute()) {
                    echo "Your file was uploaded successfully.";
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
?>
