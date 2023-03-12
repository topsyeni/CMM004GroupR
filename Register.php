<?

if(isset($_POST['Register'])){


   $username = $_POST['username'];
   $email = $_POST['email'];
   $password = $_POST['password'];
   $Confirm_Password = $_POST['Confirm Password'];
   $con=mysqli_connect("localhost:8888","root","root","student");
   $sql="INSERT INTO Project('$username','$email','$password','$Confirm_Password')VALUES('$username','$email','$password','$Confirm_Password')";
   $result = mysqli_query($con,$sql);
   if($result)
   {
  echo "User registered successfully. Sign in mow";
   }
   else{
    echo "Data not stored";
   }
}
   

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="# Style.css">
    <title>Create an account</title>
</head>
<body style="background-color: bisque; font-family: sans-serif;">
    <div class = "container">
    <img src="Images/pexels-photo-6935078.webp" alt="Fierra" width="1500" height="800">
    <h1 style="text-align: center;">Create an account</h1>
    <p style="text-align: center; font-size: 25px; font-weight: bold;">
    <form action="Sign in.php" method="post">
    <label style="text-align: center;  font-size: 20px; text-align: center;">username</label>
    <input style="padding: 10px 20px; margin: 10px 15px;" type="text" name="username" placeholder="username" required="">
    <br>
    <br>
    <label style="text-align: center; font-size: 20px;">email</label>
    <input style="padding: 10px 20px; margin: 10px 50px;" type="email" name="email" placeholder="email" required="">
    <br>
    <br>
    <label style="text-align: center;font-size: 20px;">password</label>
    <input style="padding: 10px 20px; margin: 10px 15px;" type="password" name="password" placeholder="password" required="">
    <br>
    <br>
    <label style="text-align: center;font-size: 20px;">Confirm Password</label>
    <input style="padding: 10px 20px; margin: 10px 15px;" type="password" name="Confirm Password" placeholder="Confirm Password" required="">
    <br>
    <br>

    <button style="background-color: brown; padding: 10px 20px;cursor: pointer; font-size: larger;color: white; text-align: center;">
    Register</button>
    
    <label><input type="checkbox" checked="checked"name="remember">Remember me</label>
    <p style="text-align: center;">Already a member? <a href="Sign in.php">Sign in</a></p>
    </div>
</form>
</body>
</html>
