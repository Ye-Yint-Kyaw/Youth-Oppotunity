<?php
session_start();
$id = 0;

$css_link = "../css/sidebar.css";
$common_css = "../common.css";
$profileimg="../img/pp.jpg";
$logout = "authentication/logout.php";
$contentToShow = isset($_GET['content']) ? $_GET['content'] : 'dashboard';
if(!isset($_SESSION['company_id'])){
    header('Location: authentication/login.php');
}else{
    $id = intval($_SESSION['company_id']);
}
$header = "";
switch ($contentToShow) {
    case "dashboard":
        $header = "Dashboard";
        break;
    case "jobs":
        $header = "Jobs Lists";
        break;
    case "jobsinsert":
        $header = "Jobs Create";
        break;
    case "jobsupdate":
        $header = "Jobs Edit";
        break;
    case "packages":
        $header = "Packages";
        break;
    case "users":
        $header = "Candidates Lists";
        break;
    case "billhistory":
        $header = "Bill History";
        break;
    default:
        $header = "";
        break;
}

$view = "company";

$view = "company";

?>

<!DOCTYPE html>
<html lang="en">
<?php include '../Views/header.php' ?>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include '../Views/sidebar.php' ?>
    <div class="main-content">
    <h1><?php echo $header ?> </h1>
    <div class="table-responsive">
        <?php  include 'Views/' . $contentToShow . '.php'; ?>
    </div>
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
