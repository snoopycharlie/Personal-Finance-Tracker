<?php
session_start();
include "db.php";

$user_id = $_SESSION['user_id'];
if(!isset($_SESSION['user_id'])){
    header("Location:login.php");
    exit();
}

$expense_query="
SELECT *, MONTH(date) as month, YEAR(date) as year
FROM expenses
WHERE user_id='$user_id'
ORDER BY year DESC, month DESC, date DESC
";
$expense_result=mysqli_query($conn,$expense_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .report-table th, .report-table td {
            padding: 10px 14px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .month-header th {
            background-color: #4a90e2;
            color: white;
            font-size: 16px;
        }
        .col-header th {
            background-color: #f0f0f0;
            font-weight: 600;
        }
        .report-table tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body class="bg-light">
<div class="container">
    <div class="sidebar">
        <h2>Finance</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="add_income.php">Add Income</a></li>
            <li><a href="add_expense.php">Add Expense</a></li>
            <li><a href="reports.php">Reports</a></li>
            <li>Savings</li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar">
            <h2>Expense Reports</h2>
            <div class="user-info">
                Welcome, <?php echo $_SESSION['Name'] ?? 'User'; ?>
            </div>
        </div>

        <div class="content">
            <?php if(mysqli_num_rows($expense_result) > 0): ?>
            <table class="report-table">
                <?php
                $current_month = "";
                while($row = mysqli_fetch_assoc($expense_result)){
                    $month_year = date("F Y", strtotime($row['date']));
                    if($month_year != $current_month){
                        $current_month = $month_year;
                        echo "<tr class='month-header'>
                                <th colspan='4'>$current_month</th>
                              </tr>";
                        echo "<tr class='col-header'>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Note</th>
                              </tr>";
                    }
                    echo "<tr>
                            <td>" . $row['date'] . "</td>
                            <td>" . $row['category'] . "</td>
                            <td>₹" . $row['amount'] . "</td>
                            <td>" . ($row['note'] ?? '-') . "</td>
                          </tr>";
                }
                ?>
            </table>
            <?php else: ?>
                <p style="margin-top:20px;">No expense records found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>

