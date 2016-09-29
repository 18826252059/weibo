
function userTip(){
        var img=document.getElementById("user_tip_img");
        var tip=document.getElementById("user_tip");
        img.src="img/info.jpg";
        tip.style.color="#00a1d8";
        tip.innerHTML="请输入您的账号";
    }
function check_userTip(){
    var user=document.getElementById("user");
    var img=document.getElementById("user_tip_img");
    var tip=document.getElementById("user_tip");
    if(user.value.trim()==""){
        img.src="img/warn.jpg";
        tip.style.color="red";
        tip.innerHTML="账号不能为空";

    }else {

        var user = document.getElementById("user").value;
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "checkusername.php?user=" + user, true);
        xhr.onreadystatechange = function(){receiveMsg(xhr);};
        xhr.send();
    }
}

function receiveMsg(xhr){
    if (xhr.readyState == 4 && xhr.status == 200){
        if(xhr.responseText==1){
            $("#user_tip").html("");
            $("#user_tip_img").attr("src","img/right.jpg");
        }
        if(xhr.responseText==0){
            $("#user_tip").html("该用户名已被注册!");
            $("#user_tip").css("color","red");
            $("#user_tip_img").attr("src","img/warn.jpg");
        }
    }
}



function passwordTip(){
    var img=document.getElementById("password_tip_img");
    var tip=document.getElementById("password_tip");
    img.src="img/info.jpg";
    tip.style.color="#00a1d8";
    tip.innerHTML="请输入您的密码";
}

function check_passwordTip(){
    var password=document.getElementById("password");
    var img=document.getElementById("password_tip_img");
    var tip=document.getElementById("password_tip");
    if(password.value.length<6){
        img.src="img/warn.jpg";
        tip.style.color="red";
        tip.innerHTML="密码必须大于6位！";
    }else {
        img.src="img/right.jpg";
        tip.innerHTML="";

    }
}
