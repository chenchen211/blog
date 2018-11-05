<?php
/**
 * 修改帖子文件
 */
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'article_modify');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//登陆后才可以发帖
if (!isset($_SESSION['username']) && !isset($_SESSION['admin'])) {
    _location('发帖前，必须登录', 'login.php');
}
//修改,还需要判断一下权限
if ($_GET['action'] == 'modify') {
    _check_code($_POST['code'], $_SESSION['code']); //验证码判断
    if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])){
/***********************开始修改******************************/
        include ROOT_PATH . 'includes/check.func.php';
        $_clean = array();
        $_clean['id'] = $_POST['id'];
        $_clean['type'] = $_POST['type'];
        $_clean['tagid'] = $_POST['type'];
        $_clean['title'] = _check_post_title($_POST['title'], 2, 40);
        $_clean['content'] = _check_post_content($_POST['content'], 10);
        $_clean['jiami'] = $_POST['jiami'];
/***********************执行SQL语句******************************/
        $sql="UPDATE b_article 
              SET 
                    a_type='{$_clean['type']}',
                     a_tagid='{$_clean['tagid']}',
                    a_title='{$_clean['title']}',
                    a_content='{$_clean['content']}',
                    a_check='{$_clean['jiami']}',
                    a_last_modify_date=NOW()
               WHERE
                    a_id='{$_clean['id']}'";
        _query($sql);
        if (_affected_rows() == 1) {
            _close();
            _location('帖子修改成功！', 'article.php?id=' . $_clean['id']);
        } else {
            _close();
            _alert_back('帖子修改失败！');
        }
    } else {
        _alert_back('非法登录！');
    }
}
/***********************读取数据******************************/
if (isset($_GET['id'])) {
      $sql="SELECT *  FROM  b_article  WHERE  a_reid=0  AND a_id='{$_GET['id']}'";

    if (!!$_rows = _fetch_array($sql)) {

    } else {
        _alert_back('不存在此帖子！');
    }
} else {
    _alert_back('非法操作！');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    <form method="post" name="post" action="?action=modify">
        <input type="hidden" value="<?php  echo  $_rows['a_id']?>" name="id">
        <dl>
            <dt>请填写以下内容</dt>
            <dd >
                类　　型：

                <select name="type" id="" >
                    <?php
                    $sql_tag="select * from b_tags WHERE tag_state=0";

                    $result=_query($sql_tag);
                    while ($rows=_fetch_array_list($result)){
                        ?>
                        <option value="<?php echo $rows['tag_id'] ?>"><?php echo  $rows['tag_name'] ?></option>
                    <?php }?>
                </select>

            </dd>
            <dd>标　　  题：<input type="text" name="title" class="text" value="<?php echo $_rows['a_title']?>"/> (*必填，2-40位)</dd>
            <dd>关　键　字：<input type="text" name="keyword" class="text" value="<?php echo $_rows['a_keyword']?>"/> (选填)</dd>
            <dd>加　　  密：<input type="radio" name="jiami" value='1' <?php if ($_rows['a_check']==1) {
                    echo 'checked="checked"';
                } ?> />是<input type="radio" name="jiami" value="2" <?php if ($_rows['a_check']==2) {
                    echo 'checked="checked"';
                } ?> />否</dd>
            <dd>
                内　　  容：<textarea name="content" class="common-textarea" id="content" cols="30" style="width: 98%;" rows="20"><?php echo  $_rows['a_content'] ?></textarea>
            </dd>
            <dd>验 证 码：<input type="text" name="code" class="text yzm"/> <img src="code.php" id="code"
                                                                             onclick="javascript:this.src='code.php?tm='+Math.random();"/>
                <input type="submit" class="submit" value="修改帖子"/></dd>
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