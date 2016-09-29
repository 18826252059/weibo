<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2016/5/15
 * Time: 14:13
 */
require_once "../lib/mysql.func.php";
require_once "../core/admin.inc.php";
require_once "../lib/commom.func.php";

header("Content-type:text/html;charset=utf-8");
session_start();
$username=$_POST['username'];
$password=$_POST['password'];
$verify=$_POST['verify'];
$verify1=$_SESSION['verify'];

if($verify==$verify1){
    $sql="select * from admin where username='{$username}' and password='{$password}'";
    $row=checkAdmin($sql);
   //var_dump($row) ;
    if($row){
        $_SESSION['adminName']=$row['username'];
        $_SESSION['adminId']=$row['id'];
        //header("location:index.php");
        alertMes("登录成功！","index.php");
    }else{
    alertMes("登录失败，重新登录","login.php");
    }
}else{
    alertMes("验证码错误！","login.php");
}