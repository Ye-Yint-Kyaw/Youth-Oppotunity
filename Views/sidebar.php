<?php
ob_start();
include '../database.php';
$profile = '../img/pp.jpg';
$name = 'Name';
if($view == "company"){
    $company_id = 0;
    if(isset($_SESSION['company_id'])){
        $company_id = $_SESSION['company_id'];
    }
    $stmt = $conn->prepare("SELECT company_name,profile FROM company WHERE id = ?");
    $stmt->bind_param('i', $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if($user['profile']){
        $profile = $user['profile'];
    }
    if($user['company_name']){
        $name = $user['company_name'];
    }
}else{
    $admin_id = 0;
    if(isset($_SESSION['admin_id'])){
        $admin_id = $_SESSION['admin_id'];
    }
    $stmt = $conn->prepare("SELECT name, profile FROM users WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('i', $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if($user['profile']){
        $profile = $user['profile'];
    }
    if($user['name']){
        $name = $user['name'];
    }
}
?>
<div class="sidebar">
        <div class="logo">
            <a href="#"><img src="../img/green_logo.png" alt="Logo"></a>
            <i class="fa-solid fa-bars"></i>
        </div>
        <div class="menu">
            <?php if($view == "company") {?>
                <ul>
                    <li class="<?php echo ($contentToShow == 'dashboard') ? 'active' : ''; ?>">
                        <a href="?content=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard </a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'users') ? 'active' : ''; ?>">
                        <a href="?content=users"><i class="fas fa-users"></i> Candidates Lists</a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'jobs') ? 'active' : ''; ?>">
                        <a href="?content=jobs"><i class="fas fa-briefcase"></i> Job Lists</a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'packages') ? 'active' : ''; ?>">
                        <a href="?content=packages"><i class="fas fa-box"></i> Packages</a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'billhistory') ? 'active' : ''; ?>">
                        <a href="?content=billhistory"><i class="fas fa-history"></i>                        </i> Bill History</a>
                    </li>
                </ul>

            <?php }else { ?>

                <ul>
                    <li class="<?php echo ($contentToShow == 'dashboard') ? 'active' : ''; ?>">
                        <a href="?content=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard </a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'users') ? 'active' : ''; ?>">
                        <a href="?content=users"><i class="fas fa-users"></i> User Lists</a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'company') ? 'active' : ''; ?>">
                        <a href="?content=company"><i class="fas fa-users"></i> Company Lists</a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'field') ? 'active' : ''; ?>">
                        <a href="?content=field"><i class="fas fa-briefcase"></i> Field Lists</a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'packages') ? 'active' : ''; ?>">
                        <a href="?content=packages"><i class="fas fa-box"></i> Packages List</a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'userfeedbacks') ? 'active' : ''; ?>">
                        <a href="?content=userfeedbacks"><i class="fas fa-briefcase"></i> User Feedbacks </a>
                    </li>
                    <li class="<?php echo ($contentToShow == 'billpayment') ? 'active' : ''; ?>">
                        <a href="?content=billpayment"><i class="fas fa-file-invoice-dollar"></i>Bill Payment </a>
                    </li>
                </ul>

            <?php } ?>
        </div>
        <div class="bottom">
            <img src="<?php echo $profile; ?>" alt="Profile Picture">
            <div class="profile-info">
                <p class="user-names"><a href="?content=myaccount"><?php echo $name; ?></a></p>
                <p class="user-names"><a href= <?php echo $logout; ?>>Logout</a></p>
            </div>
        </div>
    </div>

    <?php
ob_end_flush(); // Flush the output buffer and turn off output buffering
?>