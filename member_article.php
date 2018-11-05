<?php
/**
 * 会员帖子文件
 */
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'member');

//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//是否正常登录
if (isset($_SESSION['username']) || isset($_SESSION['admin'])) {
    //批删除帖子
    if ($_GET['action'] == 'delete' && isset($_POST['ids'])) {
        $_clean = array();
        $_clean['ids'] = _mysql_string(implode(',', $_POST['ids']));
        //危险操作，为了防止cookies伪造，还要比对一下唯一标识符uniqid()
        if (!!$_rows = _fetch_array("SELECT 
																tg_uniqid 
													FROM 
																tg_user 
												 WHERE 
																tg_username='{$_COOKIE['username']}' 
													 LIMIT 
																1"
        )) {
            _uniqid($_rows['tg_uniqid'], $_COOKIE['uniqid']);
            _query("DELETE FROM 
												tg_article 
								  WHERE 
												tg_id 
											IN 
												({$_clean['ids']})"
            );
            if (_affected_rows()) {
                _close();
                _location('帖子删除成功', 'member_article.php');
            } else {
                _close();
                _alert_back('帖子删除失败');
            }
        }
    }
    //读取帖子列表
    global $_pagesize, $_pagenum;
    $keywords = $_POST['search'];
    $jiami = $_POST['jiami'];
    $sql = "SELECT a_id,a_title,a_reid,a_type,a_readcount,a_commendcount,a_check FROM b_article WHERE  a_reid=0 and (a_username='{$_SESSION['username']}' or a_username='{$_SESSION['admin']}' )";

    if (!empty($keywords)) {
        $sql .= " and a_title like '%" . $keywords . "%'";
    }
    switch ($jiami) {
        case 1:
            $sql .= " and a_check=1";
            break;
        case 2:
            $sql .= " and a_check=2";
            break;
        default:
            break;
    }
    _page($sql, 10);//分页
    $sql .= " ORDER BY a_date DESC  LIMIT $_pagenum,$_pagesize";
    $_result = _query($sql);

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
<script type="text/javascript">
    window.onload = function () {
        var all = document.getElementById('all');

        var fm = document.getElementsByName('myform')[0];
        all.onclick = function () {
            //form.elements获取表单内的所有表单
            //checked表示已选
            for (var i = 0; i < fm.elements.length; i++) {
                if (fm.elements[i].name != 'chkall') {
                    fm.elements[i].checked = fm.chkall.checked;
                }
            }
        };
        fm.onsubmit = function () {
            if (confirm('确定要删除这批数据吗？')) {
                return true;
            }
            return false;
        };
    };

</script>
<div id="member">
   <?php
   include 'left.php'
   ?>
    <div id="member_main">
        <h2>帖子管理</h2>
        <form action="" method="post">
            <ul class="article">
                <li><select name="jiami" id="">
                        <option value="2">未加密</option>
                        <option value="1">已加密</option>
                    </select>
                    <input type="text" name="search" value="">
                    <input type="submit" value="搜索">
                </li>

            </ul>
        </form>
        <form method="post" name="myform" action="?action=delete">
            <ul class="article">
                <?php
                $_htmllist = array();//定义一个空数组
                while (!!$_rows = _fetch_array_list($_result)) {
                    $_htmllist['id'] = $_rows['a_id'];
                    $_htmllist['type'] = $_rows['a_type'];
                    $_htmllist['readcount'] = $_rows['a_readcount'];
                    $_htmllist['commendcount'] = $_rows['a_commendcount'];
                    $_htmllist['title'] = $_rows['a_title'];
                    $_htmllist['jiami'] = $_rows['a_check'];

                    ?>

                    <li class="icon<?php echo $_htmllist['type'] ?> ">
                        <em>
                            阅读数(<strong> <?php echo $_htmllist['readcount'] ?></strong>)
                            评论数(<strong><?php echo $_htmllist['commendcount'] ?></strong>)

                            <?php
                            if ($_htmllist['jiami'] == 1) { ?>
                                (<span style="color: #ff0000">已加密</span>)
                            <?php }else{ ?>
                                [<a href="article_modify.php?id=<?php echo $_htmllist['id'] ?>">修改</a>]
                                <input name="ids[]" value="<?php echo $_htmllist['id'] ?>" type="checkbox"/>
                            <?php }?>

                        </em>
                        <a href="article.php?id=<?php echo $_htmllist['id'] ?>"><?php echo _title($_htmllist['title'], 20) ?></a>
                    </li>
                <?php }
                _free_result($_result);
                ?>

                <label for="all">全选 <input type="checkbox" name="chkall" id="all"/></label> <input
                        type="submit" value="批删除"/>
            </ul>
        </form>
        <?php _paging(2); ?>
    </div>
</div>


<?php
include "footer.php";
?>
</body>
</html>
