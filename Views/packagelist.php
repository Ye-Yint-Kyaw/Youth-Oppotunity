<?php
include '../database.php';
$select_fields = "SELECT * FROM packages WHERE is_delete = '0' ORDER BY id DESC"; 
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
            <th>Post Limit</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $number = 0;
            echo "<tr>";
            echo "<td>" . $number++ . "</td>";
            echo "<td>" . $row['package_name'] . "</td>";
            echo "<td>" . $row['post_limit'] . "</td>";
        }
        ?>
    </tbody>
</table>
