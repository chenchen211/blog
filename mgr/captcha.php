﻿<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26 0026
 * Time: 下午 2:42
 */
session_start();
require '../includes/ValidateCode.class.php';  //先把类包含进来，实际路径根据实际情况进行修改。

$_vc = new ValidateCode();      //实例化一个对象
$_vc->doimg();

$_SESSION['code'] = $_vc->getCode();//验证码保存到SESSION中

?>