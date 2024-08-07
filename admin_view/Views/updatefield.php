<?php
include '../database.php';
$field_name = '';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT field_name FROM field WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($field_name);
    $stmt->fetch();
    $stmt->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $field_name = $_POST['name'];
    $insert_query = "UPDATE field SET field_name = ? WHERE id = ?";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("si", $field_name, $id);
    if ($stmt->execute()) {
        header("Location:?content=field");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

?>


<div class="" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Job Field</h5>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST">
                    <div class="form-group">
                        <label for="name">Field Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $field_name; ?>" required>
                    </div>
                    <div class="btn-gp">
                        <button type="button" class="btn field-cancel-btn" id="cancel">CANCEL</button>
                        <button type="submit" class="btn field-create-btn btn-success">UPDATE</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                
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

