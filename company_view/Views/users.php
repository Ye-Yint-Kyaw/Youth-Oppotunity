<?php
include '../database.php';

$company_id =  $_SESSION['company_id'];

$select_fields = "
SELECT users.name, users.email, users.phone_number, users.gender, user_post.cv, posts.title
FROM users
JOIN user_post ON users.id = user_post.candidate_id
JOIN posts ON user_post.post_id = posts.id
WHERE users.is_delete = '0' AND users.user_type = 'user' AND posts.company_id = $company_id
ORDER BY users.id DESC";


$result = mysqli_query($conn, $select_fields);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit; 
}
?>

<table id="datatable" class="table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Number </th>
            <th>User Name</th>
            <th>Job Title</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Gender</th>
            <th>CV</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $number++ . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['phone_number'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
            echo "<td><a href='download_cv.php?cv=" . urlencode($row['cv']) . "'>Download CV</a></td>";
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
