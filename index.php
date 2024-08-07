<?php
session_start();
ob_start(); // Start output buffering at the very beginning
$profileimg = "img/pp.jpg";
include 'database.php';

$contentToShow = isset($_GET['content']) ? $_GET['content'] : 'home';

// Ensure the header redirection does not output any content before it.
if ($contentToShow === "not-loggedin") {
    header("Location: authentication/login.php");
    exit;
}

$css_link = "css/nav.css";
$common_css = "common.css";
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'Views/header.php'; ?>
<body>
<div class="nav-bar" id="bar">
    <?php include 'Views/nav.php'; ?>
</div>
 
<div class="body-content">
    <?php include 'Views/' . $contentToShow . '.php'; ?>
</div>

<?php include 'Views/footer.php'; ?>

<script>
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('bar');
        if (window.scrollY > 50) {
            navbar.style.boxShadow = '0 2px 4px 1px #dadada';
            navbar.style.backgroundColor = '#fdfdfd';
        } else {
            navbar.style.boxShadow = 'none';
            navbar.style.backgroundColor = '#ffffff';
        }
    });
</script>
</body>
</html>

<?php
// //Merchant's account information
// $merchant_id = "JT01";			//Get MerchantID when opening account with 2C2P
// $secret_key = "7jYcp4FxFdf0";	//Get SecretKey from 2C2P PGW Dashboard

// //Transaction information
// $payment_description  = '2 days 1 night hotel room';
// $order_id  = time();
// $currency = "702";
// $amount  = '000000002500';

// //Request information
// $version = "8.5";	
// $payment_url = "https://demo2.2c2p.com/2C2PFrontEnd/RedirectV3/payment";
// $result_url_1 = "http://localhost/devPortal/V3_UI_PHP_JT01_devPortal/result.php";

// //Construct signature string
// $params = $version.$merchant_id.$payment_description.$order_id.$currency.$amount.$result_url_1;
// $hash_value = hash_hmac('sha256',$params, $secret_key,false);	//Compute hash value

// echo 'Payment information:';
// echo '<html> 
// <body>
// <form id="myform" method="post" action="'.$payment_url.'">
//     <input type="hidden" name="version" value="'.$version.'"/>
//     <input type="hidden" name="merchant_id" value="'.$merchant_id.'"/>
//     <input type="hidden" name="currency" value="'.$currency.'"/>
//     <input type="hidden" name="result_url_1" value="'.$result_url_1.'"/>
//     <input type="hidden" name="hash_value" value="'.$hash_value.'"/>
// PRODUCT INFO : <input type="text" name="payment_description" value="'.$payment_description.'"  readonly/><br/>
//     ORDER NO : <input type="text" name="order_id" value="'.$order_id.'"  readonly/><br/>
//     AMOUNT: <input type="text" name="amount" value="'.$amount.'" readonly/><br/>
//     <input type="submit" name="submit" value="Confirm" />
// </form>  

// <script type="text/javascript">
//     document.forms.myform.submit();
// </script>
// </body>
// </html>';	 

ob_end_flush(); // End output buffering and flush it
?>
