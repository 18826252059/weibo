/**
 * Created by star on 2016/6/20.
 */
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

    }
}

function passwordTip(){
    var img=document.getElementById("password_tip_img");
    var tip=document.getElementById("password_tip");
    img.src="img/info.jpg";
    tip.style.color="#00a1d8";
    tip.innerHTML="请输入您的新密码";
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