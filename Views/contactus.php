<?php
include "database.php";
$id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']): 0;
$stmt = $conn->prepare("SELECT name, email from users WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_name = $user['name'];
$user_email = $user['email'];
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/contact.css">
    <title>Contact Us</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
</head>
<body>
    <div class="contact-container">
        <div class="contact-info">
            <h2>Contact Information</h2>
            <p>If you have any questions, feel free to reach out to us.</p>
            <ul>
                <li><i class="fas fa-map-marker-alt"></i> 123 Main Street, Anytown, USA</li>
                <li><i class="fas fa-phone-alt"></i> +1 234 567 890</li>
                <li><i class="fas fa-envelope"></i> contact@example.com</li>
            </ul>
        </div>
       
        <div class="contact-form">
            <h2>Get in Touch</h2>
            <form action="Views/submit_contact.php" method="POST" id="createForm">
                <div class="form-group">
                    <input type="hidden" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required>
                    <?php             echo '<p> Hello '.$user_name.'</p>';
?>
                </div>
                <div class="form-group">
                    <input type="hidden" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" required>
                    <?php             echo '<p><p>You can freely contact us if you have technical issue or feedbacks related to our system.</p>';
?>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5"></textarea>
                </div>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>

    <script>
        ClassicEditor
            .create(document.querySelector('#message'))
            .catch(error => {
                console.error(error);
            });

        document.getElementById('createForm').addEventListener('submit', function(event) {
            const messageEditor = ClassicEditor.instances.message;
            if (messageEditor.getData().trim() === '') {
                alert('Please fill out the message field.');
                event.preventDefault();
                return false;
            }
            document.querySelector('#message').value = messageEditor.getData();
        });
    </script>
</body>
</html>
