<?php
include 'check.php';
session_start();
//定义个常量，用来指定本页的内容
define('IN_TG', true);
//引入公共文件
include '../includes/common.inc.php';

$title = "标签列表";
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
                     <ul class="<?php echo $flag; ?>"> <?php echo $title; ?>
                        <a href="articleTag.php"> <button type="button" class="am-btn am-btn-danger am-round am-btn-xs am-icon-plus"> 添加标签</button></a>
                    </ul>
                
                    <dl class="am-icon-home" style="float: right;">当前位置： 首页 > <a
                                href="javascript:;"> <?php echo $title; ?></a></dl>

                    <!--这里打开的是新页面-->
                </form>
            </div>
            <form class="am-form am-g" action="admin.php?action=delete" method="post">
                <table width="100%" class="am-table am-table-bordered am-table-radius am-table-striped">
                    <thead>
                    <tr class="am-success">
                        <th width="5%"><input type="checkbox" name="chkall" id="all"/></th>
                        <th class="table-id">ID</th>
                        <th class="table-title">标签名称</th>
                        <th  class="table-set">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    
                    $sql = "select * from  b_tags   ";
                    $sql .= $where;
                    $_result = _query($sql);
                    while ($row = _fetch_array_list($_result)) {
                    ?>
                    <tr>
                    	<td><input type="checkbox"/></td>
                        <td class="am-hide-sm-only"><?php echo $row['tag_id'] ?></td>
                       
                        <td class="am-hide-sm-only"><?php echo $row['tag_name'] ?></td>
                     
                        <td>
                            [<a onclick="return confirm('确定删除吗？')"
                                href="admin.php?act=del&id=<?php echo $row['tag_id'] ?>">删除</a> ]
                            [<a onclick="return confirm('确定修改吗？')"
                                href="editTag.php?act=edit&id=<?php echo $row['tag_id'] ?>">修改</a> ]
                            <?php
                            if ($row['tag_state'] == 1){
                            ?>
                        [<a onclick="return confirm('确定启用吗？')"
                            href="admin.php?act=qiyong&id=<?php echo $row['tag_id'] ?>">已禁止</a> ]
                    <?php } else { ?>
                        [<a onclick="return confirm('确定禁止吗？')"
                            href="admin.php?act=jinzhi&id=<?php echo $row['tag_id'] ?>">已启用</a> ]
                        </td>
                    <?php
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