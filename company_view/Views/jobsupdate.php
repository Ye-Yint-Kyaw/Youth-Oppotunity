<?php

include '../database.php';



// Check if job_id is provided in the URL
if(isset($_GET['id'])) {
    $job_id = $_GET['id'];
    $jobfields = [];
    $jobfieldquery = "SELECT id, field_name FROM field WHERE is_delete = 0";
    $stmt = $conn->prepare($jobfieldquery);
    $stmt->execute();
    $stmt->bind_result($field_id, $field_name);
    while ($stmt->fetch()) {
        $jobfields[] = ['id' => $field_id, 'name' => $field_name];
    }

    // Fetch job details based on job ID
    $fetch_query = "SELECT * FROM posts WHERE id=?";
    $stmt = $conn->prepare($fetch_query);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $stmt->bind_result($id, $title, $job_type, $staffs, $requirements, $jd, $location ,$company_id, $salary, $post_date, $deadline, $profile_link, $field_id, $is_delete);

    $stmt->fetch();
    $stmt->close();
} else {
    // Redirect if job_id is not provided
    header("Location: jobs.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated job details from the form
    $title = $_POST['title'];
    $needed_staff = intval($_POST['needed_staff']);
    $requirements = $_POST['requirements'];
    $jd = $_POST['jd'];
    $salary = $_POST['salary'];
    $deadline = $_POST['deadline'];
    $profile_link = $_POST['profile_link'];
    $job_type = $_POST['job_type'];
    $field_id = $_POST['field_id'];
    $company_id = intval($_SESSION['company_id']);
    $location = $_POST['location'];
    $update_query = "UPDATE posts SET title=?, needed_staffs=?, requirements=?, jd=?, location=?, salary=?, deadline=?, profile_link=?, job_type=?, field_id=? WHERE id=? AND company_id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sisssssssiii", $title, $needed_staff, $requirements, $jd, $location, $salary, $deadline, $profile_link, $job_type, $field_id, $job_id, $company_id);

    if ($stmt->execute()) {
        header("Location: ?content=jobs");
        exit();
    } else {
        echo "Error updating job: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
    $conn->close();
}


?>

<div class="" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Your Job</h5>
            </div>
            <div class="modal-body">
                <form id="updateForm" method="POST">
                    <input type="hidden" name="job_id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="title">Job Title*:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Job Location*:</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="needed_staff">Needed Staffs*:</label>
                        <input type="number" class="form-control" id="needed_staff" name="needed_staff" value="<?php echo $staffs; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="requirement">Requirements*:</label>
                        <textarea id="requirement" name="requirements" class="form-control" required><?php echo htmlspecialchars($requirements); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jd">Job Description*:</label>
                        <textarea id="jd" name="jd" class="form-control" required><?php echo htmlspecialchars($jd); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary Range*:</label>
                        <input type="text" class="form-control" id="salary" name="salary" value="<?php echo htmlspecialchars($salary); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Deadline*:</label>
                        <input type="date" class="form-control" id="deadline" name="deadline" value="<?php echo $deadline; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="company_profile">Company Website*:</label>
                        <input type="text" class="form-control" id="company_profile" name="profile_link" value="<?php echo htmlspecialchars($profile_link); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="job_type">Job Type*:</label>
                        <select class="form-control" id="job_type" name="job_type" required style="padding: 0 5px;">
                            <option value="Full Time" <?php if ($job_type == 'Full Time') echo 'selected'; ?>>Full Time</option>
                            <option value="Part Time" <?php if ($job_type == 'Part Time') echo 'selected'; ?>>Part Time</option>
                            <option value="Paid Internship" <?php if ($job_type == 'Paid Internship') echo 'selected'; ?>>Paid Internship</option>
                            <option value="Volunteer" <?php if ($job_type == 'Volunteer') echo 'selected'; ?>>Volunteer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="job_field">Job Field*:</label>
                        <select class="form-control" id="job_field" name="field_id" required style="padding: 0 5px;">
                            <?php foreach ($jobfields as $jobfield): ?>
                                <option value="<?php echo $jobfield['id']; ?>" <?php if ($field_id == $jobfield['id']) echo 'selected'; ?>><?php echo htmlspecialchars($jobfield['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="btn-gp">
                        <button type="button" class="btn field-cancel-btn" id="cancel">CANCEL</button>
                        <button type="submit" class="btn field-create-btn btn-success">UPDATE</button>
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
    $(document).ready(function() {
        $('#requirement').summernote({
            height: 200,  
            placeholder: 'Enter job requirements here...',
            tabsize: 2
        });

        $('#jd').summernote({
            height: 200, 
            placeholder: 'Enter job description here...',
            tabsize: 2
        });
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
        // Ensure CKEditor content is updated in the respective textarea
        document.querySelector('#requirement').value = requirementEditor.getData();
        document.querySelector('#jd').value = jdEditor.getData();

        // Manually validate the CKEditor fields
        if (requirementEditor.getData().trim() === '' || jdEditor.getData().trim() === '') {
            alert('Please fill out the requirement and job description fields.');
            event.preventDefault();
            return false;
        }
    });
</script>
</body>
</html>
