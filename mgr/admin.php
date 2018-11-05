<?php
/**
 * 通用文件
 * 
 */
session_start();
error_reporting(0);
include 'check.php';
//定义个常量，用来指定本页的内容
define('IN_TG', true);
//引入公共文件
include '../includes/common.inc.php';

$act = $_POST['act'];
/*******************修改管理员密码***************************/
if ($act == 'edit') {
        $token = $_POST['token'];//token 随机生成 令牌 防止重复提交
        if (!isset($_SESSION['token']) || $token != $_SESSION['token']) {
            _alert_back('请不要重复提交', 'reset.php');
        }
        $username = $_SESSION['admin'];
        $old = $_POST['old'];
        $new = $_POST['new'];

        //检索出是否存在用户
        $sql = "select * from  b_user where u_username='" . $username . "'and u_password='" . md5($old) . "'";
        $re = _fetch_array($sql);
        if ($re) {//存在用户 根据id改密码
                $sql_edit = "update  b_user set u_password='" . md5($new) . "' where u_id =" . $re['u_id'];
                _query($sql_edit);
                    if ( _affected_rows() > 0) {
                        _location('修改成功', 'reset.php');
                    }else{
                        _alert_back('修改失败', 'reset.php');
                    }

        } else {
            _alert_back('用户不存在或密码错误','reset.php');
        }

}
/*******************添加标签***************************/
if ($act == 'addTag') {
	$token = $_POST['token'];//token 随机生成 令牌 防止重复提交
	if (!isset($_SESSION['token']) || $token != $_SESSION['token']) {
		_alert_back('请不要重复提交', 'reset.php');
	}
	
	$name= $_POST['name'];
	

	//检索出是否存此标签
	$sql = "select tag_name from  b_tags where tag_name='" . $name . "'";
	$re = _fetch_array($sql);
	if (!$re) {//如果不存在
			$sql_add = "insert into b_tags (tag_name,tag_state) value('{$name}',1)";
			_query($sql_add);
		if ( _affected_rows() > 0) {
			_location('添加成功', 'tagLst.php');
		}else{
			_alert_back('添加失败', 'articleTag.php');
		}

	} else {
		_alert_back('该标签名已存在','articleTag.php');
	}

}
$id = $_REQUEST['id'];
/**********************删除单个标签******************************************/
if ($_GET['act'] == 'del') {
    //查看对应id下是否存在该标签
    $sql = "SELECT tag_name FROM   b_tags  WHERE tag_id='{$id}'	LIMIT 1";
    if (!!$_rows = _fetch_array($sql)) {//若存在
        $sql = "delete from  b_tags   where tag_id=" . $id;//将状态改为0
        _query($sql);
        if (_affected_rows()) {
            _close();
            _location('已删除', 'tagLst.php');
        } else {
            _close();
            _alert_back('删除失败');
        }
    } else {
        _alert_back('该标签不存在！');
    }
}

/**********************禁止或启用标签******************************************/

if($_GET['act']=='jinzhi') {
    //查看对应id下是否存在该标签
    $sql = "SELECT tag_name FROM   b_tags  WHERE tag_id='{$id}'	LIMIT 1";
    if (!!$_rows = _fetch_array($sql)) {//若存在
        $sql = "update b_tags set tag_state=1 where tag_id=" . $id;//将状态改为0
        _query($sql);
        if (_affected_rows()) {
            _close();
            _location('已禁止', 'tagLst.php');
        } else {
            _close();
            _alert_back('禁止失败！');
        }
    } else {
        _alert_back('该标签不存在！');
    }
}
if($_GET['act']=='qiyong') {
	//查看对应id下是否存在该标签
	$sql = "SELECT tag_name FROM   b_tags  WHERE tag_id='{$id}'	LIMIT 1";
	if (!!$_rows = _fetch_array($sql)) {//若存在
		$sql = "update b_tags set tag_state=0 where tag_id=" . $id;//将状态改为0
		_query($sql);
		if (_affected_rows()) {
			_close();
			_location('已启用', 'tagLst.php');
		} else {
			_close();
			_alert_back('启用失败');
		}
	} else {
		_alert_back('该标签不存在！');
	}
}
/***********************修改标签***********************************************/
//获取指令
if ($_POST['act']=="editTag"){
    $token = $_POST['token'];
    if (!isset($_SESSION['token']) || $token != $_SESSION['token']) {
        _alert_back('请不要重复提交');
    }
    $id = $_POST['id'];
    //根据id查出该条标签
    $sql="select * from b_tags where tag_id=".$id;
    //var_dump($sql);
    if (!!$rows=_fetch_array($sql)){
        $name=$_POST['name'];
        $sql_edit = "update b_tags set tag_name='" . $name . "' where tag_id=" . $id;
        _query($sql_edit);
        if (_affected_rows() > 0) {
            _location('修改成功', 'tagLst.php');
        } else {
            _alert_back('修改失败');
        }
    }else{
        _alert_back('无效操作！');
    }
}