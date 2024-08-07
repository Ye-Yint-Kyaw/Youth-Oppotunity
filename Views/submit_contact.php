<?php
include('../database.php');

$name=$_POST['name'];
$email = $_POST['email'];
$message=$_POST['message'];

$sql ="insert into contactform(name,email,message)
values ('$name','$email','$message')";


if (mysqli_query($conn,$sql))
{
    header("Location:../?content=contactus");
}
else
{
    echo "<script>alert('Submit failed!');
    window.location='contact.php';</script>";
}


?>
