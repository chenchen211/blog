<?php
session_start();
error_reporting(0);
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
if($_SESSION['admin']==NULL)
{
    header("Content-type:text/html;charset=utf8");
	echo "<script language=javascript>window.location='login.php';</script>";
	exit;
}
?>