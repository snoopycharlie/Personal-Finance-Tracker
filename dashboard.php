<?php
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($_SESSION['user_id'])){
    header("Location:login.php");
    exit();       
}
include "db.php";
$user_id = $_SESSION['user_id'];

/*-------- Total Income --------*/
$total_query = "SELECT SUM(amount) AS total FROM income WHERE user_id ='$user_id'";
$total_result = mysqli_query($conn, $total_query);
$total_income = mysqli_fetch_assoc($total_result)['total'] ?? 0;

/*-------- This Month Income --------*/
$month_query = "
    SELECT SUM(amount) AS month_total
    FROM income
    WHERE user_id ='$user_id'
    AND MONTH(date) = MONTH(CURRENT_DATE())
    AND YEAR(date) = YEAR(CURRENT_DATE())
";
$month_result = mysqli_query($conn, $month_query);
$this_month = mysqli_fetch_assoc($month_result)['month_total'] ?? 0;

/*-------- Recent Income --------*/
$recent_query = "SELECT * FROM income WHERE user_id='$user_id' ORDER BY date DESC LIMIT 5";
$recent_result = mysqli_query($conn, $recent_query);

/*-------- Total Expense & Savings --------*/
$total_expense_query = "SELECT SUM(amount) AS total_expense FROM expenses WHERE user_id='$user_id'";
$total_expense_result = mysqli_query($conn, $total_expense_query);
$total_expense = mysqli_fetch_assoc($total_expense_result)['total_expense'] ?? 0;
$saving = $total_income - $total_expense;

/*-------- Line Chart Data --------*/
$query = "SELECT MONTH(date) as month, SUM(amount) as total
          FROM expenses
          WHERE user_id='$user_id'
          GROUP BY MONTH(date)";
$result = mysqli_query($conn, $query);
$months = [];
$amounts = [];
while($row = mysqli_fetch_assoc($result)){
    $months[] = $row['month'];
    $amounts[] = $row['total'];
}

/*-------- Pie Chart Data --------*/
$pie_query = "SELECT category, SUM(amount) as total
              FROM expenses
              WHERE user_id='$user_id'
              GROUP BY category";
$pie_result = mysqli_query($conn, $pie_query);
$categories = [];
$totals = [];
while($row = mysqli_fetch_assoc($pie_result)){
    $categories[] = $row['category'];
    $totals[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .charts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px;
        }
        .chart-box {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            height: 250px;
            position: relative;
        }
        .chart-box h6 {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .chart-box canvas {
            max-height: 200px !important;
        }
    </style>
</head>
<body class="bg-light">
<div class="container">
    <div class="sidebar">
        <h2>Finance</h2>
        <ul>
            <li>Dashboard</li>
            <li><a href="income.php">Add Income</a></li>
            <li><a href="add_expense.php">Add Expense</a></li>
            <li><a href="reports.php">Reports</a></li>
            <li>Savings</li>
            <li><a href="logout.php">Logout</a></li>
        </ul>  
    </div> 

    <div class="main">
        <div class="topbar">
            <h2>Dashboard</h2>
            <div class="user-info">
                Welcome, <?php echo $_SESSION['Name'] ?? 'User'; ?> 
            </div>
        </div>       

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
                    <h3>₹<?php echo $total_expense; ?></h3>
                </div>
                <div class="card-box">
                    <h5>Savings</h5>
                    <h3>₹<?php echo $saving; ?></h3>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts">
            <div class="chart-box">
                <h6>Monthly Expenses</h6>
                <canvas id="lineChart"></canvas>
            </div>
            <div class="chart-box">
                <h6>Expense by Category</h6>
                <canvas id="pieChart"></canvas>
            </div>
            <div class="chart-box">
                <h6>Savings vs Expense</h6>
                <canvas id="gaugeChart"></canvas>
            </div>
        </div>

    </div><!-- end .main -->
</div><!-- end .container -->

<script>
    const months     = <?php echo json_encode($months); ?>;
    const amounts    = <?php echo json_encode($amounts); ?>;
    const categories = <?php echo json_encode($categories); ?>;
    const totals     = <?php echo json_encode($totals); ?>;

    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Monthly Expense',
                data: amounts,
                borderWidth: 2,
                borderColor: '#6c63ff',
                backgroundColor: 'rgba(108,99,255,0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: categories,
            datasets: [{
                data: totals
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

new Chart(document.getElementById('gaugeChart'), {
    type: 'doughnut',
    data: {
        labels: ['Saved', 'Yet to Save'],
        datasets: [{
            data: [<?php echo $saving; ?>, <?php echo $total_income - $saving; ?>],
            backgroundColor: ['#fffd7d', '#ef4488'],
            borderWidth: 0
        }]
    },
    options: {
        rotation: -90,
        circumference: 180,
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { enabled: true }
        }
    }
});
</script>
</body>
</html>