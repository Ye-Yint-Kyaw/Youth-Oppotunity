<?php
include('database.php');


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/view.css">
</head>

<body>
    <?php
        $sql = "SELECT * FROM contactform";
        $result = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($result);
    ?>
    
    <div class="grid-container">
        <?php
        for ($i = 0; $i < $num_rows; $i++) {
            $record = mysqli_fetch_assoc($result);
        ?>
            <div class="grid-item">
                <h4>Contact View Of Customer</h4>
                <?php echo '<p><strong>Id Number = </strong>' . $record['id'] . '</p>'; ?>
                <?php echo '<p><strong>User Name = </strong>' . $record['name'] . '</p>'; ?>
                <?php echo '<p><strong>Email = </strong>' . $record['email'] . '</p>'; ?>
                <?php echo '<p><strong>Message = </strong>' . $record['message'] . '</p>'; ?>
            </div>
        <?php
        }
        ?>
    </div>

</body>

</html>
