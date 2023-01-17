<?php
    require_once('conn.php');
    
    //印出訊息 function 
    function alertMessage($message, $location){
        echo 
        "<script> 
            alert('$message');
            window.location = '$location';
        </script>";
    }

    //setToken function 
    function setToken($conn, $username){
        /*新增token*/
        $token = uniqid();
        // 如果之前已經有登入取得 token，要先刪除
        //$sql = "DELETE FROM annwoolf_users_certificate WHERE username ='$username'";
        //$result = $conn->query($sql);

        /*prepare statement*/
        $stmt = $conn->prepare("DELETE FROM annwoolf_users_certificate WHERE username = ? ");
        $stmt->bind_param("s", $username);
        $result = $stmt->execute();

        /*新增進token&username表單*/
        //$sql= "INSERT INTO annwoolf_users_certificate(username, token) VALUES('$username', '$token')";
        /*連線*/
        //$result = $conn->query($sql);
        
        /*prepare statement*/
        $stmt = $conn->prepare("INSERT INTO annwoolf_users_certificate(username, token) VALUES(?, ?)");
        $stmt->bind_param("ss", $username, $token);
        $result = $stmt->execute();
        
        /*建立cookie：cookie名稱token，cookie內容：$token*/
        setcookie("token", $token, time()+3600*24);
    }

    //用 token 找 username function 
    function getUserByToken($conn, $token){
    //驗證是否設定 token、驗證 token 是否為空值
        if(isset($token) && !empty($token)){
            /*如果不是空值：驗證token是否和資料庫token一樣*/
            //sql查詢token、對比token是否相同
            //如果相同就回傳 $row['username']
            /*取值*/
            //$sql= "SELECT * FROM annwoolf_users_certificate 
            //WHERE token='$token'";
            /*連線*/
            //$result = $conn->query($sql);
            /*確認有回傳值*/
            /*prepare statement*/
            $stmt = $conn->prepare("SELECT * FROM annwoolf_users_certificate WHERE token= ? ");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                /*拿取回傳值&賦值給$row*/
                $row = $result->fetch_assoc();
                /*取得值裡面的username*/
                return $row['username'];
            } else {
                return null;
            }
        }else {
            //如果是空值回傳 null
            return null;
        }
    }

    //刪除按鈕 改ajax
    function renderDeleteBtn($id){
        return "
        <div class='delete-comment'>
            <button class='btn-delete' data-id='$id'>刪除</button>
        </div>
        ";
    }
    
    //編輯按鈕
    function renderEditBtn($id){
        return "
        <div class='edit-comment'>
            <form method='GET' action='./update.php'>
                <input type='hidden' name='id' value='$id' />
                <input type='submit' value='修改' />
            </form>
        </div>
        ";
    }
    //跳脫
    function escape($str){
        return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
    }

    //getNicknameByUser
    function getNickname($conn, $user) {
        $sql = "SELECT * FROM annwoolf_users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['nickname'];
        } else {
            return null;
        }
    } 
?>