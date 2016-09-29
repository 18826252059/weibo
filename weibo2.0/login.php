<?php
require_once "core/admin.inc.php";
require_once "core/album.inc.php";
require_once "core/user.inc.php";
require_once "core/weibo.inc.php";
require_once "lib/commom.func.php";
require_once "lib/mysql.func.php";
require_once "lib/upload.func.php";
require_once "lib/image.func.php";
require_once "lib/page.func.php";
require_once "lib/string.func.php";
header("content-type:text/html;charset=utf-8");
session_start();
if($_SESSION['username']!=""||$_COOKIE['username']!=""){
    alertMes("你之前已经登录过了，可以直接登录","index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Tell</title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
    <link href="css/register.css" rel="stylesheet">
</head>

<body>

<div class="container">

    <form  id="loginform" class="form-signin" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Nick name</label>
        <input id="username" type="text" name="username" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input id="password" type="password"  name="password" class="form-control" placeholder="Password" required>
        <div class="item_content input-group">

            <input id="captch" class="form-control" type="text" placeholder="验证码" name="captcha" style="width: 80px;"/>
            <img id="captcha_img" border="1" src="./captcha.php?r=<?php echo rand()?>" alt="">
            <a  href="javascript:void(0)" onclick="document.getElementById('captcha_img').src='./captcha.php?' +
             'r='+Math.random()" >换一个?</a>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="isSave" value="true"> Remember me
            </label>
            <span>·</span>
            <a href="forgetPassword.php">忘记密码</a>
        </div>
        <button id="login" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>

    <form class="form-register" method="post" action="doAction.php?act=reg" enctype="multipart/form-data" onsubmit="return checkmyForm();">
        <h2 class="form-register-heading">新来Tell?注册</h2>
        <label for="registerNickName" class="sr-only">Nick name</label>
        <input name="username" type="text" id="inputNickName" class="form-control" placeholder="Nick name" required autofocus>
        <label for="registerEmail" class="sr-only">Email address</label>
        <input name="email" type="text" id="registerEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="registerPassword" class="sr-only">Password</label>
        <input name="password" type="password" id="registerPassword" class="form-control" placeholder="Password" required>

        <div class="reg_content input-group">

            <input id="captch" class="form-control" type="text" placeholder="验证码" style="width: 80px;"/>
            <button id="getCaptcha" class="btn btn-info" type="button">获取邮箱验证码</button>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="register">注册</button>
    </form>
</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="res/js/jquery/jquery-1.9.1.min.js"></script>
<script src="res/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
<script>
var checkcaptcha=0;
$(function(){

        $('#login').click(
            function () {
                $.ajax({
                    type : "POST",
                    url:"doAction2.php?act=login",
                    dataType : "json",
                    data :$('#loginform').serialize(),
                    success :function(response){
                        if(response.errno==0){
                            alert(response.mes);
                            location.href="index.php";
                        }else{
                            alert(response.mes);
                        }
                    },
                    error:function(error){alert('error 是'+error);}
                });
            });

            $('#getCaptcha').click(function(){
                       /* alert("11111"+$('#user').val().trim()+$('#email').val());*/
                        $.ajax({
                            type : "GET",
                            url:'phpmailer.php',
                            dataType : "json",
                            data :{username:$('#inputNickName').val().trim(),mail:$('#registerEmail').val()},
                            success :function(response){
                                if(response.errno==1){
                                    alert(response.mes);
                                    var i = 60;
                                    var mytime=setInterval(function(){
                                        i -= 1;
                                        $('#getCaptcha').html("请到邮箱查看验证码("+i+" s)");
                                        $('#getCaptcha').attr('disabled','disabled');
                                        if(i==0){
                                            $('#getCaptcha').val("获取邮箱验证码");
                                            $('#getCaptcha').removeAttr("disabled");
                                            clearInterval(mytime);
                                        }
                                    },1000);
                                    checkcaptcha=response.captcha;
                                }else{
                                    alert(response.mes);
                                }
                            },
                            error:function(){alert('error')}
                        });

                        }
                    );
            });
            function checkmyForm(){
                    if($('#inputNickName').val().trim()==""){
                        alert("用户名不能为空！");
                        return false;
                    }else if($('#registerPassword').val().trim().length<6){
                        alert("密码格式错误！");
                        return false;
                    }else if($('#captcha').val().trim()==""){
                        alert("请输入邮箱验证码！");
                        return false;
                    }else if($('#captcha').val().trim()!=checkcaptcha){
                        alert("验证码不正确，请重新输入！");
                        return false;
                    }
//                    else if($('#face').val()==""){
//                        alert("请上传头像！");
//                        return false;
//                    }else if($('#user_tip').html()=="该用户名已被注册!"){
//                        alert("该用户名已被注册！");
//                        return false;
//                    }
                    return true;
                }
</script>
</body>
</html>
