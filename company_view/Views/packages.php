<?php
include '../database.php';
$company_id = 0;
$package_id = 0;
if(isset($_SESSION['company_id'])){
    $company_id = intval($_SESSION['company_id']);
}

$select_fields = "SELECT * FROM packages WHERE is_delete = '0' ORDER BY id DESC"; 
$result = mysqli_query($conn, $select_fields);
$pkgquery = "SELECT package_id FROM company WHERE id = $company_id";
$pkgresult = $conn->query($pkgquery);
if ($pkgresult->num_rows > 0) {
    $row = $pkgresult->fetch_assoc();
    $package_id = $row['package_id'];
}

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit; 
}

?>
<table id="datatable" class="table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Number</th>
            <th>Package Name</th>
            <th>Post Limit</th>
            <th>Your Package</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $number++ . "</td>";
            echo "<td>" . $row['package_name'] . "</td>";
            echo "<td>" . $row['post_limit'] . "</td>";
            echo "<th>";
            if ($package_id != $row['id']) {
                echo "<a href='?content=buy_package&package_id=" . $row['id'] . "&amount=" . ($row['post_limit'] * 1000) . "' class='button'>Subscribe</a>";
            } else {
                echo "Already subscribed";
            }
            echo "</th>";
            echo "</tr>";
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