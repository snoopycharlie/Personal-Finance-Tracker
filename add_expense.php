<?php
session_start();
if(!isset($_SESSION ['user_id'])){
    header("Location:login.php");
    exit();
}
include "db.php";

if(isset($_POST['sbtn'])){
    $user_id= $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $note= $_POST['note'];
    
    if($amount > 0 && !empty($category) && !empty($date)){
        $insert_query="INSERT INTO expenses (user_id, amount, category, date, note)
        VALUES('$user_id', '$amount', '$category','$date','$note')";

        mysqli_query($conn, $insert_query);
        header("Location: dashboard.php");
        exit();
    }else{
        echo "Please fill all required fields properly.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
</head>
<body>
    <form method="POST">
        Amount: <input type="Number" name="amount">
        Category:<select name="category">
            <option value="Bills">Bills</option>
            <option value="Food">Food</option>
            <option value="Shopping">Shopping</option>
            <option value="Education">Education</option>
            <option value="Health Care">Health Care</option>
            <option value="Personal Expense">Personal Expense</option>
            <option value="Transportation">Transportation</option>
            <option value="Rent">Rent</option>
            <option value="Subscriptions">Subscriptions</option>
            <option value="Investments">Investments</option>
            <option value="EMIs">EMIs</option>
            <option value="Others">Others</option>
        </select>
        Date:<input type="Date" name="date">
        Note:<textarea name="note"></textarea>
        <input type="submit" name="sbtn">
    </form>
</body>
</html>
