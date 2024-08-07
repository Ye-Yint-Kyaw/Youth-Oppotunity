<?php
include '../database.php';
$id = intval($_SESSION['company_id']);
$count_query = "SELECT COUNT(*) as post_count 
                FROM posts 
                WHERE posts.is_delete = '0' AND posts.company_id = ?";
$countstmt = $conn->prepare($count_query);
$countstmt->bind_param("i", $id);
$countstmt->execute();
$countstmt->bind_result($post_count);
$countstmt->fetch();
$countstmt->close();

$pckquery = "SELECT packages.post_limit 
             FROM packages 
             JOIN company ON packages.id = company.package_id 
             WHERE company.id = ? AND packages.is_delete = 0";
$pckstmt = $conn->prepare($pckquery);
$pckstmt->bind_param("i", $id);
$pckstmt->execute();
$pckstmt->bind_result($post_limit);
$pckstmt->fetch();
$pckstmt->close();
$available = TRUE;
if($post_count >= $post_limit){
    $available = FALSE;
}

$select_fields = "SELECT posts.id, posts.title, posts.job_type, posts.deadline, field.field_name AS field_name,
                         COUNT(user_post.candidate_id) AS candidate_count
                  FROM posts
                  JOIN field ON posts.field_id = field.id
                  LEFT JOIN user_post ON posts.id = user_post.post_id
                  WHERE posts.is_delete = '0' AND posts.company_id = $id
                  GROUP BY posts.id, posts.title, posts.job_type, posts.deadline, field.field_name;";

$result = mysqli_query($conn, $select_fields);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}


?>

<div class="field-create">
    <a href="?content=jobsinsert" 
       class="btn field-create-btn btn-success <?php echo !$available ? 'disabledJobCreate' : ''; ?>">
        Create New Job Post <i class="fa-solid fa-plus"></i>
    </a>
</div>
<p>You have <?php echo ($post_limit - $post_count); ?>&nbsp;post(s) left to crete new Job Posts. </p>


<table id="datatable" class="table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Number</th>
            <th>Job Title</th>
            <th>Job Type</th>
            <th>Job Field</th>
            <th>Deadline</th>
            <th>Numbers of applied candidates</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $number++ . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['job_type'] . "</td>";
            echo "<td>" . $row['field_name'] . "</td>";
            echo "<td>" . $row['deadline'] . "</td>";
            echo "<td>" . $row['candidate_count'] . "</td>";
            echo "<td>
                    <a href='?content=jobsdelete&id=" . $row['id'] . "'><i class='fa-solid fa-trash'></i></a>
                    <a href='?content=jobsupdate&id=" . $row['id'] . "'><i class='fa-solid fa-pen-to-square'></i></a>
                  </td>";
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
