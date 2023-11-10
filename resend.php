<?php

    session_start();

    require 'config.php';

    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\SMTP; 
    use PHPMailer\PHPMailer\Exception;


    $otp=rand(0000,9999);
    $sql="update user set otp=$otp where mail='$_SESSION[mail]'";
    // $result=$conn->query($sql);
    if($conn->query($sql)){

        $email=$_SESSION['mail'];

        // $_SESSION['mail']=$email;
        // setcookie('mail',$email,time()+36000,'/');

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
                        
        $bodyContent = "<h2>Welcome to car rental system <br> your otp for verification is : '$otp'</h2>"; 
        $mail->Body    = $bodyContent; 
        $mail->send();
        header("Location:verify.php");
    }

?>