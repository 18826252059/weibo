
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>后台登陆</title>
<link type="text/css" rel="stylesheet" href="styles/reset.css">
<link type="text/css" rel="stylesheet" href="styles/main.css">
<!--[if IE 6]>
<script type="text/javascript" src="../js/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript" src="../js/ie6Fixpng.js"></script>
<![endif]-->
</head>

<body>


<div class="loginBox">
	<div class="login_cont">
	<form action="doLogin.php" method="post">
			<ul class="login">
				<li class="l_tit">管理员帐号:</li>
				<li class="mb_10"><input type="text"  name="username" placeholder="请输入管理员帐号"class="login_input user_icon"></li>
				<li class="l_tit">密码:</li>
				<li class="mb_10"><input type="password"  name="password" class="login_input password_icon"></li>
				<li class="l_tit">验证码:</li>
				<li class="mb_10"><input type="text"  name="verify" class="login_input password_icon"></li>
				<li class="mb_10"><img id="captcha_img" src="./getVerify.php?r=<?php echo rand()?>" alt="" />
					<a  href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='./getVerify.php?' +
             'r='+Math.random()" >看不清，换一个?</a></li>
				<li class="autoLogin"><input type="checkbox" id="a1" class="checked" name="autoFlag" value="1"><label for="a1">自动登陆(一周内自动登陆)</label></li>
				<li><input type="submit" value="" class="login_btn"></li>
			</ul>
		</form>
	</div>
</div>


</body>
</html>
