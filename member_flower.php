<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'member_flower');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//判断是否登录了
if (!isset($_SESSION['username']) && !isset($_SESSION['admin'])) {
    _alert_back('请先登录！');
}
//批删除花朵
if ($_GET['action'] == 'delete' && isset($_POST['ids'])) {
    $_clean = array();
    $_clean['ids'] = _mysql_string(implode(',', $_POST['ids']));
    _query("DELETE FROM b_flower WHERE f_id IN ({$_clean['ids']})");
    if (_affected_rows()) {
        _close();
        _location('花朵删除成功', 'member_flower.php');
    } else {
        _close();
        _alert_back('花朵删除失败');
    }

}
//分页模块
global $_pagesize, $_pagenum;
_page("SELECT f_id FROM b_flower WHERE f_touser='{$_SESSION['username']}' || f_touser='{$_SESSION['admin']}'", 15);   //第一个参数获取总条数，第二个参数，指定每页多少条
$sql_h="SELECT f_id,f_fromuser,f_flower,f_content,f_date 
						FROM b_flower 
						WHERE f_touser='{$_SESSION['admin']}' || f_touser='{$_SESSION['username']}'
						ORDER BY 	f_date DESC 
						 LIMIT";
/*var_dump($sql_h);
exit;*/
$_result = _query($sql_h." $_pagenum,$_pagesize");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php
    require ROOT_PATH . 'includes/title.inc.php';
    ?>
    <script type="text/javascript" src="js/member_message.js"></script>
</head>
<body>
<?php
include 'header.php';
?>

<div id="member">
    <?php
    include 'left.php'
    ?>
    <div id="member_main">
        <h2>花朵管理中心</h2>
        <form method="post" action="?action=delete">
            <table cellspacing="1">
                <tr>
                    <th>送花人</th>
                    <th>花朵数目</th>
                    <th>感言</th>
                    <th>时间</th>
                    <th>操作</th>
                </tr>
                <?php
                $_html = array();

                while (!!$_rows = _fetch_array_list($_result)) {
                    $_html['id'] = $_rows['f_id'];
                    $_html['fromuser'] = $_rows['f_fromuser'];
                    $_html['content'] = $_rows['f_content'];
                    $_html['flower'] = $_rows['f_flower'];
                    $_html['date'] = $_rows['f_date'];
                    $_html['count'] += $_html['flower'];
                    ?>
                    <tr>
                        <td><?php echo $_html['fromuser'] ?></td>
                        <td><img src="images/x4.gif" alt="花朵"/> x <?php echo $_html['flower'] ?>朵</td>
                        <td><?php echo _title($_html['content'], -1) ?></td>
                        <td><?php echo $_html['date'] ?></td>
                        <td><input name="ids[]" value="<?php echo $_html['id'] ?>" type="checkbox"/></td>
                    </tr>
                    <?php
                }
                _free_result($_result);
                ?>
                <tr>
                    <td colspan="5">共<strong><?php echo $_html['count'] ?></strong>朵花</td>
                </tr>
                <tr>
                    <td colspan="5"><label for="all">全选 <input type="checkbox" name="chkall" id="all"/></label> <input
                                type="submit" value="批删除"/></td>
                </tr>
            </table>
        </form>
        <?php _paging(2); ?>
    </div>
</div>

<?php
include "footer.php";
?>
</body>
</html>