<?php
session_start();//开启session
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'index');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php'; //转换成硬路径，速度更快

//读取帖子列表 不是加密的且不是回复的帖子
global $_pagesize, $_pagenum, $_system;
$keywords=$_POST['search'];//获取搜索关键字
$id=$_GET['tagid'];//获取标签ID

$sql ="SELECT a_id,a_title,a_type,a_readcount,a_commendcount,a_check ,a_tagid FROM b_article WHERE  a_reid=0 and a_check=2";
if(!empty($id)){//如果id不为空  拼接sql查询条件
    $sql .= " and a_tagid ='".$id."'";
}
if(!empty($keywords)){//如果关键字不为空  拼接sql查询条件
    $sql .= " and a_title like '".$keywords."%' or a_keyword like '".$keywords."%'";
}
_page($sql,10);
$sql .=" ORDER BY a_readcount DESC  LIMIT $_pagenum,$_pagesize";
$_result = _query($sql);


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

<div id="list">
    <h2>帖子列表</h2>
    <div>
        <ul>
            <li>帖子数：1005</li>
        </ul>
    </div>
    <a <?php $_rows = _fetch_array("select u_state from b_user where u_username='{$_SESSION['username']}'");
    if($_rows['u_state']==1){  echo 'href="index.php"  onclick="return confirm(\'您已被禁用发帖,请联系管理员\')"';}else{ echo  'href="post.php"' ;} ?>  class="post" >发表帖子</a>
    <ul class="article">
        <?php
        $_htmllist = array();//定义一个空数组
        while (!!$_rows = _fetch_array_list($_result)) {
            $_htmllist['id'] = $_rows['a_id'];
            $_htmllist['type'] = $_rows['a_type'];
            $_htmllist['readcount'] = $_rows['a_readcount'];
            $_htmllist['commendcount'] = $_rows['a_commendcount'];
            $_htmllist['title'] = $_rows['a_title'];

         ?>
            <li <?php if($_htmllist['type']<16){ ?>class="icon<?php echo $_htmllist['type'] ?><?}else{ echo  'class="icon1"';}?> ">
                <em>
                    阅读数(<strong> <?php echo $_htmllist['readcount'] ?></strong>)
                    评论数(<strong><?php echo $_htmllist['commendcount'] ?></strong>)
                </em>
                <a href="article.php?id=<?php echo $_htmllist['id'] ?>"><?php echo _title($_htmllist['title'], 20) ?></a>
            </li>
        <?php }
        _free_result($_result);
        ?>
    </ul>
    <?php _paging(2); ?>
</div>

<div id="user">
    <h2>搜索</h2>
        <form action="index.php" method="post">
            <ul class="article">
              <dd>
                  <dl>
                      <input type="text" name="search" value=""  placeholder="标题或关键词" style="width:120px;height:19px;border:1px dashed #333;background:#fff;">
                      <input type="submit" value="搜索">
                  </dl>

              </dd>
            </ul>
        </form>
</div>

<div id="tag">
    <h2>按标签浏览</h2>

        <dl>
            <?php
                $sql_tag="select * from b_tags WHERE tag_state=0";
                $result=_query($sql_tag);
                while ($rows=_fetch_array_list($result)){
            ?>
                    <dd class="tags"><a href="index.php?tag=<?php echo $rows['tag_name']?>&tagid=<?php echo $rows['tag_id']?>" ><?php echo $rows['tag_name']?></a></dd>
            <?php }?>

        </dl>

</div>
<div id="pics">
    <h2>最新帖子</h2>
    <ul class="pics_article">
        <?php
        //最新帖子,找到时间点最后上传的文章，并且是公开的
        $re_new=_query("SELECT * FROM b_article  WHERE  a_reid=0 and a_check=2 ORDER BY a_date DESC LIMIT 3 ");
        while ($_re_new = _fetch_array_list($re_new)){
        ?>
        <li> <a href="article.php?id=<?php echo $_re_new['a_id'] ?>"><?php echo $_re_new['a_title'] ?></a></li>
        <?php } ?>
    </ul>

</div>

<div id="link">
    <h2>友情链接</h2>
    <dl>
        <dd class="tags"><a href="http://www.lopwon.com/" >立云图志</a></dd>
        <dd class="tags"><a href="http://duonuli.com/" >多努力网</a></dd>
        <dd class="tags"><a href="http://daohang.lusongsong.com/" >博客大全</a></dd>
        <dd class="tags"><a href="http://jandan.net/" >煎蛋网</a></dd>
        <dd class="tags"><a href="http://tech2ipo.com/" >创见网</a></dd>
        <dd class="tags"><a href="http://down.lusongsong.com/" >松松资讯</a></dd>
        <dd class="tags"><a href="http://www.ikanchai.com/" >砍柴网</a></dd>
        <dd class="tags"><a href="http://www.geekpark.net/" >极客公园</a></dd>
        <dd class="tags"><a href="http://www.kejilie.com/" >科技猎</a></dd>
        <dd class="tags"><a href="https://www.appinn.com/" >小众软件</a></dd>
    </dl>
</div>

<?php
include "footer.php";
?>

</body>
</html>
