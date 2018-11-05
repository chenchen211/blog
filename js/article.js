window.onload = function () {
	code();
	var fm = document.getElementsByTagName('form')[0];

	var font = document.getElementById('font');
	var color = document.getElementById('color');
	var html = document.getElementsByTagName('html')[0];

	
	if (fm != undefined) {
		fm.onsubmit = function () {
			if (fm.title.value.length < 2 || fm.title.value.length > 40) {
				alert('标题不得小于2位或者大于40位');
				fm.title.value = ''; //清空
				fm.title.focus(); //将焦点以至表单字段
				return false;
			}
			if (fm.content.value.length < 10) {
				alert('内容不得小于10位');
				fm.content.value = ''; //清空
				fm.content.focus(); //将焦点以至表单字段
				return false;
			}
			//验证码验证
			if (fm.code.value.length != 4) {
				alert('验证码必须是4位');
				fm.code.value = ''; //清空
				fm.code.focus(); //将焦点以至表单字段
				return false;
			}
			return true;
		};
	}
	


	if (font != null) {
		html.onmouseup = function () {
			font.style.display = 'none';
			color.style.display = 'none';
		};
	}

	function content(string) {
		fm.content.value += string; 
	}
	
	if (fm != undefined) {
		fm.t.onclick = function () {
			showcolor(this.value);
		}
	}
	
	var message = document.getElementsByName('message');
	var friend = document.getElementsByName('friend');
	var flower = document.getElementsByName('flower');
	var re = document.getElementsByName('re');
	
	
	for (var i=0;i<re.length;i++) {
		re[i].onclick = function () {
			document.getElementsByTagName('form')[0].title.value = this.title;
		};
	}
	for (var i=0;i<message.length;i++) {
		message[i].onclick = function () {
			centerWindow('message.php?id='+this.title,'message',250,400);
		};
	}
	for (var i=0;i<friend.length;i++) {
		friend[i].onclick = function () {
			centerWindow('friend.php?id='+this.title,'friend',250,400);
		};
	}
	for (var i=0;i<flower.length;i++) {
		flower[i].onclick = function () {
			centerWindow('flower.php?id='+this.title,'flower',250,400);
		};
	}
};

function centerWindow(url,name,height,width) {
	var left = (screen.width - width) / 2;
	var top = (screen.height - height) / 2;
	window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left);
}

function font(size) {
	document.getElementsByTagName('form')[0].content.value += '[size='+size+'][/size]'
};

function showcolor(value) {
	document.getElementsByTagName('form')[0].content.value += '[color='+value+'][/color]'
};

