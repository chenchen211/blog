<?php
/**
 * 退出文件
 */
session_start();
define('IN_TG', true);
require dirname(__FILE__) . '/includes/common.inc.php';
unset($_SESSION['admin']);
unset($_SESSION['username']);//销毁session
echo "<script language=javascript>alert('已安全退出!');</script>"  ;
echo "<Meta http-equiv='refresh' content='0;URL=./index.php'>";

?>