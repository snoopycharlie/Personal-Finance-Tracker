<?php
     session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h1>IF You Have Already Register Then Login From Here! </h1>
    <form method="POST">
        Email:<input type="text" name="mail">
        Password:<input type="password" name="pass">
        <input type="Submit" name="sbbtn">
    </form>
    <?php 
    // session_start();
     $con=mysqli_connect('localhost','root','','users');
     if(isset($_POST['sbbtn'])){
        $mail=$_POST['mail'];
        $input_pass=$_POST['pass'];
        $query="SELECT * FROM userdata WHERE Email='$mail'";
        $result=mysqli_query($con,$query);
        if(mysqli_num_rows($result)==1){
            $row = mysqli_fetch_assoc($result);
            if(Password_verify($input_pass,$row['password'])){
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['Name'];
                // echo "<script>window.location='dashboard.php'</script>";
                header("Location: dashboard.php");
                exit();
            }else{
                echo "Wrong Password";
            }
        }else{
            echo "User Not Found";
        }
     }
    ?>
</body>
</html>