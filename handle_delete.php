<?php
    include_once('check_login.php');
    require_once('conn.php');
    require_once('utils.php');
    
    if (
        isset($_POST['id']) &&
        !empty($_POST['id'])
        ){
        
        $id = $_POST['id'];
   
        //$sql = "DELETE FROM annwoolf_comments WHERE id = $id or parent_id = $id";
        //$result = $conn->query($sql);

        $stmt = $conn->prepare("DELETE FROM annwoolf_comments WHERE id = ? or parent_id = ?");
        $stmt->bind_param("ss", $id, $id);
        $result = $stmt->execute();

        if ($result){
            //如果成功的話不要轉址 
            //echo "success";
            //header('Location: ./index.php');
            echo json_encode(array(
                'result' => 'success',
                'message' => '刪除成功'
            ));
        } else {
            //echo "fail";
            //alertMessage('錯誤NO', './index.php');
            echo json_encode(array(
                'result' => 'failure',
                'message' => '刪除失敗'
            ));
        }    
    } else {
        //echo "fail";
        //alertMessage('錯誤', './index.php');
        echo json_encode(array(
            'result' => 'failure',
            'message' => '資料是空的，刪除失敗'
        ));
    }
?>