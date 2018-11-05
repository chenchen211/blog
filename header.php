<?php
//博客头部文件
//防止恶意调用
session_start();
if (!defined('IN_TG')) {
	exit('Access Defined!');
}
global $_system;

?>
<script type="text/javascript" src="js/skin.js"></script>
<div id="header">
	<h1><a href="index.php"><?php echo $_system['webname'] ?></a></h1>
	<ul>
		<li><a href="index.php">首页</a></li>
        <?php
        if ($_SESSION['username'] || $_SESSION['admin']){
            echo '<li> <a href="MyFriends_article.php">朋友圈</a></li>';
            echo '<li> <a href="MyFriends.php">我的好友</a></li>';
           if($_SESSION['username']){
               echo '<li> '.$GLOBALS['message'].'<a href="member.php">'.$_SESSION['username'].'·个人中心</a></li>';
           }else{
               echo '<li> '.$GLOBALS['message'].'<a href="member.php">'.$_SESSION['admin'].'·个人中心</a></li>';
           }
            echo "\n";

        } else {
            echo '<li><a href="register.php">注册</a></li>';
            echo "\n";
            echo "\t\t";
            echo '<li><a href="login.php">登录</a></li>';
            echo "\n";
        }
        ?>
		<?php

			if (isset($_SESSION['username']) || isset($_SESSION['admin'])){
				echo '<li><a onclick="return confirm(\'确定退出吗？\')" href="logout.php">退出</a></li>';
			}
		?>
        <li class="skin" onmouseover='inskin()' onmouseout='outskin()'>
            <a href="javascript:;">风格</a>
            <dl id="skin">
                <dd><a href="skin.php?id=1">1.一号皮肤</a></dd>
                <dd><a href="skin.php?id=2">2.二号皮肤</a></dd>
                <dd><a href="skin.php?id=3">3.三号皮肤</a></dd>
            </dl>
        </li>
	</ul>
</div>