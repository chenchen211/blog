<?php
/**
 * 文章列表
 */
include 'check.php';
session_start();
//定义个常量，用来指定本页的内容
define('IN_TG', true);
define('SCRIPT', 'articleLst');
//引入公共文件
include '../includes/common.inc.php';

$title = "文章列表";
$flag = "am-icon-list";
$token = md5(time() . rand(1000, 9999));
$_SESSION['token'] = $token;


//执行删除
if ($_POST['action']=='del'){
    $ids=$_POST['ids'];
    $id=implode(',',$ids);//将数组拼接成字符串
    //危险操作，为了防止cookies伪造，还要比对一下会员等级
    $sql = "SELECT u_level FROM   b_user  WHERE u_username='{$_SESSION['admin']}'	LIMIT 1";
    if (!!$_rows = _fetch_array($sql)) {
        if($_rows['u_level']==1 || $_rows['u_level']==2){
            _query("DELETE FROM b_article WHERE a_id IN ({$id})");
            if (_affected_rows()) {
                _close();
                _location('删除成功', 'articleLst.php');
            } else {
                _close();
                _alert_back('删除失败');
            }
        }else {
            _alert_back('非法登录');
        }

    }

}
?>
<script type="text/javascript" src="../ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="../ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="../ueditor/lang/zh-cn/zh-cn.js"></script>
<?php
include 'head.html';
?>

<div class="am-cf admin-main">
    <?php include 'left.php' ?>

    <div class=" admin-content">
        <?php
        include 'navigation.html';
        ?>
        <?php
        $sql = "select * from b_article ";
        $re=_query($sql);
        while ($row = _fetch_array_list($re)) {
        ?>
            <div class="am-popup am-popup-inner" id="my-popups<?php echo  $row['a_id'] ;?>" style="height: auto;" >

                <div class="am-popup-hd">
                    <h4 class="am-popup-title"><?php  echo  $row['a_title'] ?></h4>
                    <span data-am-modal-close class="am-close">&times; </span>
                </div>

                <div class="am-popup-bd">
                    <form name="myform" class="am-form tjlanmu" autocomplete="off" enctype="multipart/form-data"
                          action="uJiaoFei.php" method="post" onsubmit="return check();">
                        <input type="hidden" name="act" value="add">
                        <input type="hidden" name="id" value="<?php echo $row['a_id']?>">
                        <div class="am-form-group am-cf">
                            <div class="zuo">标题：</div>
                            <div class="you">
                                <input type="text" name="uName" id="sid" value="<?php echo $row['a_title']?>" readonly>
                            </div>
                        </div>
                        <div class="am-form-group am-cf">
                            <div class="zuo">关键词：</div>
                            <div class="you">
                                <input type="text" name="uName" id="sid" value="<?php echo $row['a_keyword']?>" readonly>
                            </div>
                        </div>
                        <div class="am-form-group am-cf">
                            <div class="zuo">作者：</div>
                            <div class="you">
                                <input type="text" name="uName" id="sid" value="<?php echo $row['a_username']?>" readonly>
                            </div>
                        </div>
                        <div class="am-form-group am-cf">
                            <div class="zuo">内容：</div>
                            <div class="you">
                                <textarea name="content" class="common-textarea" id="content<?php echo $row['a_id']?>" cols="30" style="" rows="20"><?php echo $row['a_content']?></textarea>
                            </div>
                        </div>
                        <div class="am-form-group am-cf">
                            <div class="zuo">状态：</div>
                            <div class="you">
                                <?php if ($row['a_check']==1){?>
                                    <input type="text" name="uName" id="sid" value="已加密" readonly>
                                <?php }else{?>
                                    <input type="text" name="uName" id="sid" value="未加密" readonly>
                                <?php }?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script type="text/javascript">

                //实例化编辑器
                //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
                UE.getEditor('content<?php echo $row['a_id']?>',{initialFrameWidth:400,initialFrameHeight:70,});

            </script>
        <?php } ?>
        <div class="admin-biaogelist">

            <div class="listbiaoti am-cf">
                <ul class="<?php echo $flag; ?>"> <?php echo $title; ?> </ul>

                <dl class="am-icon-home" style="float: right;"> 当前位置： 首页 > <a href="#">文章列表</a></dl>
            </div>
            <form action="articleLst.php" method="post">
                <div class="am-btn-toolbars am-btn-toolbar am-kg am-cf">
                    <ul>
                        <li>
                            <div class="am-btn-group am-btn-group-xs">
                                <select data-am-selected="{btnWidth: 90, btnSize: 'sm', btnStyle: 'default'}" name="tag">
                                    <option value="">文章标签</option>
                                    <?php
                                    $sql_tag = "select * from b_tags WHERE tag_state=0";
                                    $result = _query($sql_tag);
                                    while ($rows = _fetch_array_list($result)) {
                                        ?>
                                        <option value="<?php echo $rows['tag_id'] ?>"><?php echo $rows['tag_name'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </li>


                        <li style="margin-right: 0;">
                            <span class="tubiao am-icon-calendar"></span>
                            <input type="text" class="am-form-field am-input-sm am-input-zm  am-icon-calendar"
                                   placeholder="开始日期" name="riqi"   data-am-datepicker="{theme: 'success',}" readonly/>
                        </li>
                        <li style="margin-left: -4px;">
                            <span class="tubiao am-icon-calendar"></span>
                            <input type="text" class="am-form-field am-input-sm am-input-zm  am-icon-calendar"
                                   placeholder="结束日期" name="date"  data-am-datepicker="{theme: 'success',}" readonly/>
                        </li>

                        <li style="margin-left: -10px;">
                            <div class="am-btn-group am-btn-group-xs">
                                <select data-am-selected="{btnWidth: 90, btnSize: 'sm', btnStyle: 'default'}" name="type">
                                    <option value="">文章状态</option>
                                    <option value="c">加密</option>
                                    <option value="o">未加密</option>
                                </select>
                            </div>
                        </li>
                        <li><input type="text" name="search" class="am-form-field am-input-sm am-input-xm" placeholder="关键词搜索"/></li>
                        <li>
                            <button type="submit" class="am-btn am-radius am-btn-xs am-btn-success"
                                    style="margin-top: -1px;">搜索
                            </button>
                        </li>
                    </ul>
                </div>
            </form>

            <form class="am-form am-g" action="" method="post">
                <input type="hidden" name="action" value="del">
                <table width="100%" class="am-table am-table-bordered am-table-radius am-table-striped">
                    <thead>
                    <tr class="am-success">
                        <th width="5%"><input type="checkbox" name="chkall" id="all"/></th>
                        <th class="table-id">ID</th>
                        <th class="table-title">标题</th>
                        <th class="table-type">类别</th>
                        <th class="table-type">发帖人</th>
                        <th class="table-date am-hide-sm-only">发帖日期</th>
                        <th width="163px" class="table-set">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    global $_pagesize, $_pagenum, $_system;
                    $type = $_POST['type'];
                    $tag = $_POST['tag'];
                    $keywords = $_POST['search'];
                    $date=$_POST['date'];
                    $riqi=$_POST['riqi'];


                    $sql = "select * from  b_article where a_reid=0 ";
                    switch ($type) {//获取是否加密
                        case 'c':
                            $sql .= " and  a_check=1  ";
                            break;
                        case 'o':
                            $sql .= " and  a_check=2 ";
                            break;
                        default:
                            break;
                    }
                  if(!empty($tag)){//获取标签
                        $sql.="and a_tagid=".$tag;
                  }else{
                        $sql .="";
                  }
                  if (!empty($date)|| !empty($riqi)){//获取日期
                        $sql.="and  a_date between   '{$riqi}' and'{$date}'";
                  }else{
                        $sql .="";
                  }
                    if (!empty($keywords)) {
                        $sql .= " and a_title like '%" . $keywords . "%'";
                    }

                    _page($sql, 10);
                    $sql .= " ORDER BY a_id DESC  LIMIT $_pagenum,$_pagesize";
                    $_result = _query($sql);
                    while ($row = _fetch_array_list($_result)) {
                        ?>
                        <tr>
                           <?php
                            if($row['a_check']==1){
                                ?>
                            <td></td>
                            <?php } else{
                           ?>
                            <td><input type="checkbox" value="<?php echo $row['a_id']?>" name="ids[]"/></td>
                            <?php } ?>
                            <td><?php echo $row['a_id'] ?></td>
                            <td><a href="#"><?php echo _title($row['a_title'],10) ?><?php if($row['a_check']==1){ echo  "(<span style='color:#ff0000'>已加密</span>)";}?></a></td>
                            <td><?php
                                $sql_tag = "select tag_name from b_tags where tag_id=" . $row['a_tagid'];
                                $tag = _fetch_array($sql_tag);
                                echo $tag['tag_name'];
                                ?></td>
                            <td class="am-hide-sm-only"><?php echo $row['a_username'] ?></td>
                            <td class="am-hide-sm-only"><?php echo $row['a_date'] ?></td>
                            <td>
                                <div class="am-btn-toolbar">
                                    <div class="am-btn-group am-btn-group-xs">
                                        <a class="am-btn am-btn-default am-btn-xs am-text-success am-round" title="查看" data-am-modal="{target: '#my-popups<?php echo  $row['a_id']?>'}" "><span class="am-icon-search"></span></a>


                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <div class="am-btn-group am-btn-group-xs">
                    <button type="submit" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除
                    </button>
                </div>

                <?php _pagings(1) ?>

                <hr/>
                <p>注：删除后不可找回</p>
            </form>


            <?php
            include 'foot.html';
            ?>

        </div>

    </div>

</div><script type="text/javascript">
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
<!--[if (gte IE 9)|!(IE)]><!-->
<script src="assets/js/amazeui.min.js"></script>
<!--<![endif]-->


</body>
</html>