<?php
    /* 讀取資料庫檔案 */
	require_once('./conn.php');
    require_once('./utils.php');
    require_once('./check_login.php');
	error_reporting(0);
    /* 用 $_POST 拿到input的參數 */
	$content = $_POST['content'];
	$parent_id = $_POST['parent_id'];
	$username = $user;

    /* 檢查輸入的值是不是空的 */
	/* empty(變數)：檢查變數是否為空值 */
	//if (empty($content)){
		//die('請輸入資料');

	//}
	if(isset($content) && !empty($content)){
		//把資料寫入php資料庫 
		//$sql = "INSERT INTO `annwoolf_comments`(username, content, parent_id) VALUE('$username', '$content', $parent_id)";
		//query 可以是資料庫查詢、資料庫更新或其他動作，順利執行則回傳 true
		//用 query 查詢 $sql 輸入資料庫的指令是否成功 
		//$result = $conn->query($sql);
		
		$stmt = $conn->prepare("INSERT INTO `annwoolf_comments`(username, content, parent_id) VALUE(?, ?, ?)");
		$stmt->bind_param("sss", $username, $content, $parent_id);
		$result = $stmt->execute();

		if ($result) {
			/*add 成功的話導回 ./index.php的頁面*/
			//header('Location:./index.php'); 
			//成功的話拿資料
			$last_id = $stmt->insert_id;
			$stmt = $conn->prepare("SELECT * FROM annwoolf_comments WHERE id = ?");
			$stmt->bind_param("i", $last_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$time = $row['created_at'];
            echo json_encode(array(
                'result' => 'success',
				'message' => '新增成功',
				'id' => $last_id, 
				'nickname' => $nickname,
				'time' => $time,
            ));
		} else {
			//echo "failed, " . $conn->error;
            echo json_encode(array(
                'result' => 'failure',
                'message' => '新增失敗'
            ));
		}
	}else{
		echo json_encode(array(
            'result' => 'failure',
            'message' => '請輸入文字'
        ));
	}
?>