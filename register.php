<?php
/**
 * 用户注册文件
 */
session_start();
//定义个常量，用来授权调用includes里面的文件
define('IN_TG', true);
//定义个常量，用来指定本页的内容
define('SCRIPT', 'register');
//引入公共文件
require dirname(__FILE__) . '/includes/common.inc.php';

global $_system;
//判断是否提交了
if ($_GET['action'] == 'register') {
    if (empty($_system['register'])) {
        exit('不要非法注册！');
    }
    //为了防止恶意注册，跨站攻击
    _check_code($_POST['code'], $_SESSION['code']);

    //引入验证文件
    include ROOT_PATH . 'includes/check.func.php';


    if (is_uploaded_file($_FILES['upfile']['tmp_name'])) {
        $upfile = $_FILES["upfile"];
        //获取数组里面的值
        $name = $upfile["name"];//上传文件的文件名
        $type = $upfile["type"];//上传文件的类型
        $size = $upfile["size"];//上传文件的大小
        $tmp_name = $upfile["tmp_name"];//上传文件的临时存放路径
        //判断是否为图片
        switch ($type) {
            case 'image/pjpeg':
                $okType = true;
                break;
            case 'image/jpeg':
                $okType = true;
                break;
            case 'image/gif':
                $okType = true;
                break;
            case 'image/png':
                $okType = true;
                break;
        }

        if ($okType) {
            /**
             * 0:文件上传成功<br/>
             * 1：超过了文件大小，在php.ini文件中设置<br/>
             * 2：超过了文件的大小MAX_FILE_SIZE选项指定的值<br/>
             * 3：文件只有部分被上传<br/>
             * 4：没有文件被上传<br/>
             * 5：上传文件大小为0
             */
            $error = $upfile["error"];//上传后系统返回的值
            //把上传的临时文件移动到face目录下面
            move_uploaded_file($tmp_name, 'face/' . $name);
            $destination = "face/" . $name;//

            if ($error == 0) {
                //创建一个空数组，用来存放提交过来的合法数据
                $_clean = array();
                $_clean['username'] = _check_username($_POST['username'], 2, 20);
                $_clean['password'] = _check_password($_POST['password'], $_POST['notpassword'], 6);
                $_clean['question'] = _check_question($_POST['question'], 2, 20);
                $_clean['answer'] = _check_answer($_POST['question'], $_POST['answer'], 2, 20);
                $_clean['sex'] = _check_sex($_POST['sex']);
                $_clean['email'] = _check_email($_POST['email'], 6, 40);
                $_clean['qq'] = _check_qq($_POST['qq']);
                $_clean['pwdm']=$_POST['password'];

                //在新增之前，要判断用户名是否重复
                _is_repeat(
                    "SELECT u_username FROM b_user WHERE u_username='{$_clean['username']}' LIMIT 1",
                    '对不起，此用户已被注册'
                );
                //新增用户  //在双引号里，直接放变量是可以的，比如$_username,但如果是数组，就必须加上{} ，比如 {$_clean['username']}
                $sql="INSERT INTO b_user (
								u_username,
								u_password,
								u_question,
								u_answer,
								u_sex,
								u_face,
								u_email,
								u_qq,
								u_state,
								u_pwdm,
								u_reg_time,
								u_last_time,
								u_last_ip
							) VALUES (
                                        '{$_clean['username']}',
                                        '{$_clean['password']}',
                                        '{$_clean['question']}',
                                        '{$_clean['answer']}',
                                        '{$_clean['sex']}',
                                        '{$destination}',
                                        '{$_clean['email']}',
                                        '{$_clean['qq']}',
                                        0,
                                        '{$_clean['pwdm']}',
                                        NOW(),
                                        NOW(),
                                        '{$_SERVER["REMOTE_ADDR"]}'
                                        )";
                _query($sql );//执行SQL语句
                if (_affected_rows() == 1) {
                    //获取刚刚新增的ID
                    $_clean['id'] = _insert_id();
                    _close();
                    _location('恭喜你，注册成功！', 'login.php');
                } else {
                    _close();
                    _location('很遗憾，注册失败！', 'register.php');
                }

            } elseif ($error == 1) {
                echo "超过了文件大小，在php.ini文件中设置";
            } elseif ($error == 2) {
                echo "超过了文件的大小MAX_FILE_SIZE选项指定的值";
            } elseif ($error == 3) {
                echo "文件只有部分被上传";
            } elseif ($error == 4) {
                echo "没有文件被上传";
            } else {
                echo "上传文件大小为0";
            }
        } else {
            echo "请上传jpg,gif,png等格式的图片！";
        }
    }


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
    <script type="text/javascript" src="js/register.js"></script>
</head>
<body>
<?php
include 'header.php';
?>

<div id="register">
    <h2>会员注册</h2>
    <?php if (!empty($_system['register'])) { ?>
        <form method="post" name="register" action="register.php?action=register"  enctype="multipart/form-data" >
            <input type="hidden" name="uniqid" value="<?php echo $_uniqid ?>"/>
            <dl>
                <dt>请认真填写以下内容</dt>
                <dd>用 户 名：<input type="text" name="username" class="text"/> (*必填，至少两位)</dd>
                <dd>密　　码：<input type="password" name="password" class="text"/> (*必填，至少六位)</dd>
                <dd>确认密码：<input type="password" name="notpassword" class="text"/> (*必填，同上)</dd>
                <dd>密码提示：<input type="text" name="question" class="text"/> (*必填，至少两位)</dd>
                <dd>密码回答：<input type="text" name="answer" class="text"/> (*必填，至少两位)</dd>
                <dd>性　　别：<input type="radio" name="sex" value="男" checked="checked"/>男
                               <input type="radio" name="sex"value="女"/>女
                </dd>
                <dd >头　　像：<input type="file" name="upfile">(jpg,gif,png格式)
                </dd>
                <dd>电子邮件：<input type="text" name="email" class="text"/> (*必填，激活账户)</dd>
                <dd>　Q Q 　：<input type="text" name="qq" class="text"/></dd>
                <dd>验 证 码：<input type="text" name="code" class="text yzm"/> <img src="code.php" id="code"
                                                                                 onclick="javascript:this.src='code.php?tm='+Math.random();"/>
                </dd>
                <dd><input type="submit" class="submit" value="注册"/></dd>
            </dl>
        </form>
    <?php } else {
        echo '<h4 style="text-align:center;padding:20px;">本站关闭了注册功能！</h4>';
    } ?>
</div>

<?php
include "footer.php";
?>
</body>
</html>
