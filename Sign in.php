<?php

if(isset($_POST['submit'])){


   
   $username = $_POST['username'];
   $password = $_POST['password'];
   $con=mysqli_connect("localhost:8888","root","root","Project");
   $sql = "SELECT * from users WHERE username = '$username' AND password = '$password'"; 
   $result=mysqli_query($con,$sql);
   $resultcheck=mysqli_num_rows($result);
   if($resultcheck==1)
   {
    header('location:Register.php');
    //echo "Signin successful";//
   }
   else{
     echo "Signin not successful";
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
</head>

<body style="background-color: none; font-family: sans-serif; ">

    <div class = "container">
        <img src="Images/images.jpeg" alt="Fierra" width="1500" height="800">
     <h1 style="text-align: center;">Sign in</h1>
     <p style="text-align: center; font-size: 25px; font-weight: bold;">
    <form action="Home.php" method="post">
    <label style="text-align: center; font-size: 20px;">username</label>
    <input style="padding: 10px 20px; margin: 10px 50px;" type="text" name="username"  required="">
    <br>
    <br>

    <label style="text-align: center;font-size: 20px;">password</label>
    <input style="padding: 10px 20px; margin: 10px 15px;" type="password" name="password"  required="">
    <br>
    <br>
    <button style="background-color: brown; padding: 10px 20px;cursor: pointer; font-size: larger;color: white; text-align: center;">Submit</button>
    <p style="text-align: center;">New to Fierra Solutions? <a href="Register.php">create an account</a></p>
</div>
</form>
</body>
</html>



