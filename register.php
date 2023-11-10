
<?php
    session_start();

    require 'config.php';

    require 'google-api/vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\SMTP; 
    use PHPMailer\PHPMailer\Exception; 

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

    if(isset($_POST['register'])){
        $name=$_POST['name'];
        $email=$_POST['mail'];
        $phone=$_POST['number'];
        $password=$_POST['password'];

        $s="SELECT * FROM `user` WHERE `mail` = '$email'";
        $r=$conn->query($s);
        if($r->num_rows > 0){
            $mess="User already Exist please try login";
        }
        else{
            $otp=rand(0000,9999);
            $sql="insert into user values ('$name','$email','$phone','$password','$otp','False','profile/default')";
            if($conn->query($sql) === TRUE){

                $_SESSION['mail']=$email;
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
                        
                $bodyContent = "<h1>Hello $full_name</h1>"; 
                $bodyContent .= "<h2>Welcome to car rental system your otp for verification is : '$otp'</h2>"; 
                $mail->Body    = $bodyContent; 
                $mail->send();
                header("Location:verify.php");
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
    $client->setRedirectUri('http://localhost/carrental/register.php');

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
            $id = $google_account_info->id;
            $full_name =  trim($google_account_info->name);
            $email =  $google_account_info->email;
            $profile_pic =  $google_account_info->picture;

            $s1="SELECT * FROM `user` WHERE `mail` = '$email'";
            $r1=$conn->query($s1);
            if($r1->num_rows > 0){
                $mess="User already Exist please try login";
            }
            else{
                $otp=rand(1000,9999);
                $sql="INSERT INTO `user` VALUES ('$full_name','$email','','','$otp','False','$profile_pic')";
                // $result=$conn->query($sql);
                if($conn->query($sql) === TRUE){

                    $_SESSION['mail']=$email;
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
                    
                    $bodyContent = "<h1>Hello $full_name</h1>"; 
                    $bodyContent .= "<h2>Welcome to car rental system your otp for verification is : '$otp'</h2>"; 
                    $mail->Body    = $bodyContent; 
                    $mail->send();
                    header("Location:verify.php");
                }
                else{
                    echo "error in registration";
                }
            }

        }
    }


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="icon" href="images/logo.png" type="image/icon type">
    <link rel="stylesheet" href="css/registration.css">
</head>
<body>
    

    <div class="types">
        <h1>Registration Page</h1>
        <div class="registration-user" id="usert">
            
            <h3>User Registration :</h3>
            <form action="" method="post" name="form1">
                <label for="name">Name <span>*</span></label>  <input type="text" name="name" id="name" placeholder="Enter Your Name" required onkeyup="checkname()" onfocusout="checknamevalid()"><br>
                <label for="mail">Mail Address <span>*</span></label>  <input type="email" name="mail" id="mail" placeholder="Enter your Email Address" required> <br>
                <label for="number">phone Number<span>*</span></label>  <input type="number" name="number" id="number" placeholder="Enter Your mobile number" required onkeyup="checknumber()" onfocusout="checknumbervalid()"> <br>
                <label for="password">Password <span>*</span></label>  <input type="password" name="password" id="password" placeholder="Create a Password" required onkeyup="checkpassword()" onfocusout="checkpasswordvalid()"><br>
                <label for="conform">Conform Password <span>*</span></label>  <input type="password" name="conform" id="conform" placeholder="Re-Enter the Password" onkeyup="checkupass()" onfocusout="checkupassmatch()" required><br><br>
                <input type="checkbox" name="declaration" id="declaration" value="True" class="declaration" required><label for="declaration" class="dec">I accept the <a href="">Terms and conditions</a> and <a href="">Private policy</a> mentioned.</label>
                <span id="mess" style="color:red"><?php echo $mess; ?></span><br>
                <span class="note">Note : * marks are required to fill.</span>
                <input type="submit" value="Register" name="register">
                <span class="or">OR</span>
            </form>
            <button class="google">
                <a class="login-with-google-btn btn" href="<?php echo $client->createAuthUrl(); ?>">Sign Up with Google</a>
            </button><br>
            <label>Already have an account?</label> <a href="login.php" class="already">click here</a>
        </div>
    </div>

    <script>

        function checkname(){
            var name=document.getElementById("name").value;
            if(name.length<3){
                document.getElementById("mess").innerHTML="Name should contain atleaste 3 characters";
            }else
            document.getElementById("mess").innerHTML="";
        }
        function checknamevalid(){
            var name=document.getElementById("name").value;
            if(name.length<3){
                document.getElementById("mess").innerHTML="";
                document.getElementById("name").value="";
            }else{
                document.getElementById("mess").innerHTML="";
            }
        }

        function checknumber(){
            var number=document.getElementById("number").value;
            if(number.length<10){
                document.getElementById("mess").innerHTML="Phone Number should contain 10 digits";
            }else if(number.length>10){
                document.getElementById("mess").innerHTML="Phone Number should contain 10 digits only";
            }else
            document.getElementById("mess").innerHTML="";
        }
        
        function checknumbervalid(){
            var number=document.getElementById("number").value;
            if(number.length!=10){
                document.getElementById("mess").innerHTML="";
                document.getElementById("number").value="";
            }else{
                document.getElementById("mess").innerHTML="";
            }
        }

        function checkpassword(){
            var password=document.getElementById("password").value;
            if(!password.match(/[a-z]/) || !password.match(/[A-Z]/) || !password.match(/[0-9]/ || !password.match(/[.!@#$%^&*()_?]/))){
                document.getElementById("mess").innerHTML="password should contains atleast 1 capital, 1 small characters and a special character with atleast 8 characters";
            }
            else{
                document.getElementById("mess").innerHTML="";
            }
        }

        function checkpasswordvalid(){
            var password=document.getElementById("password").value;
            if(!password.match(/[a-z]/) || !password.match(/[A-Z]/) || !password.match(/[0-9]/ || !password.match(/[.!@#$%^&*()_?]/))){
                document.getElementById("mess").innerHTML="";
                document.getElementById("password").value="";
            }
            else{
                document.getElementById("mess").innerHTML="";
            }
        }

        function checkupass(){
            var upass=document.getElementById("password").value;
            var uconform=document.getElementById("conform").value;
            if(upass!==uconform){
                var mess=document.getElementById("mess");
                mess.innerHTML="Password Doesnot Match";
                mess.style.color="red";
            }
            else{
                var mess=document.getElementById("mess");
                mess.innerHTML="Password Matchs";
                mess.style.color="green";
            }
        }
        
        function checkupassmatch(){
            var upass=document.getElementById("password").value;
            var uconform=document.getElementById("conform").value;
            if(upass!==uconform){
                var mess=document.getElementById("mess");
                mess.innerHTML="Password Doesnot Match";
                mess.style.color="red";
                document.getElementById("conform").value="";
            }
            else{
                var mess=document.getElementById("mess");
                mess.innerHTML="Password Matchs";
                mess.style.color="green";
            }
        }

    </script>



</body>
</html>