<?php
/**
 * 修改用户文件
 */
session_start();
include 'check.php';
//定义个常量，用来指定本页的内容
define('IN_TG', true);
//引入公共文件
include '../includes/common.inc.php';
$title="修改用户";
$flag="am-icon-edit";
$id=$_REQUEST['id'];
$sql_id="select * from b_user where u_id=".$id;

if (!!$row= _fetch_array($sql_id)) {

}else{
    _alert_back('操作无效！','userLst.php');
}
$token=md5(time().rand(1000,9999));
$_SESSION['token'] = $token;
?>

<?php
include 'head.html';
?>
<div class="am-cf admin-main">

    <?php
    include 'left.php';
    ?>
    <div class=" admin-content">
        <?php
        include 'navigation.html';
        ?>

        <div class="admin-biaogelist">

            <div class="listbiaoti am-cf">
                <ul class="<?php echo $flag;?>"> <?php echo $title;?></ul>

                <dl class="am-icon-home" style="float: right;"> 当前位置： 首页 > <a href="javascript:;"><?php echo $title;?></a></dl>
            </div>

            <div class="fbneirong">
                <form class="am-form" autocomplete="off" enctype="multipart/form-data" action="user.php" method="post">
                    <input type="hidden" name="act" value="edit">

                    <input type="hidden" name="id" value="<?php echo $row['u_id'];?>">
                    <div class="am-form-group am-cf">
                        <div class="zuo">用户名：</div>
                        <div class="you">
                            <input type="text" class="am-input-sm" id="name" name="name" value="<?php echo $row['u_username'];?>">
                        </div>
                    </div>
                    <div class="am-form-group am-cf">
                        <div class="zuo">头像：</div>
                        <div class="you">
                            <img src="../<?php echo $row['u_face'];?>" alt="" style="width: 20%;height: 20%">
                        </div>
                    </div>
                    <div class="am-form-group am-cf">
                        <div class="zuo">密码：</div>
                        <div class="you">
                            <input type="text" class="am-input-sm" id="pwd" name="pwd" value="<?php echo $row['u_pwdm'];?>">
                        </div>
                    </div>
                    <div class="am-form-group am-cf">
                        <div class="zuo">性别：</div>
                        <div class="you">
                            <input type="radio" class="am-input-sm" id="sex" name="sex"  value="0" <?php if($row['sex']==0) {?>  checked="checked" <?php }?>>男
                            <input type="radio" class="am-input-sm" id="sex" name="sex"  value="1" <?php if($row['sex']==1) {?>  checked="checked" <?php }?>>女
                        </div>
                    </div>
                    <div class="am-form-group am-cf">
                        <div class="zuo">QQ：</div>
                        <div class="you">
                            <input type="text" class="am-input-sm" id="qq" name="qq" value="<?php echo $row['u_qq'];?>">
                        </div>
                    </div>
                    <div class="am-form-group am-cf">
                        <div class="zuo">邮箱：</div>
                        <div class="you">
                            <input type="text" class="am-input-sm" id="email" name="email" value="<?php echo $row['u_email'];?>">
                        </div>
                    </div>
                    <div class="am-form-group am-cf">
                        <div class="zuo">密保问题：</div>
                        <div class="you">
                            <input type="text" class="am-input-sm" id="question" name="question" value="<?php echo $row['u_question'];?>">
                        </div>
                    </div>
                    <div class="am-form-group am-cf">
                        <div class="zuo">密保答案：</div>
                        <div class="you">
                            <input type="text" class="am-input-sm" id="answer" name="answer" value="<?php echo $row['u_answer'];?>">
                        </div>
                    </div>

                    <input type="hidden" class="am-input-sm" id="token" name="token" value="<?php echo $token;?>">
                    <div class="am-form-group am-cf" style="margin-left: 150px;">
                        <div class="you">
                            <p>
                                <input type="submit" class="am-btn am-btn-success am-radius" value="确认修改"/>
                            </p>
                        </div>
                    </div>

                </form>
            </div>


            <?php
            include 'foot.html';
            ?>
        </div>

    </div>

</div>
<!--[if (gte IE 9)|!(IE)]><!-->
<script src="assets/js/amazeui.min.js"></script>
<!--<![endif]-->
<script type="text/javascript">
    function check() {
        var name = $("#name").val();
        var en_name = $("#en_name").val();
        var logo = $("#logo").val();
        var parent = $("#parent").val();
        if(name == '') {
            alert("请输入分类名字");
            return false;
        }
        else if(en_name == '') {
            alert("请输入分类英文名字");
            return false;
        }
        else if(parent == 0 && logo == '') {
            alert("一级分类必须选择图标");
            return false;
        }
        return true;
    }
</script>

</body>
</html>