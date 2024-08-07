<?php
ob_start();
include 'database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_loggin = FALSE;
$user_id = 0;
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $is_loggin = TRUE;
}
$stmt = $conn->prepare("SELECT profile FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$default_profile_img = 'img/pp.jpg';
?>

<div class="nav-sec">
    <nav>
        <div>
            <div class="left-bar">
                <div class="logo">
                    <a href="?content=home"><img src="img/logo.png" alt="Logo"></a>
                </div>
                <div class="menus">
                    <ul>
                        <li><a href="?content=home" class="<?php echo ($contentToShow == 'home') ? 'active' : ''; ?>">HOME</a></li>
                        <li><a href="?content=fulltime" class="<?php echo ($contentToShow == 'fulltime') ? 'active' : ''; ?>">FULL TIME</a></li>
                        <li><a href="?content=parttime" class="<?php echo ($contentToShow == 'parttime') ? 'active' : ''; ?>">PART TIME</a></li>
                        <li><a href="?content=paidinternship" class="<?php echo ($contentToShow == 'paidinternship') ? 'active' : ''; ?>">PAID INTERNSHIP</a></li>
                        <li><a href="?content=volunteer" class="<?php echo ($contentToShow == 'volunteer') ? 'active' : ''; ?>">VOLUNTEER</a></li>
                        <li><a href="?content=aboutus" class="<?php echo ($contentToShow == 'aboutus') ? 'active' : ''; ?>">ABOUT US</a></li>
                        <?php if($is_loggin){ ?>
                            <li><a href="?content=contactus" class="<?php echo ($contentToShow == 'contactus') ? 'active' : ''; ?>">CONTACT US</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="right-bar">
                <?php if($is_loggin){ ?>
                <div class="profile-btn">
                    <div class="dropdown-center">
                        <?php
                        $profile_img = !empty($user['profile']) ? htmlspecialchars($user['profile']) : $default_profile_img;
                        ?>
                        <img src="<?php echo $profile_img; ?>" onclick="toggleDropdown()" class="pp rounded-circle dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <ul class="dropdown-menu dropdown-content" id="dropdownContent">
                            <li>
                                <a class="dropdown-item" href="?content=myaccount">
                                    <?php 
                                        if ($_SESSION['user_name']) {
                                            echo $_SESSION['user_name'];
                                        } 
                                    ?>
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="authentication/logout.php">Logout</a></li>
                        </ul>
                    </div> 
                </div>
                <?php }else{ ?>
                    <a href="authentication/login.php" class = "user-login"><i class="fa-solid fa-right-to-bracket"></i> &nbsp; LOGIN</a>
                <?php } ?>
            </div>

           
        </div>
    </nav>
</div>
<script>
    function toggleDropdown() {
        document.getElementById("dropdownContent").classList.toggle("show");
    }
    window.onclick = function(event) {
        if (!event.target.matches('.pp')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>

<?php
ob_end_flush(); // End output buffering and flush it
?>
