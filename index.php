<?php 
    require_once('./conn.php'); 
    require_once('./utils.php');
    require_once('./check_login.php');
    error_reporting(0)
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Emotional Oasis MessageBoard</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="./src.js"></script>
    </head>
    <body>
        <?php include_once('./navbar.php') ?>
        <div class="wrapper">
                <div class="welcome">
                    <?php /*是否為member：出現不一樣的button*/
                        if (isset($user)) {  
                            //$sql = "SELECT * FROM annwoolf_users WHERE username = '$user'";
                            //$result = $conn->query($sql);
                            //$row = $result->fetch_assoc();
                            /*prepare statement*/
                            $stmt = $conn->prepare("SELECT * FROM annwoolf_users WHERE username = ? ");
                            $stmt->bind_param("s", $user);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            echo "<p>您好 $row[nickname] 歡迎留言！</p>";
                        } else {
                            echo "<p>歡迎留言！</p>";
                        }
                    ?>
                </div>
                <div class="leave-comment-box">
                    <form action="handle_add_comments.php" method="post"> <!--POST傳參數-->
                     <input type='hidden' value="0" name="parent_id" class="parent_id" />
                     <textarea name="content" placeholder="留言在這邊吧！" class="content"></textarea>
                        <?php /*是否為member：出現不一樣的button*/
                            if (isset($user)) {  
                                echo "<div class='submit-btn'>" . "<button class='btn_submit'>送出</button>" . "</div>";
                            } else {
                                echo "<div class='sign_in_first'>欲留言請先登入或成為會員</div>";
                            }
                        ?>
                    </form>
                </div>
                <!--分頁功能-->
                <?php  
                    //資料庫連線，選取表格內所有資料，並算出全部有幾筆
                    $sql = "SELECT * FROM annwoolf_comments ORDER BY created_at DESC";
                    $result = $conn->query($sql);
                    $data_nums = $result->num_rows; //共有幾個留言

                    //設定分頁參數
                    $page_limit = 10; //每頁數量20個留言
                    $pages = ceil($data_nums/$page_limit); //有幾頁（ceil整數無條件進位）

                    //過濾手續 
                    if (!isset($_GET['page'])){ 
                        $pageIndex=1; //設定起始頁 
                    } else {
                        $pageIndex = intval($_GET['page']); //確認頁數只能夠是數值資料
                    }

                    //每頁起始資料序號 
                    $data_start = ($pageIndex-1)*$page_limit;  

                    //取得資料，顯示在畫面上：起始序號、每頁顯示多少筆資料
                    //$sql查詢語法//
                    /*$sql = "SELECT C.content, C.created_at, C.id, U.nickname, U.username  
                    FROM annwoolf_comments C LEFT JOIN annwoolf_users U 
                    ON C.username = U.username 
                    WHERE C.parent_id = 0
                    ORDER BY created_at DESC 
                    LIMIT $data_start, $page_limit";
                    //連線執行$sql//
                    $result = $conn->query($sql);
                    */
                    /*prepare statement*/
                    $sql = "SELECT C.content, C.created_at, C.id, U.nickname, U.username  
                    FROM annwoolf_comments C LEFT JOIN annwoolf_users U 
                    ON C.username = U.username 
                    WHERE C.parent_id = 0
                    ORDER BY created_at DESC 
                    LIMIT ?, ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $data_start, $page_limit);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    //檢查連線//
                    if (!$result) {
                        echo "failed, " . $conn->error;
                    };
                ?>

                <div class="comments">
                    <!--留言功能-->
                    <!--抓資料-->
                    <?php
                     if ($result) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                    <div class='comment-box'>
                            <!--主留言-->
                            <div class='main-comment-box'>
                                <div class='nickname'><?= escape($row['nickname']) ?></div>
                                <div class='time'><?= $row['created_at'] ?></div>
                                <div class='comment'><?= escape($row['content']) ?></div>
                                <?php
                                    if ($user === $row['username']) {
                                        echo renderEditBtn($row['id']);
                                        echo renderDeleteBtn($row['id']);
                                    }
                                ?>
                            </div>
                            <!--子留言-->
                            <?php
                                $parent_id = $row['id'];
                                /*
                                $sql_sub = "SELECT C.id, C.username, C.content, C.created_at, U.nickname
                                    FROM annwoolf_comments as C 
                                    LEFT JOIN annwoolf_users as U 
                                    ON C.username = U.username 
                                    WHERE C.parent_id = $parent_id
                                    ORDER BY C.id ASC
                                ";
                                $result_sub = $conn->query($sql_sub);
                                */
                                
                                /*prepare statement*/
                                $sql_sub = "SELECT C.id, C.username, C.content, C.created_at, U.nickname
                                    FROM annwoolf_comments as C 
                                    LEFT JOIN annwoolf_users as U 
                                    ON C.username = U.username 
                                    WHERE C.parent_id = ?
                                    ORDER BY C.id ASC
                                ";
                                $stmt = $conn->prepare($sql_sub);
                                $stmt->bind_param("s", $parent_id);
                                $stmt->execute();
                                $result_sub = $stmt->get_result();

                                if($result_sub){
                                    while($row_sub = $result_sub->fetch_assoc()) {                 
                            ?>
                            <?php /*同user，同底色*/
                                if ($row_sub['nickname'] === $row['nickname']) {  
                                    echo "<div class='sub-comment-box-color'>";
                                } else {
                                    echo "<div class='sub-comment-box'>";
                                }
                            ?>          
                            <!--<div class='sub-comment-box'>-->
                                <div class='nickname'><?= escape($row_sub['nickname']) ?></div>
                                <div class='time'><?= $row_sub['created_at'] ?></div>
                                <div class='comment'><?= escape($row_sub['content']) ?></div>
                                <?php
                                    if ($user === $row_sub['username']) {
                                        echo renderEditBtn($row_sub['id']);
                                        echo renderDeleteBtn($row_sub['id']);
                                    }
                                ?>
                            </div>

                            <?php
                                    }
                                }
                            ?>
                            <!--新增留言-->        
                            <div class='add-sub-comment-box'>
                                <?php
                                    /*old sql
                                    $sql_add = "SELECT * FROM annwoolf_users WHERE username = '$user'";
                                    $result_add = $conn->query($sql_add);
                                    $row_add = $result_add->fetch_assoc();
                                    */

                                    /*prepare statement*/
                                    $sql_add = "SELECT * FROM annwoolf_users WHERE username = ? ";
                                    $stmt = $conn->prepare($sql_add);
                                    $stmt->bind_param("s", $user);
                                    $stmt->execute();
                                    $result_add = $stmt->get_result();
                                    $row_add = $result_add->fetch_assoc();

                                    echo "<div class='nickname'>$row_add[nickname]</div>";
                                ?>
                                    <div class='sub-textarea leave-comment-box'> 
                                        <form action="handle_add_comments.php" method="post"> 
                                            <input type='hidden' value="<?php echo $parent_id; ?>" name="parent_id" />
                                            <textarea name="content" placeholder="回覆留言吧！" rows="10" cols="50"></textarea>
                                            <?php /*是否為member：出現不一樣的button*/
                                                if (isset($user)) {  
                                                    echo "<div class='submit-btn'>" . "<button class='btn_submit'>送出</button>" . "</div>";
                                                } else {
                                                    echo "<div class='sign_in_first'>欲留言請先登入或成為會員</div>";
                                                }
                                            ?>
                                        </form>
                                    </div>
                            </div>
                            
                        </div>
                <?php
                        }
                    }
                ?>   
                </div> 
                <!--分頁頁碼-->
                <div class="pages">
                <?php 
                    //分頁
                    echo "<div class='page_select'>";
                    echo "<a href='?page=1'>第一頁 </a>";
                    //中間頁碼
                    for($i=1 ; $i <= $pages ; $i++){
                        echo "<a href='?page= $i' > $i </a>"; //為什麼知道是index.php?page=1
                    };
                    echo "<a href='?page= $pages '>最後頁  </a>";
                    echo "</div>";
                    //第幾頁
                    echo "<div class='page_show'>";
                    echo "共 $data_nums 筆留言 - 在第 $pageIndex 頁 - 共 $pages 頁";
                    echo "</div>";
                ?>
                </div>
            </div>
        </div>
    </body>
</html>
