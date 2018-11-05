<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'post');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//登陆后才可以发帖
if (!isset($_SESSION['username']) && !isset($_SESSION['admin']) ) {
    _location('发帖前，必须登录', 'login.php');
}

//根据登录名查询用户状态
$_rows = _fetch_array("SELECT u_state FROM b_user WHERE u_username='{$_SESSION['username']}' || u_username='{$_SESSION['admin']}' ") ;
if ($_rows['u_state']==1){
    _alert_back('您已被管理员禁止发帖,请联系管理员！');
}
//将帖子写入数据库
if ($_GET['action'] == 'post') {
    _check_code($_POST['code'], $_SESSION['code']); //验证码判断
    if (!!$_rows = _fetch_array("SELECT  u_post_time FROM   b_user WHERE  u_username='{$_SESSION['username']}' || u_username='{$_SESSION['admin']}' LIMIT 1")) {
        global $_system;
        //验证一下是否在规定的时间外发帖
        _timed(time(), $_rows['u_post_time'], $_system['post']);

        //引入验证
        include ROOT_PATH . 'includes/check.func.php';
        /**************************接受帖子内容************************************************/
        $_clean = array();
        if(isset($_SESSION['username'])){
            $_clean['username'] = $_SESSION['username'];
        }else{
            $_clean['username'] = $_SESSION['admin'];
        }
        $_clean['type'] = $_POST['type'];
        $_clean['tag'] = $_POST['type'];
        $_clean['jiami'] = $_POST['jiami'];
        $_clean['keyword'] = $_POST['keyword'];
        $_clean['title'] = _check_post_title($_POST['title'], 2, 40);
        $_clean['content'] = _check_post_content($_POST['content'], 10);
      /*  $_clean = _mysql_string($_clean);*/

        //写入数据库
        $sql="INSERT INTO b_article (a_username, a_title,a_keyword, a_tagid ,a_type,a_content,a_check, a_date) 
					  VALUES ('{$_clean['username']}','{$_clean['title']}','{$_clean['keyword']}','{$_clean['tag']}','{$_clean['type']}','{$_clean['content']}','{$_clean['jiami']}',NOW())";

        _query($sql);
        if (_affected_rows() == 1) {
            $_clean['id'] = _insert_id();
            $_clean['time'] = time();
            _query("UPDATE b_user SET u_post_time='{$_clean['time']}' WHERE u_username='{$_SESSION['username']}' || u_username='{$_SESSION['admin']}'");
            _close();

            _location('帖子发表成功！', 'article.php?id=' . $_clean['id']);
        } else {
            _close();
            _alert_back('帖子发表失败！');
        }
    }
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
    <script type="text/javascript" src="js/post.js"></script>
    <script type="text/javascript" src="ueditor/ueditor.config.js"></script>
    <script type="text/javascript" src="ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" src="ueditor/lang/zh-cn/zh-cn.js"></script>
</head>
<body>
<?php
include 'header.php';
?>

<div id="post">
    <h2>发表帖子</h2>
    <form method="post" name="post" action="?action=post">
        <dl>
            <dt>请填写以下内容</dt>
            <dd >
                类　　型：

                    <select name="type" id="">
                        <?php
                        $sql_tag="select * from b_tags WHERE tag_state=0";
                        $result=_query($sql_tag);
                        while ($rows=_fetch_array_list($result)){
                        ?>
                        <option value="<?php echo $rows['tag_id'] ?>"><?php echo  $rows['tag_name'] ?></option>
                        <?php }?>
                    </select>


            </dd>
            <dd>标　　  题：<input type="text" name="title" class="text"/> (*必填，2-40位)</dd>
            <dd>关　键　字：<input type="text" name="keyword" class="text"/> (选填)</dd>
            <dd>加　　  密：<input type="radio" name="jiami" value='1' checked="checked"/>是<input type="radio" name="jiami" value='2'/>否</dd>
            <dd>
                内　　  容：<textarea name="content" class="common-textarea" id="content" cols="30" style="width: 98%;" rows="20"></textarea>
            </dd>
            <dd>验 证 码：<input type="text" name="code" class="text yzm"/> <img src="code.php" id="code"
                                                                             onclick="javascript:this.src='code.php?tm='+Math.random();"/>
                <input type="submit" class="submit" value="发表帖子"/></dd>
        </dl>
    </form>
</div>
<script type="text/javascript">

    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    UE.getEditor('content',{initialFrameWidth:500,initialFrameHeight:150,});

</script>
<?php
include "footer.php";
?>
</body>
</html>