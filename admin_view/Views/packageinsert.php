<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../database.php';
    $package_name = $_POST['package_name'];
    $post_limit = $_POST['post_limit'];
    $insert_query = "INSERT INTO packages (package_name, post_limit, is_delete) VALUES ('$package_name', '$post_limit', 0)";
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['success'] = "Successful";
        header("Location:?content=packages");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_close($conn);
}

?>


<div class="" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Package</h5>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST">
                    <div class="form-group">
                        <label for="package_name">Package Name:</label>
                        <input type="text" class="form-control" id="package_name" name="package_name" required>
                    </div>
                    <div class="form-group">
                        <label for="post_limit">Post Limit:</label>
                        <input type="number" class="form-control" id="post_limit" name="post_limit" required>
                    </div>
                    <div class="btn-gp">
                        <button type="button" class="btn field-cancel-btn" id="cancel">CANCEL</button>
                        <button type="submit" class="btn field-create-btn btn-success">CREATE</button>
                    </div>
                </form>
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

