
<?php

    session_start();

    require 'config.php';

    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\SMTP; 
    use PHPMailer\PHPMailer\Exception; 

    $mess="";


    if(isset($_POST['submit'])){
        $otp=$_POST['otp'];
        $email=$_SESSION['mail'];
        $sql1="select * from user where mail='$email'";
        $result1=$conn->query($sql1);
        if($result1->num_rows > 0){
            while($row=$result1->fetch_assoc()){
                $o=$row['otp'];
            }
            if($o===$otp){
                $sql="update user set otp='0',status='True' where mail='$email'";
                if($conn->query($sql)){
                    header("Location:login.html");
                }
            }
            else{
                $mess="Invalid OTP";
            }
        }
        else{
            echo "Invalid ";
        }
    }


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <link rel="stylesheet" href="css/verification.css">
</head>
<body>
    
    <div class="types">
        <h1>Verify Your Mail</h1>
        <div class="registration-user" id="usert">
            
            <h3>OTP has sent to your mail :</h3>
            <form action="" method="post" name="form1">
                <label for="otp">Enter Your OTP : <span>*</span></label>
                <input type="number" id="otp" name="otp" placeholder="Enter OTP" required>
                <span id="mess" style="color:red"><?php echo $mess; ?></span><br>
                <a href="resend.php" class="resend">Resend OTP</a>
                <input type="submit" value="submit" name="submit">
            </form>
        </div>
    </div>


</body>
</html>