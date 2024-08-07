<?php
include '../database.php';
$select_fields = "SELECT * FROM users WHERE is_delete = '0' ORDER BY id DESC"; 
$result = mysqli_query($conn, $select_fields);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit; 
}


?>
<div class="field-create">
    <a href="?content=usersinsert" class="btn field-create-btn btn-success">Create New Admin <i class="fa-solid fa-plus"></i></a>
</div>


<table id="datatable" class="table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Number</th>
            <th>User Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Gender</th>
            <th>User Type</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $num = 1;
       while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$num."</td>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['phone_number'] . "</td>";
        echo "<td>" . $row['gender'] . "</td>";
        echo "<td>" . $row['user_type'] . "</td>";
        echo "<td>
            <a href='?content=usersdelete&id=" . $row['id'] . "'><i class='fa-solid fa-trash'></i></a>
          </td>";
        echo "</tr>";
        ++$num;
    }
        ?>
    </tbody>
</table>

<?php if (isset($_SESSION['success'])): ?>
<div class="toast-container">
    <div id="sessionToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1500">
        <div class="toast-header">
            <strong class="me-auto">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toastEl = document.getElementById('sessionToast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    });
</script>