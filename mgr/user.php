<?php
session_start();
//定义个常量，用来指定本页的内容
define('IN_TG', true);
//引入公共文件
include '../includes/common.inc.php';


$id = $_REQUEST['id'];
/**********************删除单个会员******************************************/
if ($_GET['act'] == 'del') {
    //查看对应id下是否存在该用户
    $sql = "SELECT u_username FROM   b_user  WHERE u_id='{$id}'	LIMIT 1";
    if (!!$_rows = _fetch_array($sql)) {//若存在
        $sql = "delete from b_user where u_id=" . $id;
        _query($sql);
        if (_affected_rows()) {
            _close();
            _location('该会员已被删除', 'userLst.php');
        } else {
            _close();
            _alert_back('删除失败');
        }
    } else {
        _alert_back('该用户不存在！');
    }
}

/**********************禁止或启用或升职会员******************************************/

if($_GET['act']=='jinzhi') {
    //查看对应id下是否存在该用户
    $sql = "SELECT u_username FROM   b_user  WHERE u_id='{$id}'	LIMIT 1";
    if (!!$_rows = _fetch_array($sql)) {//若存在
        $sql = "update b_user set u_state=1 where u_id=" . $id;//将状态改为1
        _query($sql);
        if (_affected_rows()) {
            _close();
            _location('该会员已被禁止发帖', 'userLst.php');
        } else {
            _close();
            _alert_back('禁止失败');
        }
    } else {
        _alert_back('该用户不存在！');
    }
}
if($_GET['act']=='qiyong') {
    //查看对应id下是否存在该用户
    $sql = "SELECT u_username FROM   b_user  WHERE u_id='{$id}'	LIMIT 1";
    if (!!$_rows = _fetch_array($sql)) {//若存在
        $sql = "update b_user set u_state=0 where u_id=" . $id;//将状态改为0
        _query($sql);
        if (_affected_rows()) {
            _close();
            _location('该会员已启用发帖', 'userLst.php');
        } else {
            _close();
            _alert_back('启用失败');
        }
    } else {
        _alert_back('该用户不存在！');
    }
}
if($_GET['act']=='shengzhi') {
    //查看对应id下是否存在该用户 切状态不为1
    $sql = "SELECT u_username FROM   b_user  WHERE u_id='{$id}' and  u_state=0	LIMIT 1";
    if (!!$_rows = _fetch_array($sql)) {//若存在
        $sql = "update b_user set u_level=1 where u_id=" . $id;//将等级改为1
        _query($sql);
        if (_affected_rows()) {
            _close();
            _location('该会员成为管理员', 'userLst.php');
        } else {
            _close();
            _alert_back('升职失败');
        }
    } else {
        _alert_back('该用户存在异常！');
    }
}

if($_GET['act']=='jiangzhi') {
    //查看对应id下是否存在该用户
    $sql = "SELECT u_username FROM   b_user  WHERE u_id='{$id}'and  u_state=0	LIMIT 1";
    if (!!$_rows = _fetch_array($sql)) {//若存在
        $sql = "update b_user set u_level=0 where u_id=" . $id;//将等级改为1
        _query($sql);
        if (_affected_rows()) {
            _close();
            _location('该管理员已被降职', 'userLst.php');
        } else {
            _close();
            _alert_back('降职失败');
        }
    } else {
        _alert_back('该用户存在异常！');
    }
}
/**********************批删除会员******************************************/

if($_POST['action']=="delete") {

    $_clean = array();
    $id = $_POST['id'];
    if(empty($id)){
        $id = array(0);
    }
    $_clean['ids'] = is_array($id) ? implode(',', $id) : $id;

    //危险操作，为了防止cookies伪造，还要比对一下会员等级
    $sql = "SELECT u_level FROM   b_user  WHERE u_username='{$_SESSION['admin']}'	LIMIT 1";
    if (!!$_rows = _fetch_array($sql)) {
        if($_rows['u_level']==1 || $_rows['u_level']==2){
            _query("DELETE FROM b_user WHERE u_id IN ({$_clean['ids']})");
            if (_affected_rows()) {
                _close();
                _location('会员删除成功', 'userLst.php');
            } else {
                _close();
                _alert_back('会员删除失败');
            }
        }else {
            _alert_back('非法登录');
        }

    }
}
/***************************修改会员*********************************************/
if ($_POST['act'] == 'edit') {
    $token = $_POST['token'];
    if (!isset($_SESSION['token']) || $token != $_SESSION['token']) {
        $msg->main('请不要重复提交', 'editUser.php?id=' . $id);
    }
    $id = $_POST['id'];
    $sql_id = "select * from b_user where u_id= '" . $id . "'";
    if (!!$row= _fetch_array($sql_id)) {
        $name = $_POST['name'];
        $pwd = $_POST['pwd'];
        $sex = $_POST['sex'];
        $qq = $_POST['qq'];
        $email= $_POST['email'];
        $question = $_POST['question'];
        $answer = $_POST['answer'];

        $sql_edit = "update b_user set u_username='" . $name . "', 
                                        u_password='" . md5($pwd) . "',
                                        u_pwdm='".$pwd."',
                                        u_sex='".$sex."',
                                        u_qq='".$qq."', 
                                        u_email='".$email."',
                                        u_question='".$question."',
                                        u_answer='".$answer."',
                                        u_modify=NOW()
                                          
                    where u_id=" . $id;
        _query($sql_edit);
        if (_affected_rows() > 0) {
            _location('修改成功', 'userLst.php');
        } else {
            _alert_back('修改失败', 'userLst.php');
        }
    } else {
        _alert_back('操作无效！', 'userLst.php');
    }

}
?>