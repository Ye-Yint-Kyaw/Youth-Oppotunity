<?php
include 'database.php';

$is_loggin = FALSE;
$user_id = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $is_loggin = TRUE;
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$limit = 7; // Number of job posts per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get the total number of job posts
$total_query = "SELECT COUNT(*) as total FROM posts WHERE is_delete = 0 AND job_type = 'Full Time'";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_posts = $total_row['total'];
$total_pages = ceil($total_posts / $limit);

$selectappliedpostquery = "SELECT id FROM user_post WHERE candidate_id = ? AND post_id = ?";

$job_posts = [];
$query = "SELECT posts.id, posts.title, posts.location, posts.job_type, posts.deadline, posts.needed_staffs, posts.salary, posts.post_date, field.field_name, company.company_name
FROM posts
JOIN field ON posts.field_id = field.id
JOIN company ON company.id = posts.company_id
WHERE posts.is_delete = 0 AND job_type = 'Full Time'
ORDER BY posts.post_date DESC
LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$stmt->bind_result($id, $title, $location, $job_type, $deadline, $needed_staffs, $salary, $post_date, $field_name, $company_name);

while ($stmt->fetch()) {
    $job_posts[] = [
        'id' => $id,
        'title' => $title,
        'location' => $location,
        'job_type' => $job_type,
        'deadline' => $deadline,
        'needed_staffs' => $needed_staffs,
        'salary' => $salary,
        'post_date' => $post_date,
        'field_name' => $field_name,
        'company_name' => $company_name,
    ];
}

$stmt->close();

?>

<h3 class='job-heading'>Explore your full-time jobs</h3>

<?php foreach ($job_posts as $job): 
$post_id = $job['id'];
$disabled = FALSE;
$statment = $conn->prepare($selectappliedpostquery);
$statment->bind_param('ii', $user_id, $post_id);
$statment->execute();
$statment->store_result();
if($statment->num_rows > 0){
    $disabled = TRUE;
}
$statment->close();
?>

<div class="job-post">
    <div class="job-title">
        <i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['title']); ?>
    </div>
    <div class="job-info">
        <i class="fas fa-building"></i>&nbsp;<?php echo htmlspecialchars($job['company_name']); ?>
    </div>
    <div class="job-info">
        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?>
    </div>
    <div class="job-info">
        <i class="fas fa-clock"></i> <?php echo htmlspecialchars($job['job_type']); ?>
    </div>
    <div class="job-info deadline">
        <i class="far fa-clock"></i> Deadline: <?php echo htmlspecialchars($job['deadline']); ?>
    </div>
    <div class="job-info">
        <span class="badge remote"><?php echo htmlspecialchars($job['field_name']); ?></span>
        <span class="badge competitive">Salary:&nbsp; <?php if (isset($_SESSION['user_id'])){ echo htmlspecialchars($job['salary']);}else{echo " Login to see salary";} ?></span>
        <span class="badge benefits"><?php echo htmlspecialchars($job['needed_staffs']); ?> &nbsp; is(are) needed</span>
        <?php if($disabled): ?>
            <span class="badge bg-danger">Already applied</span>
        <?php endif; ?>
    </div>
    <a href="?content=<?php echo ($is_loggin)? 'jobdetail&id='.$job['id'] : 'not-loggedin'; ?>" class="apply-btn button <?php if($disabled) echo "disabled";  ?> ">Apply Now</a>
</div>
<?php endforeach; ?>

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?content=fulltime&page=<?php echo $page - 1; ?>" class="page-link">&laquo; Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?content=fulltime&page=<?php echo $i; ?>" class="page-link <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?content=fulltime&page=<?php echo $page + 1; ?>" class="page-link">Next &raquo;</a>
    <?php endif; ?>
</div>

<?php $conn->close(); ?>

<style>
    .pagination {
        display: flex;
        justify-content: center;
        margin: 20px 0;
    }
    .pagination .page-link {
        display: inline-block;
        padding: 10px 15px;
        margin: 0 5px;
        border-radius: 5px;
        background-color: #f4f4f4;
        color: #333;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    .pagination .page-link:hover {
        background-color: #ddd;
    }
    .pagination .page-link.active {
        background-color: #11ca00;
        border-color: #11ca00;
        color: #fff;
    }
</style>
