<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>欢迎登录后台管理系统</title>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <script language="JavaScript" src="js/jquery.js"></script>
    <script src="js/cloud.js" type="text/javascript"></script>

    <script language="javascript">
        $(function () {
            $('.loginbox').css({'position': 'absolute', 'left': ($(window).width() - 692) / 2});
            $(window).resize(function () {
                $('.loginbox').css({'position': 'absolute', 'left': ($(window).width() - 692) / 2});
            })
        });
    </script>

</head>

<body style="background-color:#1c77ac; background-image:url(images/light.png); background-repeat:no-repeat; background-position:center top; overflow:hidden;">


<div id="mainBody">
    <div id="cloud1" class="cloud"></div>
    <div id="cloud2" class="cloud"></div>
</div>
<div class="logintop">
    <span>欢迎登录后台管理界面平台</span>
    <ul>
        <li><a href="#">回首页</a></li>
        <li><a href="#">帮助</a></li>
        <li><a href="#">关于</a></li>
    </ul>
</div>
<div class="loginbody">

    <span class="systemlogo"></span>

    <div class="loginbox loginbox1">
        <form action="checkcode.php?action=login" method="post" ">
            <ul>
                <li><input name="username" type="text" class="loginuser" placeholder="登录名"/></li>
                <li><input name="password" type="password" class="loginpwd" placeholder="密码"/></li>
                <li class="yzm">
                    <span><input name="code" type="text" placeholder="验证码"/></span><cite>


                        <img title="点击刷新" src="captcha.php" align="absbottom"
                             onclick="this.src='captcha.php?'+Math.random();"></img>
                    </cite>
                </li>
                <li><input  type="submit"  class="loginbtn" value="登录"/>
            </ul>

        </form>
    </div>

</div>

<script type="text/javascript">


</script>
<div class="loginbm">版权所有 2017 <a href=""></a></div>


</body>

</html>
