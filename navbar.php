<div class="warning">
    <p class="warning">本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼</p>
</div>

<!--套用bootstrap的navbar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="./index.php">留言板</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link">
            <?php /*是否為member：出現不一樣的button*/
                if (isset($user)) {  
                    $sql = "SELECT * FROM annwoolf_users WHERE username = '$user'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo "<div class='user'>您好 $row[nickname]！</div>";
                } else {
                    echo "<a href='register.php' target='_self'>會員註冊</a>";
                }
            ?> 
        <span class="sr-only"></span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link">
            <?php /*是否為member：出現不一樣的button*/
                if (isset($user)) {  
                    $sql = "SELECT * FROM annwoolf_users WHERE username = '$user'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo "<a href='logout.php' target='_self' class='nav_logout'>登出</a>";
                } else {
                    echo "<a href='login.php' target='_self'>登入</a>";
                }
            ?>
        </a>
      </li>
  </div>
</nav>



<!-- 原來的 navbar 
<nav>
    <div class="navbar-brand">
        <a href='index.php' target='_self'>留言板</a>
    </div>
    <ul class="navbar-right">
        <li class="signup">
            <?php /*是否為member：出現不一樣的button*/
                if (isset($user)) {  
                    $sql = "SELECT * FROM annwoolf_users WHERE username = '$user'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo "<div class='user'>您好 $row[nickname]！</div>";
                } else {
                    echo "<a href='register.php' target='_self'>會員註冊</a>";
                }
            ?>
        </li>
        <li class="signin">
            <?php /*是否為member：出現不一樣的button*/
                if (isset($user)) {  
                    $sql = "SELECT * FROM annwoolf_users WHERE username = '$user'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    echo "<a href='logout.php' target='_self'>登出</a>";
                } else {
                    echo "<a href='login.php' target='_self'>登入</a>";
                }
            ?>
        </li> 
    </ul>
</nav>
-->