
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>星之声微博</title>
    <link rel="stylesheet" href="style/register_style.css">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery-1.12.3.js"></script>
    <script src="js/forgetPasswordJS.js"></script>

</head>
<body>
<div class="login_top_bg">
    <img src="img/login_top_bg.jpg" >
</div>
<form method="post" action="doChangePassword.php" enctype="multipart/form-data" ">
    <div class="main">

        <h2>找回密码</h2>
        <div class="item_content">
            <label for="user">账号：</label>
            <input type="text" name="username" id="user" onfocus=" userTip()" onblur="check_userTip()" placeholder="请输入账号">
            <div class="tipArea"><img id="user_tip_img" ><span id="user_tip"></span></div>
        </div>
        <div class="item_content">
            <label for="">邮箱：</label>
            <input type="email" id="email" name="email" placeholder="请输入邮箱">
            <input class="btn btn-info" type="button" id="getCaptcha" value="获取邮箱验证码">
        </div>
        <div class="item_content">
            <label for="">验证码：</label>
            <input type="text" id="captcha"  placeholder="请输入邮箱验证码">
        </div>
        <div class="item_content">
            <label for="">新密码：</label>
            <input type="password" id="password" name="password" onfocus=" passwordTip()" onblur="check_passwordTip()" placeholder="请输入新密码">
            <div class="tipArea"><img id="password_tip_img" ><span id="password_tip"></span></div>
        </div>
        <div class="item_content">
            <label ></label>
            <span style="display:inline-block;width: 250px;text-align: left"><input class="btn btn-info btn-lg" type="button" id="changePassword" value="修改密码"></span>
        </div>
    </div>
</form>
</body>
<script>
    var checkcaptcha=0;
    $(function(){
        $('#getCaptcha').click(function(){
                $.ajax({
                    type : "GET",
                    url:'phpmailer.php',
                    dataType : "json",
                    data :{username:$('#user').val().trim(),mail:$('#email').val()},
                    success :function(response){
                        if(response.errno==1){
                            alert(response.mes);
                            var i = 60;
                            var mytime=setInterval(function(){
                                i -= 1;
                                $('#getCaptcha').val("请到邮箱查看验证码,还剩"+i+"秒");
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

        $('#changePassword').click(function(){
            if($('#user').val().trim()==""){
                alert("用户名不能为空！");
                return false;
            }else if($('#captcha').val().trim()==""){
                alert("请输入邮箱验证码！");
                return false;
            }else if($('#captcha').val().trim()!=checkcaptcha){
                alert("验证码不正确，请重新输入！");
                return false;
            }else if($('#password').val().trim().length<6){
                alert("密码格式错误！");
                return false;
            } else{
                $.ajax({
                    type : "POST",
                    url:'doAction2.php?act=changePassword',
                    dataType : "json",
                    data :{username:$('#user').val().trim(),email:$('#email').val(),password:$('#password').val()},
                    success :function(response){
                        if(response.errno==0){
                            alert(response.mes);
                            location.href="login.php";
                        }else{
                            alert(response.mes);
                        }
                    },
                    error:function(){alert('error')}
                });
            }

        });
    });


</script>
</html>