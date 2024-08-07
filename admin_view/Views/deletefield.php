<?php
include '../database.php';
if(isset($_GET['id'])){
    $id = intval($_GET['id']);
}else{
    $id = -1;
}
if($id > -1){
    $stmt = $conn->prepare("UPDATE field SET is_delete = 1 WHERE id = ?");
    if (!$stmt) {
        die("Error in prepare statement: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        die("Error in execute statement: " . $stmt->error);
    }else{
        header('Location: ?content=field');
    }

    $stmt->close();
    $conn->close();
}

echo "<script>window.history.back();</script>";

?>