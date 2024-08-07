<?php
include '../database.php';
$id = '';
$package_name = '';
$post_limit = '';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT package_name, post_limit  FROM packages WHERE id = ? AND is_delete = 0");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($package_name, $post_limit);
    $stmt->fetch();
    $stmt->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $package_name = $_POST['package_name'];
    $post_limit = $_POST['post_limit'];
    $insert_query = "UPDATE packages SET package_name = ?, post_limit = ? WHERE id = ?";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sii", $package_name, $post_limit, $id);
    if ($stmt->execute()) {
        header("Location:?content=packages");
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
                <h5 class="modal-title">Update Package</h5>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST">
                    <div class="form-group">
                        <label for="package_name">Package Name:</label>
                        <input type="text" class="form-control" id="package_name" name="package_name" value="<?php echo $package_name; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="post_limit">Package Name:</label>
                        <input type="text" class="form-control" id="post_limit" name="post_limit" value="<?php echo $post_limit; ?>" required>
                    </div>
                    <div class="btn-gp">
                        <button type="submit" class="btn field-cancel-btn" id="cancel">CANCEL</button>
                        <button type="submit" class="btn field-create-btn btn-success">UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('cancel').addEventListener('click', function () {
        document.getElementById('package_name').value = '';
        document.getElementById('post_limit').value = '';
        window.history.back();
    });
</script>
</body>
</html>

