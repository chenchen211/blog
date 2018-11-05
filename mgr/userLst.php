<?php
include 'check.php';
session_start();
//定义个常量，用来指定本页的内容
define('IN_TG', true);
//引入公共文件
include '../includes/common.inc.php';

$title = "用户列表";
$flag = "am-icon-users";
$token = md5(time() . rand(1000, 9999));
$_SESSION['token'] = $token;
?>

<?php
include 'head.html';
?>
<div class="am-cf admin-main">
    <?php include 'left.php' ?>
    <div class=" admin-content">
        <?php
        include 'navigation.html';
        ?>
        <div class="admin-biaogelist">
            <div class="listbiaoti am-cf">
                <form action="" method="post" autocomplete="off">
                    <ul class="<?php echo $flag; ?>"> <?php echo $title; ?> </ul>
                    <ul class="soso" style="float: left; margin-left: 20px;">
                        <p>
                            <select name="type" class="am-btn-default am-btn-sm am-selected-status"
                                    style="width: 70px;">
                                <option value="all">全部</option>
                                <option value="uname">用户名</option>
                                <option value="qq">QQ</option>
                                <option value="email">邮箱</option>
                                <option value="qi">启用</option>
                                <option value="jin">禁用</option>
                            </select>
                        </p>

                        <p class="ycfg"><input type="text" name="search" id="search" class="am-form-field am-input-sm "
                                               placeholder="请输入关键字"/></p>
                        <p>
                            <button type="submit" class="am-form-field am-btn am-btn-xs am-btn-default am-xiao"><i
                                        class="am-icon-search"></i></button>
                        </p>
                    </ul>
                    <dl class="am-icon-home" style="float: right;">当前位置： 首页 > <a
                                href="javascript:;"> <?php echo $title; ?></a></dl>

                    <!--这里打开的是新页面-->
                </form>
            </div>
            <form class="am-form am-g" action="user.php" method="post">
                <input type="hidden" value="delete" name="action">
                <table width="100%" class="am-table am-table-bordered am-table-radius am-table-striped">
                    <thead>
                    <tr class="am-success">
                        <th width="5%"><input type="checkbox" name="chkall" id="all"/></th>
                        <th width="5%" class="table-id">用户ID</th>
                        <th width="10%" class="table-date">用户性别</th>
                        <th width="20%" class="table-date">用户姓名</th>
                        <th width="10%" class="table-date">用户QQ</th>
                        <th width="20%" class="table-date">用户邮箱</th>
                        <th width="10%" class="table-date">登录时间</th>
                        <th width="20%" class="table-data">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $type = $_POST['type'];
                    $keywords = $_POST['search'];
                    switch ($type) {
                        case 'uname':
                            $where = " where u_username like '%" . $keywords . "%' ";
                            break;
                        case 'qq':
                            $where = " where u_qq like '%" . $keywords . "%' ";
                            break;
                        case 'email':
                            $where = " where u_email like '%" . $keywords . "%' ";
                            break;
                        case 'jin':
                            $where = " where u_state=1  ";
                            break;
                        case 'qi':
                            $where = " where u_state=0 ";
                            break;
                        default:
                            break;
                    }
                    $sql = "select * from  b_user   ";
                    $sql .= $where;
                    $_result = _query($sql);
                    while ($row = _fetch_array_list($_result)) {
                    ?>
                    <tr>
                        <?php
                        if ($row['u_level'] == 1 ||$row['u_level'] == 2) {
                            ?>
                            <td></td>
                        <?php } else { ?>
                            <td><input name="id[]" value="<?php echo $row['u_id'] ?>" type="checkbox"/></td>
                        <?php } ?>
                        <td class="am-hide-sm-only"><?php echo $row['u_id'] ?></td>
                        <td class="am-hide-sm-only"><?php if($row['u_sex']==0){echo "男";}else{ echo "女";} ?></td>
                        <td class="am-hide-sm-only"><?php echo $row['u_username'] ?></td>
                        <td class="am-hide-sm-only"><?php echo $row['u_qq'] ?></td>
                        <td class="am-hide-sm-only"><?php echo $row['u_email'] ?></td>
                        <td class="am-hide-sm-only"><?php echo $row['u_last_time'] ?></td>
                        <?php
                        if ($row['u_level'] == 2){
                            ?>
                            <td>[博客超级管理员]</td>
                        <?php }elseif($row['u_level'] == 1){?>
                            <td>[博客管理员][<a onclick="return confirm('确定降职吗？')"
                                           href="user.php?act=jiangzhi&id=<?php echo $row['u_id'] ?>">降职</a> ]</td>
                        <?php }else{ ?>
                        <td>
                            [<a onclick="return confirm('确定删除吗？')"
                                href="user.php?act=del&id=<?php echo $row['u_id'] ?>">删除</a> ]
                            [<a onclick="return confirm('确定修改吗？')"
                                href="editUser.php?act=edit&id=<?php echo $row['u_id'] ?>">修改</a> ]
                            [<a onclick="return confirm('确定升职吗？')"
                                href="user.php?act=shengzhi&id=<?php echo $row['u_id'] ?>">升职</a> ]
                            <?php
                            if ($row['u_state'] == 1){
                            ?>
                        [<a onclick="return confirm('确定启用吗？')"
                            href="user.php?act=qiyong&id=<?php echo $row['u_id'] ?>">已禁止</a> ]
                    <?php } else { ?>
                        [<a onclick="return confirm('确定禁止吗？')"
                            href="user.php?act=jinzhi&id=<?php echo $row['u_id'] ?>">已启用</a> ]
                        </td>
                    <?php }
                        }?>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="am-btn-group am-btn-group-xs">
                    <label for="all"></label>
                    <input type="submit" value="批删除" class="am-btn am-btn-default"/>

                </div>
                <hr/>
            </form>

            <?php include 'foot.html' ?>

        </div>

    </div>

</div>
<script type="text/javascript">
    window.onload = function () {
        var all = document.getElementById('all');
        var fm = document.getElementsByTagName('form')[1];
        all.onclick = function () {
            //form.elements获取表单内的所有表单，目前一共16个
            //checked表示已选
            for (var i = 0; i < fm.elements.length; i++) {
                if (fm.elements[i].name != 'chkall') {
                    fm.elements[i].checked = fm.chkall.checked;
                }
            }
        };
        fm.onsubmit = function () {
            if (confirm('确定要删除这批数据吗？')) {
                return true;
            }
            return false;
        };
    };
</script>
<script src="assets/js/amazeui.min.js"></script>

</body>
</html>