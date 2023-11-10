
<?php

    session_start();

    require "config.php";

    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\SMTP; 
    use PHPMailer\PHPMailer\Exception; 

    $mess="";

    if(isset($_POST['submit'])){
        $email=$_POST['email'];

        $s="SELECT * FROM `user` WHERE `mail` = '$email'";
        $r=$conn->query($s);
        if($r->num_rows > 0){

            $s="SELECT * FROM `user` WHERE `mail` = '$email' and `password`=''";
            $r=$conn->query($s);
            if($r->num_rows > 0){
                $mess="This mail is registered with google account try login with Google";
                // header("Location:forgot.php");
            }

            else{

                $otp=rand(0000,9999);
                $sql="update user set otp='$otp' where mail='$email'";
                // $result=$conn->query($sql);
                if($conn->query($sql) === TRUE){
                    // $_SESSION['mail']=$email;
                    setcookie('mail',$email,time()+36000,'/');

                    require 'mailer/Exception.php'; 
                    require 'mailer/PHPMailer.php'; 
                    require 'mailer/SMTP.php'; 
                        
                    $mail = new PHPMailer; 
            
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';            
                    $mail->SMTPAuth = true;
                    $mail->Username = 'makmsm2@gmail.com';
                    $mail->Password = 'ksfcstbfcdaylxyw';  
                    $mail->SMTPSecure = 'ssl';            
                    $mail->Port = 465; 
                        
                    $mail->setFrom('carrental@gmail.com', 'car rental'); 
                        
                    $mail->addAddress($email); 
                        
                    $mail->isHTML(true); 
                        
                    $mail->Subject = 'Registration For car rental system'; 
                        
                    $bodyContent = "<h2>Welcome to car rental system your otp for verification is : '$otp'</h2>"; 
                    $mail->Body    = $bodyContent; 
                    $mail->send();
                    header("Location:forgot1.php");
                }
            }

            

        }
        else{
            $mess="Invalid Email";
            // header("Location:forgot.php");
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
            
            <h3>Login here :</h3>
            <form action="" method="post" name="form1">
                <label for="email">Mail : <span>*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter Your Email Address" required>
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