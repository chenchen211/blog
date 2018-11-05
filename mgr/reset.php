<?php
include 'check.php';
session_start();
//定义个常量，用来指定本页的内容
define('IN_TG', true);
//引入公共文件
include '../includes/common.inc.php';

$flag = "am-icon-edit";
$title = "修改密码";
$token = md5(time() . rand(1000, 9999));
$_SESSION['token'] = $token;
?>

<?php include 'head.html'; ?>
<div class="am-cf admin-main">
    <?php include 'left.php'; ?>
    <div class=" admin-content">
        <?php include 'navigation.html'; ?>

        <div class="admin-biaogelist">

            <div class="listbiaoti am-cf">
                <ul class="<?php echo $flag; ?>"> <?php echo $title; ?></ul>

                <dl class="am-icon-home" style="float: right;"> 当前位置： 首页 > <a
                            href="javascript:;"><?php echo $title; ?></a></dl>
            </div>

            <div class="fbneirong">
                <form id="mail" autocomplete="off" class="am-form" action="admin.php" method="post">
                    <input type="hidden" name="act" value="edit">
                    <div class="am-form-group am-cf">
                        <div class="zuo">当前管理员：</div>
                        <div class="you">
                            <input type="text" class="am-input-sm" readonly="readonly" name="username"
                                   value="<?php echo $_SESSION['admin']; ?>"/>
                        </div>
                    </div>

                    <div class="am-form-group am-cf">
                        <div class="zuo">请输入登录密码：</div>
                        <div class="you">
                            <input type="text" autofocus="autofocus" id="old" class="am-input-sm" name="old" value=""/>
                        </div>
                    </div>


                    <input name="token" value="<?php echo $token; ?>" hidden="hidden"/>


                    <div class="am-form-group am-cf">
                        <div class="zuo">请输入新密码：</div>
                        <div class="you">
                            <input type="text" class="am-input-sm" id="new" name="new" value=""/>
                        </div>
                    </div>

                    <div class="am-form-group am-cf">
                        <div class="you" style="margin-left: 11%;">
                            <input type="submit" onclick="return check();" class="am-btn am-btn-success am-radius"
                                   value="修改"/>
                            <input onclick="randPassword()" class="am-btn am-btn-success am-radius" value="产生随机密码"/>
                        </div>
                    </div>
                </form>
            </div>

            <?php include 'foot.html'; ?>
        </div>

    </div>

</div>
<script src="assets/js/amazeui.min.js"></script>
<script type="text/javascript">

    function check() {
        if ($("#old").val() == '') {
            alert("请输入原密码");
            return false;
        } else if ($("#order").val() == '') {
            alert("请输入口令");
            return false;
        } else if ($("#new").val() == '') {
            alert("请输入新密码");
            return false;
        }
        return confirm('请在确定前牢记您的密码');
    }

    function randPassword() {
        var text = ['abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', '1234567890'];
        var rand = function (min, max) {
            return Math.floor(Math.max(min, Math.random() * (max + 1)));
        }
        var len = rand(15, 22);
        var pw = '';
        for (i = 0; i < len; ++i) {
            var strpos = rand(0, 2);
            pw += text[strpos].charAt(rand(0, text[strpos].length));
        }
        $("#new").val(pw);
    }
</script>
</body>
</html>