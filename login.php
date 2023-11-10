
<?php

    session_start();

    require "config.php";

    require 'google-api/vendor/autoload.php';

    $mess="";

    if(isset($_SESSION['mail'])){
        $m=$_SESSION['mail'];
        $s2="select * from user where mail='$m' and status=='False'";
        $r2=$conn->query($s2);
        if($r2->num_rows > 0){
            header("Location:verificaion.html");
        }
        else{
            header("Location:loggedin.html");
        }
    }

    if(isset($_POST['submit'])){
        $email=$_POST['email'];
        $password=$_POST['password'];

        $s="SELECT * FROM `user` WHERE `mail` = '$email' and `password` = '$password'";
        $r=$conn->query($s);
        if($r->num_rows > 0){
            while($row=$r->fetch_assoc()){
                $status=$row['status'];
            }
            if($status=="False"){
                $_SESSION['mail']=$email;
                setcookie('mail',$email,time()+36000,'/');
                header("Location:verify.php");
            }
            else{
                $_SESSION['mail']=$email;
                setcookie('mail',$email,time()+36000,'/');
                header("Location:logedin.html");
            }
        }
        else{
            $s="SELECT * FROM `user` WHERE `mail` = '$email' and `password`=''";
            $r=$conn->query($s);
            if($r->num_rows > 0){
                $mess="This mail is registered with google account try login with Google";
                // header("Location:forgot.php");
            }
            else{
                $mess="Invalid Email or Password";
            }
        }
    }


    // Creating new google client instance
    $client = new Google_Client();

    // Enter your Client ID
    $client->setClientId('507669100340-7i0t50fhuk03stljv0pt40c4o0l6hcjo.apps.googleusercontent.com');
    // Enter your Client Secrect
    $client->setClientSecret('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');
    // Enter the Redirect URL
    $client->setRedirectUri('http://localhost/carrental/login.php');

    // Adding those scopes which we want to get (email & profile Information)
    $client->addScope("email");
    $client->addScope("profile");


    if(isset($_GET['code'])){

        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        if(!isset($token["error"])){

            $client->setAccessToken($token['access_token']);

            // getting profile information
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
        
            // Storing data into database
            // $id = $google_account_info->id;
            // $full_name =  trim($google_account_info->name);
            $email =  $google_account_info->email;
            // $profile_pic =  $google_account_info->picture;

            $s1="SELECT * FROM `user` WHERE `mail` = '$email'";
            $r1=$conn->query($s1);
            if($r1->num_rows > 0){
                while($row=$r1->fetch_assoc()){
                    $status=$row['status'];
                }
                if($status=="False"){
                    $_SESSION['mail']=$email;
                    setcookie('mail',$email,time()+36000,'/');
                    header("Location:verify.php");
                }
                else{
                    $_SESSION['mail']=$email;
                    setcookie('mail',$email,time()+36000,'/');
                    header("Location:logedin.html");
                }
            }
            else{
                $mess="User Does not Exists";
            }

        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="images/logo.png" type="image/icon type">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="types">
        <h1>Login Page</h1>
        <div class="registration-user" id="usert">
            
            <h3>Login here :</h3>
            <form action="" method="post" name="form1">
                <label for="email">Mail : <span>*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter Your Email Address" required>
                <label for="">password : <span>*</span></label>
                <input type="password" id="password" name="password" placeholder="Enter Your Email Address" required>
                <span id="mess" style="color:red"><?php echo $mess; ?></span><br>
                <input type="submit" value="submit" name="submit">
                <span class="or">OR</span>
            </form>
            
            <button class="google">
                <a class="login-with-google-btn btn" href="<?php echo $client->createAuthUrl(); ?>">Sign in with Google</a>
            </button><br>
            <label>Forget Password?</label> <a href="forgot.php" class="already">click here</a><br>
            <br>
            <label>Don't have an account?</label> <a href="register.php" class="already">click here</a>
        </div>
    </div>
    
</body>
</html>