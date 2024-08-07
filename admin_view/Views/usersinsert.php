<?php
include '../database.php';
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phno'];
    $gender = $_POST['gender'];
    $user_type = 'admin';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $check_query = "SELECT * FROM users WHERE email = ? AND user_type = 'admin' AND is_delete = 0";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = "Email already existed!";
    } else {
        $insert_query = "INSERT INTO users (name, email, phone_number, password, gender, user_type, is_delete) 
                         VALUES (?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssss", $name, $email, $phone_number, $password, $gender, $user_type);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Successful";
            header("Location:?content=users");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<div class="" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Admin</h5>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST">
                    <div class="form-group">
                        <label for="name">User Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
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
                        <label for="gender">Gender*:</label>
                        <select class="form-control" id="gender" name="gender" required style="padding: 0 5px;">
                            <option value="" disabled selected hidden>Please choose gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="btn-gp">
                        <button type="button" class="btn field-cancel-btn" id="cancel">CANCEL</button>
                        <button type="submit" class="btn field-create-btn btn-success">CREATE</button>
                    </div>
                </form>
                <p style="color:red"> <?php echo $error; ?></p>
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
