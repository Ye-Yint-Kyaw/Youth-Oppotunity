<?php
include 'database.php';
$job_id = -1;
$title = $location = $job_type = $deadline = $description = $requirements = '';
if(isset($_GET['id'])) {
    $job_id = intval($_GET['id']);
    $query = "SELECT title, location, job_type, deadline, jd, requirements FROM posts WHERE id = ? AND is_delete = 0";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    
    if (!$stmt) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $stmt->bind_result($title, $location, $job_type, $deadline, $description, $requirements);
    $stmt->fetch();
    $stmt->close();
}else{
    die('No have id');
}
?>

<h3 class='job-heading detail'>Job Details</h3>

<div class="job-post">
    <div class="job-title">
        <i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($title); ?>
    </div>
    <div class="job-info">
        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($location); ?>
    </div>
    <div class="job-info">
        <i class="fas fa-clock"></i> <?php echo htmlspecialchars($job_type); ?>
    </div>
    <div class="job-info deadline">
        <i class="far fa-clock"></i> Deadline: <?php echo htmlspecialchars($deadline); ?>
    </div>
    <div class="job-descriptions">
        <h4>Job Description</h4>
        <?php echo htmlspecialchars_decode($description); ?>
    </div>
    <div class="job-requirements">
        <h4>Requirements</h4>
        <?php echo htmlspecialchars_decode($requirements); ?>
    </div>
    <a href="?content=applyform&id=<?php echo $job_id; ?>" class="apply-btn button">Apply Now</a>
</div>
