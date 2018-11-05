<?php
/**
 * 帖子详情文件
 */
session_start();

//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'article');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//处理精华帖
if ($_GET['action'] == 'nice' && isset($_GET['id']) && isset($_GET['on'])) {
    if (!!$_rows = _fetch_array("SELECT u_level FROM b_user WHERE u_username='{$_SESSION['admin']}' LIMIT 1")) {
        //设置精华帖，或者取消精华帖
        _query("UPDATE b_article SET a_nice='{$_GET['on']}' WHERE a_id='{$_GET['id']}'");
        if (_affected_rows() == 1) {
            _close();
            _location('精华帖操作成功！', 'article.php?id=' . $_GET['id']);
        } else {
            _close();
            _alert_back('精华帖设置失败！');
        }
    } else {
        _alert_back('非法登录！');
    }
}
//处理回帖
if ($_GET['action'] == 'rearticle') {
    global $_system;
    if (!empty($_system['code'])) {
        _check_code($_POST['code'], $_SESSION['code']); //验证码判断
    }
    //判断回帖时间
    if (!!$_rows = _fetch_array("SELECT u_article_time FROM b_user WHERE u_username='{$_SESSION['username']}'|| u_username='{$_SESSION['admin']}' LIMIT 1")) {
        _timed(time(), $_rows['u_article_time'], $_system['re']);
        //接受数据
        $_clean = array();
        $_clean['reid'] = $_POST['reid'];
        $_clean['type'] = $_POST['type'];
        $_clean['title'] = $_POST['title'];
        $_clean['content'] = $_POST['content'];
        if($_SESSION['usenamae']){
            $_clean['username'] = $_SESSION['username'];
        }else{
            $_clean['username'] = $_SESSION['admin'];
        }

        //写入数据库
        $sql="INSERT INTO b_article (
                                               a_reid,
                                               a_username,
                                               a_title,
                                               a_type,
                                               a_content,
                                               a_date
                                              )
                                     VALUES (
                                                '{$_clean['reid']}',
                                                '{$_clean['username']}',
                                                '{$_clean['title']}',
                                                '{$_clean['type']}',
                                                '{$_clean['content']}',
                                                NOW()
                                                    )";

        _query($sql);
        if (_affected_rows() == 1) {

            $_clean['time'] = time();
            _query("UPDATE b_user SET u_article_time='{$_clean['time']}' WHERE u_username='{$_COOKIE['username']}' || u_username='{$_SESSION['admin']}'");
            _query("UPDATE b_article SET a_commendcount=a_commendcount+1 WHERE a_reid=0 AND a_id='{$_clean['reid']}'");
            _close();

            _location('回帖成功！', 'article.php?id=' . $_clean['reid']);
        } else {
            _close();

            _alert_back('回帖失败！');
        }
    } else {
        _alert_back('非法登录！');
    }
}
//读出数据
if (isset($_GET['id'])) {
    $sql_article="SELECT 
                            a_id,
                            a_username,
                            a_title,
                            a_type,
                            a_tagid,
                            a_content,
                            a_readcount,
                            a_commendcount,
                            a_last_modify_date,
                            a_nice,
                            a_date 
                      FROM 
                            b_article 
                     WHERE
                            a_reid=0
                    AND
                            a_id='{$_GET['id']}'";

    if (!!$_rows = _fetch_array($sql_article)) {

        //累积阅读量
        _query("UPDATE b_article SET a_readcount=a_readcount+1 WHERE a_id='{$_GET['id']}'");

        $_html = array();
        $_html['reid'] = $_rows['a_id'];
        $_html['username_subject'] = $_rows['a_username'];
        $_html['title'] = $_rows['a_title'];
        $_html['type'] = $_rows['a_type'];
        $_html['tagid'] = $_rows['a_tagid'];
        $_html['content'] = $_rows['a_content'];
        $_html['readcount'] = $_rows['a_readcount'];
        $_html['commendcount'] = $_rows['a_commendcount'];
        $_html['nice'] = $_rows['a_nice'];
        $_html['last_modify_date'] = $_rows['a_last_modify_date'];
        $_html['date'] = $_rows['a_date'];
        //拿出用户名，去查找用户信息
        $sql_user="SELECT u_id,u_sex,u_face,u_email FROM b_user WHERE  u_username='{$_html['username_subject']}'";
        if (!!$_rows_user = _fetch_array($sql_user)) {
            //提取用户信息 转义
            $_html['userid'] = $_rows_user['u_id'];
            $_html['sex'] = $_rows_user['u_sex'];
            $_html['face'] = $_rows_user['u_face'];
            $_html['email'] = $_rows_user['u_email'];
            //创建一个全局变量，做个带参的分页
            global $_id;
            $_id = 'id=' . $_html['reid'] . '&';

            //读取最后修改信息
            if ($_html['last_modify_date'] != '0000-00-00 00:00:00') {
                $_html['last_modify_date_string'] = '本贴已由[' . $_html['username_subject'] . ']于' . $_html['last_modify_date'] . '修改过！';
            }
            //给楼主回复
            if ($_COOKIE['username']) {
                $_html['re'] = '<span>[<a href="#ree" name="re" title="回复1楼的' . $_html['username_subject'] . '">回复</a>]</span>';
            }
            //读取回帖
            global $_pagesize, $_pagenum, $_page;
            _page("SELECT a_id FROM b_article WHERE a_reid='{$_html['reid']}'", 10);

            $_result_re = _query("SELECT a_username,a_type,a_title,a_content,a_date FROM b_article WHERE a_reid='{$_html['reid']}'ORDER BY a_date ASC LIMIT $_pagenum,$_pagesize");


        } else {
            //这个用户已被删除

        }
    } else {
        _alert_back('不存在这个主题！');
    }
} else {
    _alert_back('非法操作！');
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
    <script type="text/javascript" src="js/code.js"></script>
    <script type="text/javascript" src="js/MyFriends.js"></script>

    <script type="text/javascript" src="ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" src="ueditor/lang/zh-cn/zh-cn.js"></script>
</head>
<body>
<?php
include 'header.php';
?>

<div id="article">
    <h2>帖子详情</h2>
    <?php
    if (!empty($_html['nice'])) {
        ?>
        <img src="images/nice.gif" alt="精华帖" class="nice"/>

        <?php
    }
    //浏览量达到200，并且评论量达到20即可为热帖
    if ($_html['readcount'] >= 200 && $_html['commendcount'] >= 20) {
        ?>
        <img src="images/hot.gif" alt="热帖" class="hot"/>

        <?php
    }
    if ($_page == 1) {
        ?>
        <div id="subject">
            <dl>
                <dd class="user"><?php echo $_html['username_subject'] ?>(<?php if ($_html['u_sex']==0){echo "男";}else{ echo "女"; }?>)[楼主]</dd>
                <dt><img src="<?php echo $_html['face'] ?>" alt="<?php echo $_html['username_subject'] ?>"/></dt>
                <dd class="message"><a href="javascript:;" name="message"  title="<?php echo $_html['userid'] ?>">发消息</a>
                </dd>
                <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid'] ?>">加为好友</a>
                </dd>

                <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid'] ?>">给他送花</a>
                </dd>
                <dd class="email">邮件：<a href="mailto:<?php echo $_html['email'] ?>"><?php echo $_html['email'] ?></a>
                </dd>

            </dl>
            <div class="content">
                <div class="user">
				<span>
					<?php
                    if ($_SESSION['admin']) {
                        if (empty($_html['nice'])) {
                            ?>
                            [<a href="article.php?action=nice&on=1&id=<?php echo $_html['reid'] ?>">设置精华</a>]
                        <?php } else { ?>
                            [<a href="article.php?action=nice&on=0&id=<?php echo $_html['reid'] ?>">取消精华</a>]
                            <?php
                        }
                    }
                    ?>
                    <?php echo $_html['subject_modify'] ?> 1#
				</span><?php echo $_html['username_subject'] ?> | 发表于：<?php echo $_html['date'] ?>
                </div>
                <h3><?php echo $_html['title'] ?><?php echo $_html['re'] ?></h3>
                    主题：<?php  $sql_tag="select tag_name from b_tags WHERE tag_id='{$_html['tagid']}'";
                                    $tag=_fetch_array($sql_tag);
                                    echo $tag['tag_name'] ?>
                <div class="detail">
                    <?php echo $_html['content']?>
                </div>
                <div class="read">
                    <p><?php echo $_html['last_modify_date_string'] ?></p>
                    阅读量：(<?php echo $_html['readcount'] ?>) 评论量：(<?php echo $_html['commendcount'] ?>)
                </div>
            </div>
        </div>
    <?php } ?>


    <p class="line"></p>

    <?php
    $_i = 2;

    while (!!$_rows = _fetch_array_list($_result_re)) {
        $_html['username'] = $_rows['a_username'];
        $_html['type'] = $_rows['a_type'];
        $_html['retitle'] = $_rows['a_title'];
        $_html['content'] = $_rows['a_content'];
        $_html['date'] = $_rows['a_date'];


    $sql_uuser="SELECT u_id, u_sex, u_face, u_email FROM  b_user  WHERE  u_username='{$_html['username']}'";

        if (!!$_rows = _fetch_array($sql_uuser)) {
            //提取用户信息
            $_html['userid'] = $_rows['u_id'];
            $_html['sex'] = $_rows['u_sex'];
            $_html['face'] = $_rows['u_face'];
            $_html['email'] = $_rows['u_email'];
            //楼层
            if ($_page == 1 && $_i == 2) {
                if ($_html['username'] == $_html['username_subject']) {
                    $_html['username_html'] = $_html['username'] . '(楼主)';
                } else {
                    $_html['username_html'] = $_html['username'] . '(沙发)';
                }
            } else {
                $_html['username_html'] = $_html['username'];
            }

        } else {
            //这个用户可能已经被删除了
        }

        //跟帖回复
        if ($_SESSION['username'] || $_SESSION['admin']) {
            $_html['re'] = '<span>[<a href="#ree" name="re" title="回复' . ($_i + (($_page - 1) * $_pagesize)) . '楼的' . $_html['username'] . '">回复</a>]</span>';
        }
        ?>
        <div class="re">
            <dl>
                <dd class="user"><?php echo $_html['username_html'] ?>(<?php if ($_html['u_sex']==0){echo "男";}else{ echo "女"; }?>)</dd>
                <dt><img src="<?php echo $_html['face'] ?>" alt="<?php echo $_html['username'] ?>"/></dt>
                <dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid'] ?>">发消息</a>
                </dd>
                <dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid'] ?>">加为好友</a>
                </dd>
                <dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid'] ?>">给他送花</a>
                </dd>
                <dd class="email">邮件：<a href="mailto:<?php echo $_html['email'] ?>"><?php echo $_html['email'] ?></a>
                </dd>

            </dl>
            <div class="content">
                <div class="user">
                    <span><?php echo $_i + (($_page - 1) * $_pagesize); ?>#</span><?php echo $_html['username'] ?> |
                    发表于：<?php echo $_html['date'] ?>
                </div>
                <h3><?php echo $_html['title'] ?><?php echo $_html['re'] ?></h3>
                主题：<?php  $sql_tag="select tag_name from b_tags WHERE tag_id='{$_html['tagid']}'";
                $tag=_fetch_array($sql_tag);
                echo $tag['tag_name'] ?>
                <div class="detail">
                    <?php echo$_html['content'] ?>
                </div>
            </div>
        </div>
        <p class="line"></p>
        <?php
        $_i++;
    }
    _free_result($_result_re);
    _paging(1);
    ?>

    <?php if (isset($_SESSION['username']) || $_SESSION['admin']) {  ?>
        <a name="ree"></a>
        <form method="post" action="?action=rearticle">
            <input type="hidden" name="reid" value="<?php echo $_html['reid'] ?>"/>
            <input type="hidden" name="type" value="<?php echo $_html['type'] ?>"/>
            <dl>
                <h2>回复帖子</h2>
                <dd>标　　题：<input type="text" name="title" class="text" value="RE:<?php echo $_html['title'] ?>" readonly="readonly"/></dd>
                <dd>
                    内　　容：<textarea name="content" class="common-textarea" id="content" cols="30" style="width: 98%;" rows="20"></textarea>
                </dd>

                <dd>
                    <?php if (!empty($_system['code'])) { ?>
                        验 证 码：
                        <input type="text" name="code" class="text yzm"/> <img src="code.php" id="code"
                                                                               onclick="javascript:this.src='code.php?tm='+Math.random();"/>
                    <?php } ?>
                    <input type="submit" class="submit" value="回复帖子"/></dd>
            </dl>
        </form>
    <?php } ?>
    <script type="text/javascript">

        //实例化编辑器
        //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
        UE.getEditor('content',{initialFrameWidth:500,initialFrameHeight:150,});
    </script>
</div>
<?php
include 'footer.php';
?>
</body>
</html>
