<?php
ob_start(); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../database.php';
    $field_name = $_POST['name'];
    $insert_query = "INSERT INTO field (field_name) VALUES ('$field_name')";
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['success'] = "Successful";
        header("Location:?content=field");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}

ob_end_flush(); 

?>


<div class="" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Job Field</h5>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST">
                    <div class="form-group">
                        <label for="name">Field Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="btn-gp">
                        <button type="submit" class="btn field-cancel-btn" id="cancel">CANCEL</button>
                        <button type="submit" class="btn field-create-btn btn-success">CREATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('cancel').addEventListener('click', function () {
        document.getElementById('name').value = '';
        window.history.back();
    });
</script>
</body>
</html>

