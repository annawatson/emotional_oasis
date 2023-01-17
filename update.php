<?php 
    require_once('./conn.php'); 
    require_once('./utils.php');
    require_once('./check_login.php');
    error_reporting(0);
?>

<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>安安您好留言版</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </head>
    <body>
            <?php include_once('./navbar.php') ?>
            <div class="wrapper">
                <div class="welcome">
                    <p> 編輯留言 </p>
                </div>
                <div class="leave-comment-box">
                    <form action="./handle_update.php" method="POST"> <!--POST傳參數-->
                     <textarea name="content"><?php 
                    $id = $_GET['id'];

                    //$sql = "SELECT * FROM annwoolf_comments WHERE id = '$id' ";
                    //$result = $conn->query($sql);
                    //$row = $result->fetch_assoc();
                    
                    /*prepare statement*/
                    $stmt = $conn->prepare("SELECT content FROM annwoolf_comments WHERE id = ? ");
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    echo $row[content]?></textarea>
                    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                    <div class='submit-btn'><button class='btn_submit'>送出</button></div>
                    <!--<input type='submit' value='送出' class='submit-btn'>-->
                </form>
                </div>
            </div>
        </div>
    </body>
</html>


