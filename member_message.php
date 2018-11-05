<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'member_message');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//判断是否登录了

if (!isset($_SESSION['username']) && !isset($_SESSION['admin'])) {
    _alert_close('请先登录！');
}
//批删除短信
if ($_GET['action'] == 'delete' && isset($_POST['ids'])) {
    $_clean = array();
    $_clean['ids'] = implode(',', $_POST['ids']);

    _query("DELETE FROM b_message WHERE  m_id  IN ({$_clean['ids']})");
    if (_affected_rows()) {
        _close();
        _location('短信删除成功', 'member_message.php');
    } else {
        _close();
        _alert_back('短信删除失败');
    }

}
//分页模块
global $_pagesize, $_pagenum;
_page("SELECT m_id FROM b_message WHERE m_touser='{$_SESSION['username']}' OR m_touser='{$_SESSION['admin']}'", 15);   //第一个参数获取总条数，第二个参数，指定每页多少条
$_result = _query("SELECT m_id,m_state,m_fromuser,m_content,m_date 
                         FROM b_message 
                         WHERE m_touser='{$_SESSION['username']}' OR m_touser='{$_SESSION['admin']}'
                        ORDER BY m_date DESC 
                        LIMIT   $_pagenum,$_pagesize
							");
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
        <h2>短信管理中心</h2>
        <form method="post" action="?action=delete">
            <table cellspacing="1">
                <tr>
                    <th>发信人</th>
                    <th>短信内容</th>
                    <th>时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                <?php
                $_html = array();
                while (!!$_rows = _fetch_array_list($_result)) {
                    $_html['id'] = $_rows['m_id'];
                    $_html['fromuser'] = $_rows['m_fromuser'];
                    $_html['content'] = $_rows['m_content'];
                    $_html['date'] = $_rows['m_date'];

                    if (empty($_rows['m_state'])) {
                        $_html['state'] = '<img src="images/read.gif" alt="未读" title="未读" />';
                        $_html['content_html'] = '<strong>' . _title($_html['content'], 14) . '</strong>';
                    } else {
                        $_html['state'] = '<img src="images/noread.gif" alt="已读 title="已读" />';
                        $_html['content_html'] = _title($_html['content'], 14);
                    }

                    ?>
                    <tr>
                        <td><?php echo $_html['fromuser'] ?></td>
                        <td><a href="member_message_detail.php?id=<?php echo $_html['id'] ?>"
                               title="<?php echo $_html['content'] ?>"><?php echo $_html['content_html'] ?></a></td>
                        <td><?php echo $_html['date'] ?></td>
                        <td><?php echo $_html['state'] ?></td>
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