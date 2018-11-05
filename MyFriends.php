<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'MyFriends');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//分页模块
global $_pagesize, $_pagenum, $_system;
$sql="SELECT f_id FROM b_friend where f_state =1 and (f_touser='{$_SESSION['username']}' or f_touser='{$_SESSION['admin']}' OR f_fromuser='{$_SESSION['username']}' or f_fromuser='{$_SESSION['admin']}')";

_page($sql, 10);   //第一个参数获取总条数，第二个参数，指定每页多少条
//首页要得到所有的数据总和
//从数据库里提取数据获取结果集
//我们必须是每次重新读取结果集，而不是从新去执行SQL语句

$sql_user=" SELECT * FROM b_friend where f_state =1 and (f_touser='{$_SESSION['username']}' or f_touser='{$_SESSION['admin']}' OR f_fromuser='{$_SESSION['username']}' or f_fromuser='{$_SESSION['admin']}' )ORDER BY f_date DESC LIMIT $_pagenum,$_pagesize";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php
    require ROOT_PATH . 'includes/title.inc.php';
    ?>
    <script type="text/javascript" src="js/MyFriends.js"></script>
</head>
<body>
<?php
include 'header.php';
?>

<div id="blog">
    <h2>好友列表</h2>
    <?php
    $_html = array();
    $_result = _query($sql_user);
    while (!!$_rows = _fetch_array_list($_result)) {
        if ($_rows['f_touser'] == $_SESSION['username'] || $_rows['f_touser'] == $_SESSION['admin']){
       $sql_f="select * from b_user where u_username='{$_rows['f_fromuser']}'";
       $_rows_f=_fetch_array($sql_f);

        ?>
        <dl>
            <dd class="user"><?php echo $_rows_f['u_username'] ?>(<?php if ($_rows_f['u_sex']==0){echo "男";}else{ echo "女"; }?>)</dd>
            <dt><img src="<?php echo $_rows_f['u_face'] ?>" alt="<?php echo $_rows_f['u_username'] ?>" title="<?php echo $_rows_f['u_username'] ?>"/></dt>
            <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_rows_f['u_id'] ?>">发消息</a></dd>
            <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_rows_f['u_id'] ?>">给他送花</a></dd>
        </dl>
    <?php }elseif ($_rows['f_fromuser'] == $_SESSION['username'] || $_rows['f_fromuser'] == $_SESSION['admin']){
            $sql_f="select * from b_user where u_username='{$_rows['f_touser']}'";
            $_rows_f=_fetch_array($sql_f);
            ?>
            <dl>
                <dd class="user"><?php echo $_rows_f['u_username'] ?>(<?php if ($_rows_f['u_sex']==0){echo "男";}else{ echo "女"; }?>)</dd>
                <dt><img src="<?php echo $_rows_f['u_face'] ?>" alt="<?php echo $_rows_f['u_username'] ?>" title="<?php echo $_rows_f['u_username'] ?>"/></dt>
                <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_rows_f['u_id'] ?>">发消息</a></dd>
                <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_rows_f['u_id'] ?>">给他送花</a></dd>
            </dl>
        <?php }
    }?>
<?php
    _free_result($_result);
    //_pageing函数调用分页，1|2，1表示数字分页，2表示文本分页
    _paging(1);
?>


</div>

<?php
include "footer.php";
?>
</body>
</html>
