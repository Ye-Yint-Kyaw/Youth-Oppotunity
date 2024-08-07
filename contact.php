<?php
session_start();
include('database.php');
$css_link = "css/nav.css";
$common_css = "common.css";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'Views/nav.php'; ?>
<?php include 'Views/header.php'; ?>


<?php
        $sql = "select * from contactform";

        $result = mysqli_query($conn,$sql);

        $num_rows = mysqli_num_rows($result);
    ?>
    <?php
for ($i = 0; $i < $num_rows; $i++) {

?>
<?php
$record = mysqli_fetch_assoc($result);
?>

    <?php

    ?>
   


    <div class="contact-container">
          <div class="contact-form">
            <h2>Contact View Of Customer</h2>
            <div class="form-group">
                    <label for="name">Id Number</label>
                    <input type="text" value = "<?php echo $record['id']?>" required>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value = "<?php echo $record['name']?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value = "<?php echo $record['email']?>" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required><?php echo $record['message']?></textarea>
                </div>
        </div>
    </div>
    <br><br>
    <?php
}

?>
<?php include 'Views/footer.php'; ?>




    
</body>
</html>
