<?php
include '../database.php';
$id = 0;
if (isset($_SESSION['company_id'])) {
    $id = intval($_SESSION['company_id']);
}
$package_query = "SELECT p.title, COUNT(up.post_id) as post_count FROM posts p LEFT JOIN user_post up ON p.id = up.post_id WHERE p.company_id = $id GROUP BY p.title";
$package_result = mysqli_query($conn, $package_query);
$package_data = [];
if ($package_result->num_rows > 0) {
    while ($row = $package_result->fetch_assoc()) {
        $package_data[$row['title']] = $row['post_count'];
    }
}

// Check if $package_data is not empty before calling max()
if (!empty($package_data)) {
    $maxY = max(8, ceil(max($package_data) / 10) * 10);
} else {
    $maxY = 8;
}

$user_query = "SELECT COUNT(id) as user_count FROM posts WHERE company_id = $id AND is_delete = '0'";
$user_result = mysqli_query($conn, $user_query);

$user_count = 0;

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $row = mysqli_fetch_assoc($user_result);
    $user_count = $row['user_count'];
} else {
    die("No data");
}

$package_data_json = json_encode($package_data);
?>

<div class="dashboard-content" style="padding-top: 20px;">
    <div class="stats" style="display: flex; justify-content: space-between; align-items: flex-start;">
        <!-- Total Users Stat Box -->
        <div class="stat-box" style="width: 30%; background-color: #11ca00; color: white; padding: 20px; border-radius: 10px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">
                <h2 style="font-size: 2.5em; margin-bottom: 10px;"><?php echo $user_count; ?></h2>
                <p style="font-size: 1.2em; margin: 0;">Total Job Posts</p>
            </div>
            <div style="margin-top: auto;">
                <img src="./uploads/jobs.png" alt="User Image" style="width: 150px; height: 150px; border-radius: 50%;">
            </div>
        </div>

        <!-- Chart Container -->
        <div class="chart-container" style="width: 65%; background-color: #fff; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px;">
            <h3 style="font-size: 1.8em; margin-bottom: 20px; color: #333;">Packages Selected by Companies</h3>
            <canvas id="packageChart" style="width: 100%; height: 300px;"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const packageData = <?php echo $package_data_json; ?>;

        // Prepare data for the package chart
        const packageLabels = Object.keys(packageData);
        const packageCounts = Object.values(packageData);
        
        const packageChartCtx = document.getElementById('packageChart').getContext('2d');
        const packageChart = new Chart(packageChartCtx, {
            type: 'bar',
            data: {
                labels: packageLabels,
                datasets: [{
                    label: 'Number of Job Posts',
                    data: packageCounts,
                    backgroundColor: [
                        '#11ca00', 
                        '#FF5733', 
                        '#C70039',  
                        '#900C3F', 
                        '#581845',  
                        '#FFC300',  
                        '#DAF7A6',  
                        '#FF33FF'  
                    ],
                    borderColor: [
                        '#11ca00', 
                        '#FF5733', 
                        '#C70039',  
                        '#900C3F', 
                        '#581845',  
                        '#FFC300',  
                        '#DAF7A6',  
                        '#FF33FF'  
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            min: 0,
                            max: <?php echo $maxY; ?>
                        }
                    }
                }
            }
        });
    });
</script>
