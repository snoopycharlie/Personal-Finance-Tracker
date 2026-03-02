<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location:login.php");
exit();       
}
include "db.php";
$user_id=$_SESSION['user_id'];
/*--------total Income-----------------*/
$total_query="SELECT SUM(amount) AS total FROM income WHERE user_id ='$user_id'";
$total_result=mysqli_query($conn,$total_query);
$total_income=mysqli_fetch_assoc($total_result)['total']??0;
/*------this month income---------- */
$month_query="
     SELECT SUM(amount) AS month_total
     FROM income
     WHERE user_id ='$user_id'
     AND MONTH(date)=MONTH(CURRENT_DATE())
     AND YEAR(date)=YEAR(CURRENT_DATE())
";
$month_result=mysqli_query($conn,$month_query);
$this_month=mysqli_fetch_assoc($month_result)['month_total']??0;
/*-------------------recent income-------------------------- */
$recent_query="
SELECT * FROM income
WHERE user_id='$user_id'
ORDER BY date DESC
LIMIT 5
";
$recent_result=mysqli_query($conn, $recent_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body class="bg-light">
<div class="container">
    <div class="sidebar">
        <h2>Finance</h2>
        <ul>
            <li>Dashboard</li>
            <li>Add Income</li>
            <li>Add Expense</li>
            <li>Reports</li>
            <li>Savings</li>
            <li><a href="logout.php">Logout</a></li>
        </ul>  
    </div> 
  <div class="main">
    <div class ="topbar">
    <h2>Dashboard</h2>
    <div class="user-info">
        Welcome, <?php echo $_SESSION['Name'] ?? 'User'; ?> 
    </div>
     </div>       
   <!-- <div class="content">
    <p>This is your dashboard overview.</p> 
    </div> -->
   <div class="content">
       <div class="cards">
        <div class="card-box">
            <h5>Total Income</h5>
            <h3>₹<?php echo $total_income; ?></h3>
        </div>
        <div class="card-box">
            <h5>This Month</h5>
            <h3>₹<?php echo $this_month; ?></h3>
        </div>
        <div class="card-box">
            <h5>Total Expense</h5>
            <h3>₹0</h3>
        </div>
        <div class="card-box">
            <h5>Savings</h5>
            <h3>₹<?php echo $total_income-0; ?></h3>
        </div>
    </div>
</div>
</div>
</body>
</html>