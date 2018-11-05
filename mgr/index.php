<?php
include './check.php';
define('IN_TG', true);
include ('../includes/common.inc.php');
$title = "服务信息";
$flag = "am-icon-gears";
?>

<?php
include 'head.html';
?>
<div class="am-cf admin-main">
    <?php include 'left.php' ?>
    <div class=" admin-content">
        <?php
        include 'navigation.html';
        ?>


        <div class="admin-biaogelist">

            <div class="listbiaoti am-cf">
                <ul class="<?php echo $flag; ?>"><?php echo $title; ?></ul>

                <dl class="am-icon-home" style="float: right;">当前位置： 首页 > <a href="javascript:;">服务信息</a></dl>

            </div>

            <?php include 'server.php' ?>
            <?php include 'foot.html' ?>


        </div>

    </div>


</div>

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/polyfill/rem.min.js"></script>
<script src="assets/js/polyfill/respond.min.js"></script>
<script src="assets/js/amazeui.legacy.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="assets/js/amazeui.min.js"></script>
<!--<![endif]-->


</body>
</html>