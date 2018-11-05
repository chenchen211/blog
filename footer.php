<?php
//防止恶意调用
if (!defined('IN_TG')) {
	exit('Access Defined!');
}
global $_system;
_close();
?>
<div id="footer">
	<p>版权所有 翻版必究</p>
	<p>本程序由<span><?php echo $_system['webname'] ?></span>提供</p>
</div>