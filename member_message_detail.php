<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'member_message_detail');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//判断是否登录了

if (!isset($_SESSION['username']) && !isset($_SESSION['admin'])) {
    _alert_close('请先登录！');
}
//删除短信模块
if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
    //这是验证短信是否合法
    if (!!$_rows = _fetch_array("SELECT m_id FROM b_message  WHERE m_id='{$_GET['id']}' LIMIT 1")) {
            //删除单短信
            _query("DELETE FROM b_message WHERE m_id='{$_GET['id']}' LIMIT 1");
            if (_affected_rows() == 1) {
                _close();
                _location('短信删除成功', 'member_message.php');
            } else {
                _close();
                _alert_back('短信删除失败');
            }

    } else {
        _alert_back('此短信不存在！');
    }
}
//处理id
if (isset($_GET['id'])) {
    $_rows = _fetch_array("SELECT m_id,m_state,m_fromuser,m_content,m_date
								FROM b_message 
								WHERE m_id='{$_GET['id']}' 
								 LIMIT 1
										");
    if ($_rows) {
        //将它state状态设置为1即可
        if (empty($_rows['m_state'])) {
            _query("UPDATE b_message SET m_state=1 WHERE m_id='{$_GET['id']}'LIMIT 1");
            if (!_affected_rows()) {
                _alert_back('异常！');
            }
        }
        $_html = array();
        $_html['id'] = $_rows['m_id'];
        $_html['fromuser'] = $_rows['m_fromuser'];
        $_html['content'] = $_rows['m_content'];
        $_html['date'] = $_rows['m_date'];

    } else {
        _alert_back('此短信不存在！');
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
    <script type="text/javascript" src="js/member_message_detail.js"></script>
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
        <h2>短信详情</h2>
        <dl>
            <dd>发 信 人：<?php echo $_html['fromuser'] ?></dd>
            <dd>内　　容：<strong><?php echo $_html['content'] ?></strong></dd>
            <dd>发信时间：<?php echo $_html['date'] ?></dd>
            <dd class="button"><input type="button" value="返回列表" id="return"/>
                <input type="button" id="delete"  name="<?php echo $_html['id'] ?>" value="删除短信"/></dd>
        </dl>
    </div>
</div>
<?php
include "footer.php";
?>
</body>
</html>