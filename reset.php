
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
    setcookie('mail','',time()-36000,'/');

    if(isset($_POST['submit'])){
        $password=$_POST['password'];

        $sql="update user set password='$password' ,otp='' where mail='$email'";
		if($conn->query($sql)){
            header("Location:login.php");
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
        <h1>Reset Password</h1>
        <div class="registration-user" id="usert">
            
            <h3>Reset Password :</h3>
            <form action="" method="post" name="form1">

                <label for="password">Password <span>*</span></label>  
                <input type="password" name="password" id="password" placeholder="Create a Password" required onkeyup="checkpassword()" onfocusout="checkpasswordvalid()"><br>
                <label for="conform">Conform Password <span>*</span></label> 
                <input type="password" name="conform" id="conform" placeholder="Re-Enter the Password" onkeyup="checkupass()" onfocusout="checkupassmatch()" required>
                <span id="mess" style="color:red"><?php echo $mess; ?></span><br>
                <input type="submit" value="submit" name="submit">
                <span class="or">OR</span>
            </form>
            
            <label>Don't have an account?</label> <a href="register.php" class="already">click here</a>
        </div>
    </div>


    <script>
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