<div class="nav-navicon admin-main admin-sidebar">
    <div class="sideMenu am-icon-dashboard" style="color:#aeb2b7; margin: 10px 0 0 0;"> 欢迎系统管理员：<?php echo $_SESSION['admin']?></div>
    <div class="sideMenu">
        <h3 class="am-icon-users"><em></em> <a href="#">会员管理</a></h3>
        <ul>
            <li><a href="userLst.php">会员列表 </a></li>
        </ul>
        <h3 class="am-icon-list"><em></em> <a href="#">文章管理</a></h3>
        <ul>
            <li><a href="articleLst.php">文章列表 </a></li>
            <li><a href="tagLst.php">标签列表 </a></li>
        </ul>
        <h3 class="am-icon-gears"><em></em> <a href="#">系统设置</a></h3>
        <ul>
            <li><a href="reset.php">修改密码</a></li>
        </ul>
    </div>
    <!-- sideMenu End -->

    <script type="text/javascript">
        jQuery(".sideMenu").slide({
            titCell:"h3", //鼠标触发对象
            targetCell:"ul", //与titCell一一对应，第n个titCell控制第n个targetCell的显示隐藏
            effect:"slideDown", //targetCell下拉效果
            delayTime:300 , //效果时间
            triggerTime:150, //鼠标延迟触发时间（默认150）
            defaultPlay:true,//默认是否执行效果（默认true）
            returnDefault:true //鼠标从.sideMen移走后返回默认状态（默认false）
        });
    </script>
</div>