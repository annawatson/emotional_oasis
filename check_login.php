<?php
    include_once('./conn.php');
    include_once('./utils.php');

    //check login 讀取 cookie  裡面的 cookie 
    //if(isset($_COOKIE['token']) && !empty($_COOKIE['token'])){
    //    $token = $_COOKIE['token'];
    //} else {
    //    $token = null;
    //}

    //由 token 找 username 
    //$user = getUserByToken($conn, $token);

    //check session 值 ＆ 賦值給 $user
    session_start();
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        $user = $_SESSION['username'];
    } else {
        $user = null;
    }


    $nickname = getNickname($conn, $user);
?>