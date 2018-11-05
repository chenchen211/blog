<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'member');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//是否正常登录
if ($_SESSION['username'] ||$_SESSION['admin']) {
    //获取数据
    $_rows = _fetch_array("SELECT * FROM   b_user WHERE u_username='{$_SESSION['username']}'|| u_username='{$_SESSION['admin']}' LIMIT 1");
    if ($_rows) {
    } else {
        _alert_back('此用户不存在');
    }
} else {
    _alert_back('非法登录');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php
    require ROOT_PATH . 'includes/title.inc.php';
    ?>
</head>
<body>
<?php
include 'header.php';

?>

<div id="member">
    <div id="member_sidebar">
        <h2>中心导航</h2>
        <dl>
            <dt>账号管理</dt>
            <dd><a href="member.php">个人信息</a></dd>
            <dd><a href="member_modify.php">修改资料</a></dd>
        </dl>
        <dl>
            <dt>其他管理</dt>
            <dd><a href="member_article.php">帖子查阅</a></dd>
            <dd><a href="member_message.php">短信查阅</a></dd>
            <dd><a href="member_friend.php">好友设置</a></dd>
            <dd><a href="member_flower.php">查询花朵</a></dd>
        </dl>
    </div>
    <div id="member_main">
        <h2>会员管理中心</h2>
        <dl>
            <dd>用 户 名：<?php echo $_rows['u_username'] ?></dd>
            <dd>性　　别：<?php if($_rows['u_sex']==0){echo "男";}else{ echo "女";}  ?></dd>
            <dd>头　　像：<img src="<?php echo $_rows['u_face'] ?>" alt=""></dd>
            <dd>电子邮件：<?php echo $_rows['u_email'] ?></dd>
            <dd>Q 　 　Q：<?php echo $_rows['u_qq'] ?></dd>
            <dd>注册时间：<?php echo $_rows['u_reg_time'] ?></dd>
            <dd>身　　份：<?php if($_rows['u_level']==0){echo "普通会员";}else{ echo "博客管理员";}  ?></dd>
            <dd>状　　态：<?php if($_rows['u_state']==0){echo "已启用";}else{ echo "已禁用,请联系管理员";}  ?></dd>
        </dl>
    </div>
</div>


<?php
include "footer.php";
?>
</body>
</html>
