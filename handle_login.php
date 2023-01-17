<?php
    /* 讀取資料庫檔案 */
    require_once('./conn.php');
    require_once('./utils.php');
	error_reporting(0);
	/* 用 $_POST 拿到input的參數 */
	$username = $_POST['username'];
    $password = $_POST['password'];
    
    /* 檢查輸入的值是不是空的 */
	/* empty(變數)：檢查變數是否為空值 */
	if (empty($username) || empty($password)){
		alertMessage('請輸入帳號密碼', './login.php');
	}else{
        //echo $hash_password;
        //exit();
        /*
	    //查詢username
        $sql = "SELECT * FROM `annwoolf_users` WHERE `username`='$username'";
        //query 可以是資料庫查詢、資料庫更新或其他動作，順利執行則回傳 true 
        //用 query 查詢 $sql 輸入資料庫的指令是否成功 
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        //拿出hash過的password
        $hash_password = $row['password'];
        */

        /*prepare statement*/
        //宣告 statement，準備好 query 
        $stmt = $conn->prepare("SELECT * from annwoolf_users where username=?");
        //放參數
        $stmt->bind_param("s", $username);
        //執行
        $result = $stmt->execute();
        //取得抓回來的資料
        $result = $stmt->get_result();

        //檢查執行結果
        if(!$result){
            echo $conn->error;
            exit();
        }

        //檢查回傳結果
        if($result->num_rows <=0){
            alertMessage('帳號密碼錯誤', './login.php');
        }
        //拿每行資料
        $row = $result->fetch_assoc();
        //資料庫被 hash 的密碼 
        $hash_password = $row['password'];

        //確認密碼是否相同        
        if (password_verify('$password', $hash_password)) {
            /*登入成功，設置cookie*/
            //setToken($conn, $username);
            
            /*設置session*/
            session_start();
            $_SESSION['username'] = $row['username'];

            /*查詢成功的話導回 ./index.php的頁面*/
            alertMessage('登入成功', './index.php');
        } else {
            alertMessage('帳號密碼錯誤', './login.php');
        }
        
    }
?>
