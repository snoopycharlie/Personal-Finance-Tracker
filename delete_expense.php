<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location:login.php");
    exit();
}
include "db.php";
$id=$_GET['id'];
$user_id=$_SESSION['user_id'];

$query="DELETE FROM expenses where id = $id AND user_id='$user_id'";
mysqli_query($conn,$query);

header("Location:reports.php");
exit();
?>