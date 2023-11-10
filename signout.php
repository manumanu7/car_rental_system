<?php

    include "config.php";
    session_start();

    if(isset($_SESSION['mail'])){
        unset($_SESSION['mail']);
        session_destroy();
        setcookie('mail','',time()-36000,'/');
        header('location: index.html');
    }

?>