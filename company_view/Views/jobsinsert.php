<?php
include '../database.php';

$jobfields = [];
$jobfieldquery = "SELECT id, field_name FROM field WHERE is_delete = 0";
$stmt = $conn->prepare($jobfieldquery);
$stmt->execute();
$stmt->bind_result($field_id, $field_name);
while ($stmt->fetch()) {
    $jobfields[] = ['id' => $field_id, 'name' => $field_name];
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $needed_staff = $_POST['needed_staff'];
    $requirement = $_POST['requirement'];
    $jd = $_POST['jd'];
    $salary = $_POST['salary'];
    $deadline = $_POST['deadline'];
    $company_profile = $_POST['company_profile'];
    $job_type = $_POST['job_type'];
    $job_field = intval($_POST['job_field']);
    $post_date = date("Y-m-d H:i:s");
    $company_id = intval($_SESSION['company_id']);
    $is_delete = 0;
    $location = $_POST['location'];
    $insert_query = "INSERT INTO posts (title, needed_staffs, requirements, jd, location, salary, deadline, profile_link, job_type, field_id, post_date, company_id, is_delete) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sisssssssissi", $title, $needed_staff, $requirement, $jd, $location, $salary, $deadline, $company_profile, $job_type, $job_field, $post_date, $company_id, $is_delete);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Job created successfully.";
        header("Location:?content=jobs");
        exit();
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
    $conn->close();
}
?>

<div class="" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Job</h5>
            </div>
            <div class="modal-body">
                <form id="createForm" method="POST">
                    <div class="form-group">
                        <label for="title">Job Title*:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Job Location*:</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="needed_staff">Needed Staffs*:</label>
                        <input type="number" class="form-control" id="needed_staff" name="needed_staff" required>
                    </div>
                    <div class="form-group">
                        <label for="requirement">Requirements*:</label>
                        <textarea id="requirement" name="requirement" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jd">Job Description*:</label>
                        <textarea id="jd" name="jd" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary Range*:</label>
                        <input type="text" class="form-control" id="salary" name="salary" required>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Deadline*:</label>
                        <input type="date" class="form-control" id="deadline" name="deadline" required>
                    </div>
                    <div class="form-group">
                        <label for="company_profile">Company Website*:</label>
                        <input type="text" class="form-control" id="company_profile" name="company_profile" required>
                    </div>
                    <div class="form-group">
                        <label for="job_type">Job Type*:</label>
                        <select class="form-control" id="job_type" name="job_type" required style="padding: 0 5px;">
                            <option value="" disabled selected hidden>Please choose your job type</option>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                            <option value="Paid Internship">Paid Internship</option>
                            <option value="Volunteer">Volunteer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="job_field">Job Field*:</label>
                        <select class="form-control" id="job_field" name="job_field" required style="padding: 0 5px;">
                            <option value="" disabled selected hidden>Please choose a job field</option>
                            <?php foreach ($jobfields as $jobfield): ?>
                                <option value="<?php echo $jobfield['id']; ?>"><?php echo htmlspecialchars($jobfield['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="btn-gp">
                        <button type="button" class="btn field-cancel-btn" id="cancel">CANCEL</button>
                        <button type="submit" class="btn field-create-btn btn-success">CREATE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('cancel').addEventListener('click', function () {
        window.history.back();
    });
    ClassicEditor
            .create( document.querySelector( '#requirement' ) )
            .catch( error => {
                console.error( error );
            } );
    ClassicEditor
            .create( document.querySelector( '#jd' ) )
            .catch( error => {
                console.error( error );
    } );
    document.getElementById('createForm').addEventListener('submit', function(event) {
        document.querySelector('#requirement').value = requirementEditor.getData();
        document.querySelector('#jd').value = jdEditor.getData();
        if (requirementEditor.getData().trim() === '' || jdEditor.getData().trim() === '') {
            alert('Please fill out the requirement and job description fields.');
            event.preventDefault();
            return false;
        }
    });
</script>
</body>
</html>
