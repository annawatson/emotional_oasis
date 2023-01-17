<?php
    //setcookie("token", "", time()-3600);
    
    /*設置session*/
    session_start();
    session_destroy();

    header("Location: ./index.php");

?>