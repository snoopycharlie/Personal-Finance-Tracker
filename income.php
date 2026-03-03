<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location:login.php");
    exit();
}
include "db.php";

if(isset($_POST['sbtn'])){
    $user_id=$_SESSION['user_id'];
    $amount=$_POST['amount'];
    $date=$_POST['date'];
    $note=$_POST['note'];

    if($amount >0 && !empty($date)){
        $insert_query = "INSERT INTO income (user_id,amount,date, note)
        VALUES ('$user_id','$amount','$date')";

        mysqli_query($conn, $insert_query);
        header("Location:dashboard.php");
        exit();
    }else{
        echo"Please fill the details:";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income!</title>
</head>
<body>
    <form method="POST">
        Amount:<input type="number" name="amount">
        Date:<input type="date" name="date">
        Note:<textarea name="note"></textarea>
        <input type="submit" name="sbtn">
    </form>
</body>
</html>