<?php
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'member_modify');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';
//修改资料
if ($_GET['action'] == 'modify') {
         _check_code($_POST['code'], $_SESSION['code']);//检测验证码
        include ROOT_PATH . 'includes/check.func.php';
        $_clean = array();
        $_clean['password'] = _check_modify_password($_POST['password'], 6);
        $_clean['sex'] = _check_sex($_POST['sex']);
        $_clean['email'] = _check_email($_POST['email'], 6, 40);
        $_clean['qq'] = _check_qq($_POST['qq']);
        $_clean['face']=$_POST['url'];
        //修改资料
        if (empty($_clean['password'])) {
            _query("UPDATE b_user SET 
																u_sex='{$_clean['sex']}',
																u_face='{$_clean['face']}',
																u_email='{$_clean['email']}',
																u_qq='{$_clean['qq']}'
																
													WHERE
																u_username='{$_SESSION['username']}' || u_username='{$_SESSION['admin']}' 
																");
        } else {
            _query("UPDATE b_user SET 
																u_password='{$_clean['password']}',
																u_sex='{$_clean['sex']}',
																u_face='{$_clean['face']}',
																u_email='{$_clean['email']}',
																u_qq='{$_clean['qq']}'
																
													WHERE
																	u_username='{$_SESSION['username']}' || u_username='{$_SESSION['admin']}' 
																");
        }

    //判断是否修改成功
    if (_affected_rows() == 1) {
        _close();

        _location('恭喜你，修改成功！', 'member.php');
    } else {
        _close();
        _location('很遗憾，没有任何数据被修改！', 'member_modify.php');
    }
}
//是否正常登录
if (isset($_SESSION['username']) || isset($_SESSION['admin'])) {
    //获取数据
    $_rows = _fetch_array("SELECT u_username,u_sex,u_face,u_email,u_qq FROM b_user WHERE u_username='{$_SESSION['username']}' || u_username='{$_SESSION['admin']}'");
    if ($_rows) {
        $_html = array();
        $_html['username'] = $_rows['u_username'];
        $_html['sex'] = $_rows['u_sex'];
        $_html['face'] = $_rows['u_face'];
        $_html['email'] = $_rows['u_email'];
        $_html['qq'] = $_rows['u_qq'];
        //签名开关
        if ($_html['switch'] == 1) {
            $_html['switch_html'] = '<input type="radio" checked="checked" name="switch" value="1" /> 启用 <input type="radio" name="switch" value="0" /> 禁用';
        } elseif ($_html['switch'] == 0) {
            $_html['switch_html'] = '<input type="radio" name="switch" value="1" /> 启用 <input type="radio" name="switch" value="0" checked="checked" /> 禁用';
        }

    } else {
        _alert_back('此用户不存在');
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
    <script type="text/javascript" src="js/code.js"></script>
    <script type="text/javascript" src="js/member_modify.js"></script>
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
        <h2>会员管理中心</h2>
        <form method="post" action="?action=modify">
            <dl>
                <dd>用 户 名：<input type="text" value="<?php echo $_html['username'] ?>" class="text" readonly="readonly"/></dd>
                <dd>密　　码：<input type="password" class="text" name="password"/> (留空则不修改)</dd>
                <dd>性　　别：<input type="radio" name="sex" value='0' <?php if ($_html['sex'] ==0) {
                        echo 'checked="checked"';
                    } ?> />男<input type="radio" name="sex" value="1" <?php if ($_html['sex'] == 1) {
                        echo 'checked="checked"';
                    } ?> />女</dd>
                <dd>头　　像：<img src="<?php  echo $_html['face']?>" alt=""><input type="text" name="url" id="url" readonly="readonly" class="text"/>
                    <a href="javascript:;" title="face" id="up">上传</a>(jpg,gif,png)</dd>
                <dd>电子邮件：<input type="text" class="text" name="email" value="<?php echo $_html['email'] ?>"/></dd>

                <dd>Q 　 　Q：<input type="text" class="text" name="qq" value="<?php echo $_html['qq'] ?>"/></dd>

                <dd>验 证 码：<input type="text" name="code" class="text yzm"/> <img src="code.php" id="code"
                                                                                 onclick="javascript:this.src='code.php?tm='+Math.random();"/>
                    <input type="submit" class="submit" value="修改资料"/></dd>
            </dl>
        </form>
    </div>
</div>
<script type="text/javascript">
    window.onload = function () {
        var up = document.getElementById('up');
        up.onclick = function () {
            centerWindow('upimg.php?dir=' + this.title, 'up', '100', '400');
        };
        var fm = document.getElementsByTagName('form')[0];
        fm.onsubmit = function () {
            if (fm.name.value.length < 2 || fm.name.value.length > 20) {
                alert('图片名不得小于2位或者大于20位');
                fm.name.focus(); //将焦点以至表单字段
                return false;
            }
            if (fm.url.value == '') {
                alert('地址不得为空！');
                fm.url.focus(); //将焦点以至表单字段
                return false;
            }
            return true;
        };
    };

    function centerWindow(url, name, height, width) {
        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;
        window.open(url, name, 'height=' + height + ',width=' + width + ',top=' + top + ',left=' + left);
    }

</script>

<?php
include "footer.php";
?>
</body>
</html>
