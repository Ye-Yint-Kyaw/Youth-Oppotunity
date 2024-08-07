<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bid']) && isset($_POST['pid']) && isset($_POST['cid'])) {
    $bid = intval($_POST['bid']);
    $pid = intval($_POST['pid']);
    $cid = intval($_POST['cid']);
    $update_company_query = "UPDATE company SET package_id = $pid WHERE id = $cid";
    $company_updated = mysqli_query($conn, $update_company_query);
    $update_bill_query = "UPDATE bill SET is_new = '0' WHERE id = $bid";
    $bill_updated = mysqli_query($conn, $update_bill_query);

    if ($company_updated && $bill_updated) {
        $response['success'] = true;
        $response['message'] = "Package updated successfully and bill marked as not new!";
    } else {
        $response['message'] = "Error updating package or bill: " . mysqli_error($conn);
    }

    echo "<script>window.history.back();</script>";
} else {
    $response['message'] = "Invalid request.";
}
?>
