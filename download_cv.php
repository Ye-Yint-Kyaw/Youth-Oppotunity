<?php
session_start();
include 'database.php';

if (isset($_GET['cv'])) {
    $cv_path = $_GET['cv'];
    if (!$cv_path || !file_exists($cv_path)) {
        die("CV file not found.");
    }
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment"); 
    header("Content-Type: application/pdf");
    readfile($cv_path);
    exit;
} else {
    die("CV file not specified.");
}
?>
