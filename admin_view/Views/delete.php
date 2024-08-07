<?php
include('../database.php');
$css_link = "css/nav.css";
$common_css = "common.css";
    

$id = $_GET['id'];

$sql = "Delete from contactform where id='$id'";


mysqli_query($conn,$sql);
header("Location: ?content=userfeedbacks");

    // echo "<script>alert('Successfully Delete')
    // window.location='?content=userfeedbacks';</script>";
