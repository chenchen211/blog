<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'member_friend');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//判断是否登录了
if (!isset($_SESSION['username']) && !isset($_SESSION['admin'])) {
    _alert_back('请先登录！');
}
//验证好友
if ($_GET['action'] == 'check' && isset($_GET['id'])) {
        //修改表里state，从而通过验证
        _query("UPDATE b_friend SET f_state=1 WHERE f_id='{$_GET['id']}'");
        if (_affected_rows() == 1) {
            _close();
            _location('好友验证成功', 'member_friend.php');
        } else {
            _close();
            _alert_back('好友验证失败');
        }

}
//批删除好友
if ($_GET['action'] == 'delete' && isset($_POST['ids'])) {
    $_clean = array();
    $_clean['ids'] = implode(',', $_POST['ids']);
        _query("DELETE FROM b_friend WHERE f_id IN ({$_clean['ids']})" );
        if (_affected_rows()) {
            _close();
            _location('好友删除成功', 'member_friend.php');
        } else {
            _close();
            _alert_back('好友删除失败');
        }
}
//分页模块
global $_pagesize, $_pagenum;
_page("SELECT f_id FROM b_friend WHERE (f_touser='{$_SESSION['username']}' or f_touser='{$_SESSION['admin']}') ", 15);   //第一个参数获取总条数，第二个参数，指定每页多少条

$sql_f="SELECT f_id,f_state,f_touser,f_fromuser,f_content,f_date 
						 FROM b_friend 
						 WHERE (f_touser='{$_SESSION['username']}' or f_touser='{$_SESSION['admin']}'OR f_fromuser='{$_SESSION['username']}' or f_fromuser='{$_SESSION['admin']}') 
						 ORDER BY f_date DESC 
						 LIMIT";

$_result = _query($sql_f." $_pagenum,$_pagesize");
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
        <h2>好友设置中心</h2>
        <form method="post" action="?action=delete">
            <table cellspacing="1">
                <tr>
                    <th>好友</th>
                    <th>请求内容</th>
                    <th>时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?php
                $_html = array();
                while (!!$_rows = _fetch_array_list($_result)) {
                    $_html['id'] = $_rows['f_id'];
                    $_html['touser'] = $_rows['f_touser'];
                    $_html['fromuser'] = $_rows['f_fromuser'];
                    $_html['content'] = $_rows['f_content'];
                    $_html['state'] = $_rows['f_state'];
                    $_html['date'] = $_rows['f_date'];
                    if ($_html['touser'] == $_SESSION['username'] || $_html['touser'] == $_SESSION['admin']) {
                        $_html['friend'] = $_html['fromuser'];
                        if (empty($_html['state'])) {
                            $_html['state_html'] = '<a href="?action=check&id=' . $_html['id'] . '" style="color:red;">你未验证</a>';
                        } else {
                            $_html['state_html'] = '<span style="color:green;">通过</span>';
                        }
                    } elseif ($_html['fromuser'] == $_SESSION['username'] || $_html['fromuser'] == $_SESSION['admin']) {
                        $_html['friend'] = $_html['touser'];
                        if (empty($_html['state'])) {
                            $_html['state_html'] = '<span style="color:blue;">对方未验证</a>';
                        } else {
                            $_html['state_html'] = '<span style="color:green;">通过</span>';
                        }
                    }

                    ?>
                    <tr>
                        <td><?php echo $_html['friend'] ?></td>
                        <td title="<?php echo $_html['content'] ?>"><?php echo _title($_html['content'], 14) ?></td>
                        <td><?php echo $_html['date'] ?></td>
                        <td><?php echo $_html['state_html'] ?></td>
                        <td><input name="ids[]" value="<?php echo $_html['id'] ?>" type="checkbox"/></td>
                    </tr>
                    <?php
                }
                _free_result($_result);
                ?>
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