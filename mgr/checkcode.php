<?php
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
include_once ('../includes/common.inc.php');
session_start();
header("Content-type:text/html;charset=utf8");
global $_system;
//开始处理登录状态
if ($_GET['action'] == 'login') {
    if (!empty($_system['code'])) {
        //为了防止恶意登录，跨站攻击
        _check_code($_POST['code'], $_SESSION['code']);
    }
    //引入验证文件
    include  '../includes/login.func.php';
    //接受数据
    $_clean = array();
    $_clean['username'] = _check_username($_POST['username'], 2, 20);
    $_clean['password'] = _check_password($_POST['password'], 6);

    //到数据库去验证
    if (!!$_rows = _fetch_array("SELECT u_username,u_level FROM b_user WHERE u_username='{$_clean['username']}' AND u_password='{$_clean['password']}' LIMIT 1")) {
        //登录成功后，记录登录信息
        _query("UPDATE b_user SET u_last_time=NOW(),u_last_ip='{$_SERVER["REMOTE_ADDR"]}',u_login_count=u_login_count+1 WHERE u_username='{$_rows['u_username']}'");
        
        if ($_rows['u_level'] == 1 || $_rows['u_level']==2) {
            $_SESSION['admin'] = $_rows['u_username'];
            _close();
            _location('登录成功！', 'index.php');
        }else{
        	_close();
        	_location('非管理员请勿操作！', 'login.php');
        }
        
    } else {
        _close();

        _location('用户名密码不正确', 'login.php');
    }
}

?>