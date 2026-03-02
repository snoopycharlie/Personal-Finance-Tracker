<?php 
$expense_query="
SELECT *, MONTH(date) as month, YEAR(date) as year
FROM expenses
WHERE user_id='$user_id'
ORDER BY year DESC, month DESC, date DESC
";
$expense_result=mysqli_query($conn,$expense_query);

$current_month="";
?>
<table border="1" cellpadding="8">
<?php while($row=mysqli_fetch_assoc($expense_result)){
    $month_year = date("F Y", strtotime($row['date']));
    if($month_year!=$current_month){
        $current_month=$month_year;
        echo"<h3 style='margin-top:20px;'>$current_month</h3>";
    

<tr>
    <td><?php echo $row['date'];?></td>
    <td><?php echo $row['category'];?></td>
    <td><?php echo $row['amount'];?></td>
    <td><?php echo $row['note'];?></td>
</tr>
<?php } ?>


