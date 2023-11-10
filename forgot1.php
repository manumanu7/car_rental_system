
<?php
    session_start();

    require "config.php";

    $_SESSION['mail']=$_COOKIE['mail'];
    if(!isset($_COOKIE['mail'])){
        header("Location:login.php");
    }

    $mess="";

    $email=$_SESSION['mail'];
    unset($_SESSION['mail']);
    session_destroy();
    // setcookie('mail','',time()-36000,'/');

    if(isset($_POST['submit'])){
        $otp=$_POST['otp'];

        $s="SELECT * FROM `user` WHERE `mail` = '$email' and `otp`=$otp";
        $r=$conn->query($s);
        if($r->num_rows > 0){
            unset($_SESSION['mail']);
            session_destroy();
            header("Location:reset.php");
        }
        else{
            $mess="Invalid Otp";
        }
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="images/logo.png" type="image/icon type">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="types">
        <h1>Forgot Password</h1>
        <div class="registration-user" id="usert">
            
            <h2 style="color:red;margin-left:80px;margin-bottom:20px;" >OTP has sent to your mail</h2>
            <h3>Login here :</h3>
            <form action="" method="post" name="form1">
                <label for="otp">OTP : <span>*</span></label>
                <input type="number" id="otp" name="otp" placeholder="Enter OTP..." required>
                <span id="mess" style="color:red"><?php echo $mess; ?></span><br>
                <input type="submit" value="submit" name="submit">
            </form>
            
            <label>Login</label> <a href="login.php" class="already">click here</a><br>
            <br>
            <label>Don't have an account?</label> <a href="register.php" class="already">click here</a>
        </div>
    </div>
    
</body>
</html>