<?php
/**
 * 添加帖子标签文件
 */
session_start();

include 'check.php';
//定义个常量，用来指定本页的内容
define('IN_TG', true);
//引入公共文件
include '../includes/common.inc.php';
$title = "添加文章标签";
$flag = "am-icon-users";
$token = md5(time() . rand(1000, 9999));
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
                <form class="am-form" autocomplete="off" enctype="multipart/form-data" action="admin.php" method="post">
                    <input type="hidden" name="act" value="addTag">

                    <div class="am-form-group am-cf">
                        <div class="zuo">标签名称：</div>
                        <div class="you">
                            <input type="text" class="am-input-sm" id="name" name="name" >
                        </div>
                    </div>
                   
                    <div class="am-form-group am-cf" hidden="hidden">
                        <input type="text" name="token" value="<?php echo $token;?>">
                    </div>

                    <div class="am-form-group am-cf" style="margin-left: 150px;">
                        <div class="you">
                            <p>
                                <input type="submit" class="am-btn am-btn-success am-radius" value="添加标签"/>
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
<script src="assets/js/amazeui.min.js"></script>
<script type="text/javascript">
    function checkform() {
        var name = $("#name").val();
      
        
        if (name == '') {
            alert("请输入标签名称");
            return false;
        }
        
        return true;
    }
</script>

</body>
</html>