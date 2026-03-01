<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location:login.php");
    exit();

}
include "db.php";
$user_id=$_SESSION['ID'];
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
</head>
<body class="bg-light">
    
<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <span class="navbar-brand">Finance Tracker</span>
    <div> Welcome, <?php echo $_SESSION['user_name']; ?>
        <a href="logout.php" class="btn btn-outline-danger btn-sm ms-3">Logout</a>
    </div>
</nav>
<div class="container mt-4">
  <div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card shadow p-4">
            <h5>Total Income</h5>
            <h2>₹<?php echo $total_income; ?></h2>
        </div>   
     </div>
     <div class="col-md-6">
        <div class="card shadow p-4">
            <h5>This Month</h5>
            <h2>₹<?php echo $this_month;?></h2>
        </div>
     </div>

  </div>
  <div class="card shadow p-4">
    <h5 class="mb-3">Recent Income </h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Amount</th>
                <th>Category</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row=mysqli_fetch_assoc($recent_result)){?>
            <tr>
                <td>₹<?php echo $row['amount'];?></td>
                <td><?php echo $row['category'];?></td>
                <td><?php echo $row['date'];?></td>
            </tr>
            <?php }?>
        </tbody>
</table>  
</div>
</div>    
</body>
</html>