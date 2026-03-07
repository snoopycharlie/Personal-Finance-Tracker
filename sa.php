<?php 
session_start();
if(!isset($_SESSION['user_id'])){
    header ("Location:login.php");
    exit();
}
include "db.php";

if(isset($_POST['sbtn'])){
    $user_id=$_SESSION['user_id'];
    $saved_amount=$_POST['saved_amount'];
    $target_amount=$_POST['target_amount'];
    $date=$_POST['date'];
    $note=$_POST['note'];

    if($saved_amount>0 && !empty($date)){
        $insert_query="INSERT INTO savings (`user_id`,`saved_amount`,`target_amount`,`date`,`note`)
        VALUES ('$user_id','$saved_amount','$target_amount','$date','$note')";

        mysqli_query($conn,$insert_query);
        header("Location:dashboard.php");
        exit();
    }else{
        echo"Please fill the detail";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saving</title>
</head>
<body>
    <form method="POST">
        Saved Amount:<input type="number" name="saved_amount" required>
        Target Amount:<input type="number" name="target_amount" >
        Date:<input type="date" name="date" required>
        Note:<textarea name="note"></textarea>
        <input type="submit" name="sbtn">
    </form>
</body>
</html>