<?php
session_start();
include '../database.php';
$success = FALSE;
$profileimg="../img/pp.jpg";
$name = "";
$css_link = "../css/sidebar.css";
$common_css = "../common.css";
$logout = "authentication/logout.php";
if (!isset($_SESSION['admin_id'])) {
    header('Location: authentication/login.php');
    exit();
}else{
    $id = intval($_SESSION['admin_id']);
    $user_type = 'admin';
    $query = "SELECT name FROM users WHERE id = ? AND is_delete = 0 AND user_type = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $id, $user_type);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name);
    $stmt->fetch();
    $_SESSION['admin_name'] = $name;
}
$contentToShow = isset($_GET['content']) ? $_GET['content'] : 'dashboard';
switch ($contentToShow) {
    case 'dashboard':
        $header = "Dashboard";
        break;
    case 'jobs':
        $header = "Jobs Lists";
        break;
    case 'packages':
        $header = "Packages Lists";
        break;
    case 'packageinsert':  // newly added
        $header = "Insert Packages";
        break;
    case 'packageupdate':  // newly added
        $header = "Update Packages";
        break;
    case 'candidates':
        $header = "Candidates Lists";
        break;
    case 'users':   // newly added
        $header = "User List";
        break;
    case 'usersinsert':   // newly added
        $header = "Insert User";
        break;
    case 'company':
        $header = "Company List";
        break;
    case 'field':
        $header = "Field Lists";
        break;
    case 'deletefield':
        $header = "Delete Field";
        break;
    case 'fieldinsert':
        $header = "Insert Field";
        break;
    case 'updatefield':
        $header = "Update Field";
        break;
    case 'companyinsert':
        $header = "Insert Company";
        break;
    case 'companyupdate': // newly added
        $header = "Update Company";
        break;
    case 'myaccount':
        $header = "My Account";
        break;
    case 'accountupdate':   // newly added
        $header = "Update Account";
        break;
    case 'userfeedbacks':  // newly added
        $header = "User Feedbacks";
        break;
    case 'billpayment':  // newly added
        $header = "Bill Payment";
        break;
    default:
        $header = "Unknown Page";
        break;
}

$view = "admin";
?>

<!DOCTYPE html>
<html lang="en">
<?php include '../Views/header.php'; ?>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<!-- newly added -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php include '../Views/sidebar.php'; ?>
<div class="main-content">
    <h1><?php echo $header; ?></h1>
    <?php 
        include 'Views/' . $contentToShow . '.php'; 
    ?>
</div>

<script>
    $(document).ready(function(){
        $('#datatable').DataTable({
            "paging": true,
            "pageLength": 7
        });
    });
</script>
</body>
</html>
