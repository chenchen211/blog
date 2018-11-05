<?php
error_reporting(0);
/**
 * 全局配置文件
 */
//防止恶意调用
if (!defined('IN_TG')) {
    exit('Access Defined!');
}

//设置字符集编码
header('Content-Type: text/html; charset=utf-8');

//转换硬路径常量
define('ROOT_PATH', substr(dirname(__FILE__), 0, -8));

//创建一个自动转义状态的常量
define('GPC', get_magic_quotes_gpc());

//拒绝PHP低版本
if (PHP_VERSION < '4.1.0') {
    exit('Version is to Low!');
}

//引入函数库
require ROOT_PATH . 'includes/global.func.php';
require ROOT_PATH . 'includes/mysql.func.php';


//数据库连接
define('DB_HOST', 'localhost');//本机地址  可填 127.0.0.1
define('DB_USER', 'root');//用户
define('DB_PWD', '930802');//密码
define('DB_NAME', 'blogger');//数据库名字

//初始化数据库
_connect();   //连接MYSQL数据库
_select_db();   //选择指定的数据库
_set_names();   //设置字符集

//当前用户登陆后  查询当前用户的短信  提醒COUNT(tg_id)是取得字段的总和
$_message = _fetch_array("SELECT COUNT(m_id) AS count FROM b_message  WHERE m_state=0 AND (m_touser='{$_SESSION['username']}' OR  m_touser='{$_SESSION['admin']}')");
if (empty($_message['count'])) {
    $GLOBALS['message'] = '<strong class="noread"><a href="member_message.php">(0)</a></strong>';
} else {
    $GLOBALS['message'] = '<a href="member_message.php"><strong class="read">(' . $_message['count'] . ')</strong></a>';
}


//网站系统设置初始化
if (!!$_rows = _fetch_array("SELECT 
                                            s_webname,
                                            s_skin,
                                            s_string,
                                            s_code 
									FROM 
											b_system 
									WHERE 
											s_id=1 
									 LIMIT 
											1"
)) {
    $_system = array();
    $_system['webname'] = $_rows['s_webname'];
    $_system['skin'] = $_rows['s_skin'];
    $_system['code'] = $_rows['s_code'];
    $_system['string'] = $_rows['s_string'];

    //如果有skin的cookie那么就替代系统数据库的皮肤
    if ($_COOKIE['skin']) {
        $_system['skin'] = $_COOKIE['skin'];
    }
} else {
    exit('系统表异常，请管理员检查！');
}


?>