<?php
include '../database.php';
$company_id = 0;
$package_id = 0;
if(isset($_SESSION['company_id'])){
    $company_id = intval($_SESSION['company_id']);
}

$select_fields = "SELECT b.amount AS b_amount, Date(b.transaction_date) AS transaction_date, Time(b.transaction_date) AS transaction_time, p.package_name AS p_name FROM bill b LEFT JOIN packages p ON b.package_id = p.id WHERE p.is_delete = '0' AND b.company_id = $company_id ORDER BY transaction_date DESC;"; 
$result = mysqli_query($conn, $select_fields);

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
            <th>Amount</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $number++ . "</td>";
            echo "<td>" . $row['p_name'] . "</td>";
            echo "<td>" . $row['b_amount'] . "</td>";
            echo "<td>" . $row['transaction_date'] . "</td>";
            echo "<td>" . $row['transaction_time'] . "</td>";
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