<?php

    $host="localhost";
    $user_name="root";
    $user_password="";
    $data_base="carrental";

    $conn=mysqli_connect($host,$user_name,$user_password,$data_base);

    if(!$conn){
        die("Unable to connect with Data base");
    }

?>