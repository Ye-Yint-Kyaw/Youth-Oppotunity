<?php

$dbhost = '127.0.0.1';
$dbuser = 'root';
$dbpass = '1111';
$dbname = 'youth';

mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$conn) {
    die("error");
}

if (!mysqli_select_db($conn, $dbname)) {
    die("can not select db.");
} 

