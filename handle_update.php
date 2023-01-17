<?php
    require_once('./conn.php');
    
    $id = $_POST['id'];
    $content = $_POST['content'];

    //$sql = "UPDATE annwoolf_comments SET content='$content' WHERE id = '$id' ";

    $stmt = $conn->prepare("UPDATE annwoolf_comments SET content=? WHERE id =? ");
    $stmt->bind_param("ss", $content, $id);
    $result = $stmt->execute();
        
    if ($result){
        header('Location: ./index.php');
    } else {
        echo "failed: " .$conn->error; 
    }
        
?>