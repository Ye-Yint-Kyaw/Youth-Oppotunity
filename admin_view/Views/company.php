<?php
include '../database.php';
$select_fields = "SELECT company.id AS company_id, company.company_name, company.user_name, company.email, company.phone_number, company.address, packages.package_name FROM company JOIN packages ON company.package_id = packages.id WHERE company.is_delete = '0' AND packages.is_delete = '0' ORDER BY company.id DESC"; 
$result = mysqli_query($conn, $select_fields);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit; 
}


?>
<div class="field-create">
    <a href="?content=companyinsert" class="btn field-create-btn btn-success">Create New Company <i class="fa-solid fa-plus"></i></a>
</div>


<table id="datatable" class="table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Number</th>
            <th>Company Name</th>
            <th>User Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Package Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $num = 1;
       while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$num."</td>";
        echo "<td>" . $row['company_name'] . "</td>";
        echo "<td>" . $row['user_name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['phone_number'] . "</td>";
        echo "<td>" . $row['address'] . "</td>";
        echo "<td>" . $row['package_name'] . "</td>";
        echo "<td>
            <a href='?content=companydelete&id=" . $row['company_id'] . "'><i class='fa-solid fa-trash'></i></a>
            <a href='?content=companyupdate&id=" . $row['company_id'] . "'><i class='fa-solid fa-pen-to-square'></i></a>
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