<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    
    <h1>WELCOME TO TRACK YOUR MOENY 💸</h1>
    <form method="POST"> 
        Name: <input type="text" name="name" required>
        Email: <input type="text" name="mail" required>
        Password: <input type="password" name="pass" required>
        <input type="submit" name="sb">
    </form>
    <?php
       $con= mysqli_connect('localhost', 'root','','users');
       if(!$con){
        die("Connection failed:".mysqli_connect_error());
       }
       if(isset($_POST['sb'])){
        $name=$_POST['name'];
        $mail=$_POST['mail'];
        $pass=password_hash($_POST['pass'],PASSWORD_DEFAULT);
        
        $check= "SELECT * FROM userdata WHERE Email='$mail'";
        $result=mysqli_query($con,$check);

        if(mysqli_num_rows($result)>0){
            echo "Email already registered!";
        }else{
       $query="INSERT INTO userdata(`Name`, `Email`, `password` )VALUES ('$name','$mail','$pass')";
       $execute=mysqli_query($con,$query);
       if($execute){
        echo "Inserted Successfully";
       }else{
        echo "Error:".mysqli_error($con);
       }
    }
}
    ?>
</body>
</html>