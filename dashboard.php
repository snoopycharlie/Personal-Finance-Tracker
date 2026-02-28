<?php
session_start();
if(!isset($_SESSION['ID'])){
    header("Location:login.php");
    exit();

}
?>


 <h2>Welcome<?php echo $_SESSION['Name']?></h2>
 <a href="logout.php">Logout</a>
 