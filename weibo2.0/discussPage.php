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
session_start();
checkuserLogined();
/*$cates=getAllCate();
print_r($cates);*/
//echo "该用户的id：".$_SESSION['uid'];
/*if(!($cates&&is_array($cates))){
    alertMes("不好意思，网站维护中","http://www.bilibili.com/");
}*/
$myid=$_SESSION['uid'];
$myname=$_SESSION['username'];
if(isset($_COOKIE['username'])){
    $myid=$_COOKIE['uid'];
    $myname=$_COOKIE['username'];
}

//echo $myid;
$uid=$_GET['uid'];
$blogId=$_GET['blogId'];
//echo $uid;
//echo $blogId;
$link=mysqli_connect( 'localhost','root','');
mysqli_select_db($link,'microblog');
mysqli_query($link, "SET NAMES 'utf8'");
$sql="select p.id,p.username,p.sex,p.face,c.followNum,c.fanNum,c.blogNum from user as p join user_information c on p.id=c.uid where c.uid={$uid}";
$rows=fetchOne($link,$sql);
//print_r($rows);
$sql2="select id,uid,time,text,collectNum,tranNum,discussNum,approveNum,isTran,tranBlogId from blog  where id={$blogId}";
$row2=fetchOne($link,$sql2);
//$num=getResultNum($link,$sql2);
//print_r($row2);
//$sql3="select b.id from blog as b join album a on b.id="
$sql3="select uid from fan where fid={$myid}";
$rows3=fetchAll($link,$sql3);
$flag=true;
if($rows3){
    foreach($rows3 as $row3){
        if($row3['uid']==$uid){
            $flag=false;
        }
    }
}
echo "<br/>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>星之声微博</title>

    <script src="js/indexJS.js"></script>
   <!-- <link href="style/index_style.css" rel="stylesheet">-->
     <!--<link href="style/homepage.css" rel="stylesheet">-->
    <script type="text/javascript" charset="utf-8" src="plugins/kindeditor/kindeditor.js"></script>
    <script type="text/javascript" charset="utf-8" src="plugins/kindeditor/lang/zh_CN.js"></script>
    <script type="text/javascript" src="admin/scripts/jquery-1.6.4.js"></script>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="bootstrap/js/jquery-1.11.1.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script>
        KindEditor.ready(function(K) {
            window.editor = K.create('#editor_id');
        });
        $(document).ready(function(){
            $("#selectFileBtn").click(function(){
                $fileField = $('<input type="file" name="thumbs2[]"/>');
                $fileField.hide();
                $("#attachList").append($fileField);
                $fileField.trigger("click");
                $fileField.change(function(){
                    $path = $(this).val();
                    $filename = $path.substring($path.lastIndexOf("\\")+1);
                    $attachItem = $('<div class="attachItem"><div class="left">a.gif</div><div class="right"><a href="#" title="删除附件">删除</a></div></div>');
                    $attachItem.find(".left").html($filename);
                    $("#attachList").append($attachItem);
                });
            });
            $("#attachList>.attachItem").find('a').live('click',function(obj,i){
                $(this).parents('.attachItem').prev('input').remove();
                $(this).parents('.attachItem').remove();
            });
        });
    </script>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #99d6f3;
        }
        .header {
            border: 2px solid #00a1d8;
            height: 48px;
            background: #fafffa;
        }
        .header_content {
            margin-left: auto;
            margin-right: auto;
            width: 1240px;
            *zoom: 1;
        }
        .header_content:before,
        .header_content:after {
            display: table;
            content: "";
        }
        .header_content:after {
            clear: both;
        }
        .header .logo {
            height: 40px;
            width: 77px;
            background: url("img/logo.jpg") left top no-repeat;
            float: left;
            display: inline;
        }
        .header .logo a {
            display: block;
            height: 100%;
            text-indent: -100em;
        }
        .header .search {
            float: left;
            display: inline;
            width: 568px;
            height: 28px;
            margin: 9px 25px;
        }
        .header .search_input {
            width: 487px;
            float: left;
            display: inline;
            height: 28px;
            padding: 0 8px;
            border: solid 1px #ccc;
            background-color: #f2f2f5;
        }
        .header .search .search_btn {
            height: 28px;
            width: 52px;
            background: url("../img/search_btn.jpg") no-repeat left center #ededef;
            text-align: right;
            margin-left: 10px;
            padding-right: 2px;
        }
        .headerNav {
            float: left;
            display: inline;
            height: 22px;
            margin: 10px 0;
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 16px;
        }
        .headerNav li {
            float: left;
            display: inline;
            margin-left: 30px;
        }
        .headerNav dt {
            background: #fff;
            width: 100px;
            display: none;
        }
        .headerNav dt a {
            margin: 0 auto;
        }
        .headerNav a {
            height: 28px;
            line-height: 28px;
            display: inline-block;
            color: #333333;
            padding-left: 26px;
            text-decoration: none;
        }
        .headerNav a:hover {
            color: #00a1d8;
        }
        .main {
            width: 1000px;
            margin-left: auto;
            margin-right: auto;
            background-color: #7fc3e2;
            padding-top: 16px;
            min-height: 90%;
            *zoom: 1;
        }
        .main:before,
        .main:after {
            display: table;
            content: "";
        }
        .main:after {
            clear: both;
        }
        .main {
            width: 1000px;
            margin-left: auto;
            margin-right: auto;
            background-color: #7fc3e2;
            padding-top: 5px;
            *zoom: 1;
        }
        .main:before,
        .main:after {
            display: table;
            content: "";
        }
        .main:after {
            clear: both;
        }
        .leftArea {
            width: 750px;
            float: left;
            display: inline;
        }
        .rightArea {
            width: 230px;
            float: right;
            display: inline;
            margin-right: 10px;
        }
        .leftArea_cont {
            width: 150px;
            float: left;
            display: inline;
        }
        .leftCont {
            width: 600px;
            float: right;
            display: inline;
        }
        .nav {
            font-size: 12px;
            font-family: "Microsoft Yahei", "微软雅黑";
            line-height: 34px;
        }
        .nav a {
            color: #FFF;
            height: 50px;
            display: inline-block;
            padding-left: 15px;
            text-decoration: none;
        }
        .nav li {
            list-style: none;
        }
        .nav li:hover {
            /*background-color: #98cee7;*/
            background-color:#5bc0de
        }
        .nav_line {
            height: 1px;
            background-color: #98cee7 ;
            magin: 12px 0;
        }
        .nav .strong_font {
            font-weight: bold;
        }
        .releaseBox {
            height: 160px;
            padding-top: 15px;
            background-color: #fff;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
        }
        .releaseBox .release_top {
            padding: 0 12px;
            *zoom: 1;
            margin-bottom: 8px ;
        }
        .releaseBox .release_top:before,
        .releaseBox .release_top:after {
            display: table;
            content: "";
        }
        .releaseBox .release_top:after {
            clear: both;
        }
        .releaseBox .release_text {
            width: 194px;
            height: 28px;
            background: url("img/discuss.jpg") left center no-repeat;
            text-indent: -99em;
            float: left;
            display: inline;
        }
        .releaseBox .release_links {
            float: right;
            display: inline;
            height: 18px;
            line-height: 18px;
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 12px;
            margin-top: 5px;
        }
        .releaseBox .release_links a {
            color: #32a2d5;
        }
        .releaseBox .release_links a:hover {
            text-decoration: underline;
        }
        .releaseBox .release_area {
            height: 78px;
            border: solid 2px #ccc;
            margin: 0 10px;
            margin-bottom: 5px;
        }
        .releaseBox .release_area textarea {
            width: 100%;
            height: 100%;
            line-height: 20px;
            resize: none;
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 12px;
        }
        .releaseBox .release_select {
            *zoom: 1;
        }
        .releaseBox .release_select:before,
        .releaseBox .release_select:after {
            display: table;
            content: "";
        }
        .releaseBox .release_select:after {
            clear: both;
        }
        .releaseBox .release_texts {
            padding-top: 6px;
            float: left;
            display: inline;
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 15px;
            padding: 0 10px;
        }
        .releaseBox .release_texts li {
            float: left;
            display: inline;
            margin-right: 20px;
            margin-top: 10px;
        }
        .releaseBox .release_texts a {
            height: 21px;
            color: #333333;
            padding-left: 5px;
            display: inline-block;
            text-decoration: none;
        }
        .releaseBox .release_texts a:hover {
            color: #00a1d8;
        }
        .releaseBox .release_btn {
            float: right;
            display: inline;
            margin-right: 12px;
        }
        .releaseBox .u_release_btn {
            width: 82px;
            height: 30px;
            background-color: #99d6f3;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 14px;
            color: #fff;
        }
        .showList {
            margin-top: 20px;
            padding: 0 0;
            width: 600px;
            background-color: #fff;
        }
        .showList .item {
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 12px;
            margin-bottom: 10px;
            padding-top: 10px;
            position: relative;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
        }
        .showList .portrait {
            float: left;
            display: inline;
            width: 50px;
            margin-left: 15px;
        }
        .showList .portrait img {
            width: 50px;
            height: 50px;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
        }
        .showList .content {
            float: right;
            width: 520px;
            line-height: 30px;
            font-size: 14px;
            margin-left: 5px;
        }
        .showList .content a {
            text-decoration: none;
            font-size: 16px;
            color: #32a2d5;
        }
        .showList .content div {
            color: gray;
        }
        .showList .operation {
            clear: both;
            height: 30px;
            border-top: 1px solid #ccc;
            width: 600px;
        }
        .showList .operation ul {
            margin: 5px 0;
        }
        .showList .operation li {
            float: left;
            height: 20px;
            list-style: none;
            width: 149px;
            text-align: center;
            border-right: 1px solid #ccc;
        }
        .showList .operation li a {
            text-decoration: none;
            color: #8f8080;
        }
        .showList .operation li a:hover {
            color: #32a2d5;
        }
        .userCard {
            height: 165px;
            background-color: #fff;
        }
        .userCard .userCardBG {
            background-color: #99d6f3;
            height: 70px;
        }
        .userHead {
            vertical-align: top;
            text-align: center;
        }
        .userHead img {
            height: 58px;
            width: 58px;
            text-align: center;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            margin-top: -65px;
        }
        .userHead h3 {
            font-size: 14px;
        }
        .userLinks {
            height: 33px;
            *zoom: 1;
            width: 195px;
        }
        .userLinks:before,
        .userLinks:after {
            display: table;
            content: "";
        }
        .userLinks:after {
            clear: both;
        }
        .userLinks .last {
            border-right: none;
        }
        .userLinks li {
            float: left;
            display: inline;
            width: 65px;
            border-right: solid 1px #ccc;
            margin-right: -1px;
            height: 33px;
            text-align: center;
            font-size: 12px;
        }
        .userLinks li a {
            text-decoration: none;
        }
        .userLinks strong {
            display: inline-block;
            width: 65px;
            font-size: 16px;
            font-family: "Arial";
        }
        .common_border_bg {
            background-color: #fff;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
        }
        .hotBox {
            background-color: #fff;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
        }
        .hot_tittle {
            height: 38px;
            line-height: 38px;
            font-family: "Microsoft Yahei", "微软雅黑";
            padding: 0 10px;
            border-bottom: solid 1px #ccc;
        }
        .hot_tittle h3 {
            float: left;
            display: inline;
            font-size: 14px;
        }
        .hot_tittle a {
            float: right;
            display: inline;
            font-size: 12px;
            color: #333333;
            text-decoration: none;
        }
        .hot_tittle a:hover {
            color: #32a2d5;
        }
        .hot_list {
            padding-top: 12px;
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 12px;
            padding: 12px 10px 0 ;
        }
        .hot_list li {
            height: 30px;
            line-height: 30px;
            list-style: none;
        }
        .hot_list a {
            color: #333333;
            text-decoration: none;
            float: left;
            display: inline;
        }
        .hot_list a:hover {
            color: #32a2d5;
        }
        .hot_list span {
            float: right;
            display: inline;
        }
        .hot_more {
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 12px;
            background-color: #fff;
            height: 38px;
            line-height: 38px;
            text-align: center;
            border-top: 1px solid #ccc;
        }
        .hot_more a {
            color: #333333;
            text-decoration: none;
        }
        .hot_more a:hover {
            color: #32a2d5;
        }
        .friBox {
            background-color: #fff;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
            margin-top: 20px;
        }
        .fri_tittle {
            height: 38px;
            line-height: 38px;
            font-family: "Microsoft Yahei", "微软雅黑";
            padding: 0 10px;
            border-bottom: solid 1px #ccc;
        }
        .fri_tittle h3 {
            float: left;
            display: inline;
            font-size: 14px;
        }
        .fri_tittle a {
            float: right;
            display: inline;
            font-size: 12px;
            color: #333333;
            text-decoration: none;
        }
        .fri_tittle a:hover {
            color: #32a2d5;
        }
        .fri_list {
            padding-top: 12px;
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 12px;
            padding: 12px 10px 0 ;
        }
        .fri_list li {
            height: 30px;
            line-height: 30px;
            list-style: none;
        }
        .fri_list a {
            color: #333333;
            text-decoration: none;
            float: right;
            display: inline;
        }
        .fri_list a:hover {
            color: #32a2d5;
            text-decoration: underline;
        }
        .fri_list span {
            float: left;
            display: inline;
        }
        .fri_more {
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 12px;
            background-color: #fff;
            height: 38px;
            line-height: 38px;
            text-align: center;
            border-top: 1px solid #ccc;
        }
        .fri_more a {
            color: #333333;
            text-decoration: none;
        }
        .fri_more a:hover {
            color: #32a2d5;
        }

        .btn-info{
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #fff;
            background-color: #5bc0de;
            border-color: #46b8da;
        }

        .btn-info:hover{
            background-color: #31b0d5;
        }

        .btn-danger{
            background-image: -webkit-linear-gradient(top,#d9534f 0,#c12e2a 100%);
            background-image: -o-linear-gradient(top,#d9534f 0,#c12e2a 100%);
            background-image: -webkit-gradient(linear,left top,left bottom,from(#d9534f),to(#c12e2a));
            background-image: linear-gradient(to bottom,#d9534f 0,#c12e2a 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffd9534f', endColorstr='#ffc12e2a', GradientType=0);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            background-repeat: repeat-x;
            border-color: #b92c28;
            text-shadow: 0 -1px 0 rgba(0,0,0,.2);
            -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.15),0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.15),0 1px 1px rgba(0,0,0,.075);
            color: #fff;
            background-color: #d9534f;
            border-color: #d43f3a;
            display: inline-block;
            margin-bottom: 0;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            white-space: nowrap;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            border-radius: 4px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

        }
        .btn-danger:hover{
            background-color: #c93a36;
        }
        * {
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #99d6f3;
        }
        .header {
            border: 2px solid #00a1d8;
            height: 48px;
            background: #fafffa;
        }
        .header_content {
            margin-left: auto;
            margin-right: auto;
            width: 1240px;
            *zoom: 1;
        }
        .header_content:before,
        .header_content:after {
            display: table;
            content: "";
        }
        .header_content:after {
            clear: both;
        }
        .header .logo {
            height: 40px;
            width: 77px;
            background: url("img/logo.jpg") left top no-repeat;
            float: left;
            display: inline;
        }
        .header .logo a {
            display: block;
            height: 100%;
            text-indent: -100em;
        }
        .header .search {
            float: left;
            display: inline;
            width: 568px;
            height: 28px;
            margin: 9px 25px;
        }
        .header .search_input {
            width: 487px;
            float: left;
            display: inline;
            height: 28px;
            padding: 0 8px;
            border: solid 1px #ccc;
            background-color: #f2f2f5;
        }
        .header .search .search_btn {
            height: 28px;
            width: 52px;
            background: url("img/search_btn.jpg") no-repeat left center #ededef;
            text-align: right;
            margin-left: 10px;
            padding-right: 2px;
        }
        .headerNav {
            float: left;
            display: inline;
            height: 22px;
            margin: 10px 0;
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 16px;
        }
        .headerNav li {
            float: left;
            display: inline;
            margin-left: 30px;
        }
        .headerNav a {
            height: 28px;
            line-height: 28px;
            display: inline-block;
            color: #333333;
            padding-left: 26px;
            text-decoration: none;
        }
        .headerNav a:hover {
            color: #00a1d8;
        }
        .main {
            width: 980px;
            margin-left: auto;
            margin-right: auto;
            background-color: #7fc3e2;
            margin-top: 20px;
            *zoom: 1;
        }
        .main:before,
        .main:after {
            display: table;
            content: "";
        }
        .main:after {
            clear: both;
        }
        .show {
            background: url("img/homepage_bg.jpg") left center;
            margin: 0 auto ;
            width: 980px;
            height: 300px;
            text-align: center;
        }
        .show img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: 50px;
        }
        .show .name {
            font-size: 30px;
            color: #fff;
            margin: 10px 0;
        }
        .show .dsc {
            font-size: 20px;
            color: #fff;
            margin: 10px 0;
        }
        .menu {
            width: 980px;
            background-color: #fff;
            height: 40px;
            margin: 0 auto ;
        }
        .menu .mid {
            width: 500px;
            height: 40px;
            margin: 0 auto;
        }
        .menu .mid li {
            float: left;
            width: 33%;
            list-style: none;
            text-align: center;
            margin: 10px 0;
        }
        .menu .mid li a {
            text-decoration: none;
            color: #000;
            height: 20px;
            line-height: 20px;
        }
        .menu .mid li a:hover {
            color: #32a2d5;
        }
        .left_area {
            float: left;
            display: inline;
            width: 300px;
        }
        .left_area .mess {
            margin-top: 0px;
            width: 300px;
            height: 50px;
            background-color: #fff;
            margin-bottom: 20px;
        }
        .left_area .mess li {
            float: left;
            display: inline;
            width: 33%;
            border-right: solid 1px #ccc;
            height: 40px;
            margin: 1px 0 1px 0;
            text-align: center;
            margin:5px 0 ;
        }
        .left_area .mess li a {
            text-decoration: none;
            font-size: 14px;
            color: #808080;
        }
        .left_area .mess strong {
            margin: 0 auto;
            display: block;
            width: 65px;
            font-size: 18px;
            font-family: "Arial";
            color: #000;
        }
        .left_area .mess strong:hover {
            color: #32a2d5;
        }
        .right_area {
            width: 660px;
            float: right;
        }
        .right_area .list_top {
            background-color: #f2f2f5;
            height: 50px;
            line-height: 50px;
            width: 660px;
            padding-right: 10px;
        }
        .right_area .list_top_left {
            float: left;
            height: 40px;
            text-align: center;
        }
        .right_area .list_top_left .both {
            display: inline-block;
            background-color: #fff;
            height: 50px;
            width: 80px;
            border-bottom: 5px solid #f7691d;
        }
        .right_area .list_top_left .both a {
            text-decoration: none;
            color: #000;
        }
        .right_area .list_top_left .both a:hover {
            color: #32a2d5;
        }
        .right_area .list_top_left .more {
            display: inline-block;
            height: 50px;
            width: 80px;
        }
        .right_area .list_top_left .more a {
            text-decoration: none;
            color: #000;
        }
        .right_area .list_top_left .more a:hover {
            color: #32a2d5;
        }
        .right_area .list_top_right {
            float: right;
            height: 30px;
            line-height: 30px;
            text-align: center;
            margin: 10px 0 ;
        }
        .right_area .list_top_right .search_wb {
            height: 25px;
            width: 250px;
        }
        .right_area .showList {
            margin-top: 20px;
            padding: 0 0;
            width: 660px;
            background-color: #fff;
        }
        .right_area .showList .item {
            font-family: "Microsoft Yahei", "微软雅黑";
            font-size: 12px;
            margin-bottom: 10px;
            padding-top: 10px;
            position: relative;
            -webkit-border-radius: 2px;
            -moz-border-radius: 2px;
            border-radius: 2px;
        }
        .right_area .showList .portrait {
            float: left;
            display: inline;
            width: 50px;
            margin-left: 15px;
        }
        .right_area .showList .portrait img {
            width: 50px;
            height: 50px;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
        }
        .right_area .showList .content {
            float: right;
            width: 570px;
            line-height: 30px;
            font-size: 14px;
            margin-left: 5px;
        }
        .right_area .showList .content .album{
            width: 150px;
            height: 150px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .right_area .showList .content a {
            text-decoration: none;
            font-size: 16px;
            color: #32a2d5;
        }
        .right_area .showList .content div {
            color: gray;
        }
        .right_area .showList .operation {
            clear: both;
            height: 30px;
            border-top: 1px solid #ccc;
            width: 660px;
        }
        .right_area .showList .operation ul {
            margin: 5px 0;
        }
        .right_area .showList .operation li {
            float: left;
            height: 20px;
            list-style: none;
            width: 164px;
            text-align: center;
            border-right: 1px solid #ccc;
        }
        .right_area .showList .operation li a {
            text-decoration: none;
            color: #8f8080;
        }
        .right_area .showList .operation li a:hover {
            color: #32a2d5;
        }


        .btn-group, .btn-group-vertical {
            position: relative;
            display: inline-block;
            vertical-align: middle;
        }

        .btn-group, .btn-group-vertical {
            position: relative;
            display: inline-block;
            vertical-align: middle;
        }
        .btn-group > .btn:first-child {
            margin-left: 0;
        }
        .btn-group > .btn:first-child {
            margin-left: 0;
        }
        .btn-group > .btn, .btn-group-vertical > .btn {
            position: relative;
            float: left;
        }
        .btn-group > .btn, .btn-group-vertical > .btn {
            position: relative;
            float: left;
        }
        .btn-info {
            background-image: -webkit-linear-gradient(top,#5bc0de 0,#2aabd2 100%);
            background-image: -o-linear-gradient(top,#5bc0de 0,#2aabd2 100%);
            background-image: -webkit-gradient(linear,left top,left bottom,from(#5bc0de),to(#2aabd2));
            background-image: linear-gradient(to bottom,#5bc0de 0,#2aabd2 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff2aabd2', GradientType=0);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            background-repeat: repeat-x;
            border-color: #28a4c9;
        }
        .btn-danger, .btn-default, .btn-info, .btn-primary, .btn-success, .btn-warning {
            text-shadow: 0 -1px 0 rgba(0,0,0,.2);
            -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.15),0 1px 1px rgba(0,0,0,.075);
            box-shadow: inset 0 1px 0 rgba(255,255,255,.15),0 1px 1px rgba(0,0,0,.075);
        }
        .btn-info {
            color: #fff;
            background-color: #5bc0de;
            border-color: #46b8da;
        }
        .btn {
            display: inline-block;
            margin-bottom: 0;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            white-space: nowrap;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            border-radius: 4px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .btn {
            display: inline-block;
            margin-bottom: 0;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            white-space: nowrap;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            border-radius: 4px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .btn-info {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #fff;
            background-color: #5bc0de;
            border-color: #46b8da;
        }
        .btn .caret {
            margin-left: 0;
        }
        .btn .caret {
            margin-left: 0;
        }
        .caret {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 2px;
            vertical-align: middle;
            border-top: 4px solid;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
        }
        .caret {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 2px;
            vertical-align: middle;
            border-top: 4px solid;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
        }
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        /*.dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            list-style: none;
            font-size: 14px;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
            box-shadow: 0 6px 12px rgba(0,0,0,.175);
            background-clip: padding-box;
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            list-style: none;
            font-size: 14px;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
            box-shadow: 0 6px 12px rgba(0,0,0,.175);
            background-clip: padding-box;
        }*/

        .num{
            font-size:22px;
            /*color:#049;*/
            color: #8c8c8c;
            font-family:Constantia,Georgia;
        }

        .weibo_num{
            font-size:16px;
            /*color: #5bc0de;*/
            color: #8c8c8c;
        }
    </style>
</head>
<body style="margin-top: -15px;">
<div class="header">
    <nav class="navbar navbar-default navbar-fixed-top" style="background: #fafffa;" >
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header" style="margin-left: 50px;">
                <!-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                     <span class="sr-only">Toggle navigation</span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                 </button>-->
                <a class="navbar-brand" href="#" ><img src="img/logo.jpg" style="height: 50px;margin-top: -15px;"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <!-- <ul class="nav navbar-nav">
                     <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                     <li><a href="#">Link</a></li>
                     <li class="dropdown">
                         <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                         <ul class="dropdown-menu">
                             <li><a href="#">Action</a></li>
                             <li><a href="#">Another action</a></li>
                             <li><a href="#">Something else here</a></li>
                             <li role="separator" class="divider"></li>
                             <li><a href="#">Separated link</a></li>
                             <li role="separator" class="divider"></li>
                             <li><a href="#">One more separated link</a></li>
                         </ul>
                     </li>
                 </ul>-->
                <div class="navbar-form navbar-left" role="search" onkeydown="searchbyEnter()">
                    <div class="form-group">
                        <input id="search" type="text" class="form-control" placeholder="搜一搜你感兴趣的话题" style="width: 500px;">
                    </div>
                    <input type="button" class="btn btn-info" onclick="search()" value="搜索">
                </div>
                <ul class="nav navbar-nav navbar-right" style="font-size: 16px;">
                    <li><a href="index.php">首页</a></li>
                    <li><a href="#">发现</a></li>
                    <li><a href="index.php"><?php echo $myname;?></a></li>
                    <li><a href="#">好友动态</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">设置 <span class="caret"></span></a>
                        <ul class="dropdown-menu" >
                            <li><a href="mycollectionPage.php" style="height: 26px;">我的收藏</a></li>
                            <li><a href="myapprovePage.php" style="height: 26px;">我赞过的微博</a></li>
                            <li  role="separator" class="divider" ></li>
                            <li><a href="doAction.php?act=userOut" style="height: 26px;">退出</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--<div class="header_content">
        <div class="logo"><a href="#">新浪微博</a></div>
        <div class="search">
            <input type="text" class="search_input" id="search" placeholder="搜一搜你感兴趣的话题">
            <input type="button" class="btn-info" value="搜索" onclick="search()" style="margin: -3px 5px">
        </div>
        <ul class="headerNav">
            <li><a href="">首页</a></li>
            <li><a href="">发现</a></li>
            <li><a href="index.php"><?php /*echo $_SESSION['username'];*/?></a></li>
            <li><a href="">好友动态</a></li>
            <li onmouseout="hiddenInstall()"  onmouseover="showInstall()">
                <a href="" id="install" >设置</a>
                <dd >
                <dt ><a href="" >账号设置</a></dt>
                <dt ><a href="" >账号安全</a></dt>
                <dt ><a href="" >隐私</a></dt>
                <dt ><a href="doAction.php?act=userOut" >退出</a></dt>
                </dd>


            </li>
        </ul>
    </div>-->
</div>

<div class="main" style="margin-top: 15px;">

    <div class="show">
        <a href="uploads/<?php echo $rows['face'];?>" title="查看大头像" target="_blank"><img src="uploads/<?php echo $rows['face'];?>"/></a>
        <div class="name"><?php echo $rows['username'];?><img src="<?php if($rows['sex']=='男'){echo 'img/boy.png';}else if($rows['sex']=='女'){echo 'img/gril.png';}?>"  style="width: 20px;height: 20px;margin-top: 0px"></div>
        <?php if($myid==$uid){?>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target=".bs-example-modal-sm">更换头像</button>
            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="mySmallModalLabel">更换头像</h4>
                        </div>
                        <form method="post" action="doAction.php?act=changeFace&uid=<?php echo $uid;?>" enctype="multipart/form-data" onsubmit="return checkChangeFaceForm();">
                            <div class="modal-body">
                                <input type="file" id="face"  name="myfile2" placeholder="上传头像" accept=".gif,.jpeg,.png,.jpg,.wbmp">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
                                <button type="submit" class="btn btn-info">保存</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php }?>
        <?php if($myid!=$uid){
            if($flag){?>
                <input type="button" class="btn btn-info" value="＋关注" onclick="follow(<?php echo $uid;?>)">
            <?php }else{?>
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        已关注<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" onclick="nofollow(<?php echo $uid;?>)">取消关注</a></li>
                        <!--<li role="separator" class="divider"></li>
                        <li><a href="#">设置分组</a></li>-->
                    </ul>
                </div>
            <?php }}?>
        <!--<<div style="color: white;">input type="button" class="btn-danger" value="取消关注" onclick="nofollow(<?php echo $uid;?>)">一句话介绍你自己</div>-->
    </div>
    <div class="menu">
        <div class="mid">
            <ul style="font-weight: bold;font-size: 18px;">
                <?php if($myid!=$uid) {
                    if ($rows['sex'] == '男') {
                        ?>
                        <li style="visibility: hidden;"><a href="">他的相册</a></li>
                        <li><a href="">他的主页</a></li>
                    <?php } else if ($rows['sex'] == '女') { ?>
                        <li style="visibility: hidden;"><a href="">她的相册</a></li>
                        <li><a href="">她的主页</a></li>
                    <?php }
                }else{?>
                    <li style="visibility: hidden;"><a href="">我的相册</a></li>
                    <li ><a href="" >我的主页</a></li>
                <?php }?>

                <li style="visibility: hidden;"><a href="">管理中心</a></li>
            </ul>
        </div>
    </div>

    <div class="main">
        <div class="left_area" style="height: 50px;">
            <div class="mess" style="margin-top: -10px;">
                <ul>
                    <li><a href="followPage.php?uid=<?php echo $uid;?>"><strong><?php echo $rows['followNum'];?></strong>关注</a></li>
                    <li><a href="fanPage.php?uid=<?php echo $uid;?>"><strong><?php echo $rows['fanNum'];?></strong>粉丝</a></li>
                    <li style="border-right: none;"><a href="myHomePage.php?uid=<?php echo $uid;?>"><strong><?php echo $rows['blogNum'];?></strong>微博</a></li>
                </ul>
            </div>
            <div class="hotBox">
                <div class="hot_tittle">
                    <h3  style="margin-top: 11.3px;">热门微博 Top5</h3>
                    <a href="">刷新</a>
                </div>
                <ul class="hot_list">
                    <?php
                    $sql="select b.id,b.text,u.username,b.uid from blog as b join user u on b.uid=u.id  order by b.approveNum+b.collectNum+b.tranNum+b.discussNum desc limit 0,5";
                    $hrows=fetchAll($link,$sql);
                    //print_r($hrows);
                    foreach($hrows as $hrow){
                        ?>
                        <li><a href="discussPage.php?uid=<?php echo $hrow['uid']?>&blogId=<?php echo $hrow['id'];?>"  ><?php echo "#".$hrow['username']."#"?></a><a href="discussPage.php?uid=<?php echo $hrow['uid']?>&blogId=<?php echo $hrow['id'];?>" style="float: right;">查看</a></li>
                    <?php }?>
                </ul>
                <div class="hot_more">
                    <a href="hotBlogPage.php">查看更多 &gt;</a>
                </div>
            </div>
        </div>
        <div class="right_area">
            <div class="list_top">
                <div class="list_top_left">
                    <span class="both"><a href="">全部</a></span>
                    <!--<span class="more"><a href="#disArea">更多</a></span>-->
                </div>
                <!--<div class="list_top_right">
                    <input type="text" class="search_wb" placeholder="搜索你的微博">
                </div>-->
            </div>



            <div class="showList">
                <div class="item">
                    <div class="portrait">
                        <a href="myHomePage.php?uid=<?php echo $uid;?>"><img src="uploads/<?php echo $rows['face'];?>" ></a>
                    </div>
                    <div class="content">
                        <div>
                            <a href="myHomePage.php?uid=<?php echo $uid;?>"><?php echo $rows['username'];?></a>
                            <img src="<?php if($rows['sex']=='男'){echo 'img/boy.png';}else if($rows['sex']=='女'){echo 'img/gril.png';}?>"  style="width: 20px;height: 20px;margin-top: 0px">

                        </div>
                        <div><?php echo $row2['time'];?>&nbsp;&nbsp;来自微博luckystarss.cn</div>
                        <p><?php echo $row2['text'];?></p>
                        <?php if($row2['isTran']==1){
                            $tsql="select b.id,b.uid,b.time,b.text,u.username,u.sex from blog as b join user u on u.id=b.uid where b.id={$row2['tranBlogId']}";
                            $trow=fetchOne($link,$tsql);
                            //print_r($trow);
                            ?>
                        <?php if($trow){?>
                            <div class="content" style="background:#f2f2f5;border: 1px solid #ccc;margin-right: 20px;padding-left: 10px;margin-bottom: 5px;">
                                <div>
                                    <a href="discussPage.php?uid=<?php echo $trow['uid']?>&blogId=<?php echo $trow['id']?>"><?php echo $trow['username'];?></a>
                                    <img src="<?php if($trow['sex']=='男'){echo 'img/boy.png';}else if($trow['sex']=='女'){echo 'img/gril.png';}?>"  style="width: 20px;height: 20px;margin-top: 0px">
                                </div>
                                <div><?php echo $trow['time'];?>&nbsp;&nbsp;来自微博luckystarss.cn</div>
                                <p><?php echo $trow['text'];?></p>

                                <?php $sql3="select albumPath from album where blogId={$trow['id']}";
                                if(@$rows3=fetchAll($link,$sql3)){
                                    //print_r($rows3);
                                    $i=1;
                                    foreach($rows3 as $row3){
                                        if($i%3!=0){
                                            echo "<a href='admin/uploads/{$row3['albumPath']}' target='_blank'><img style='width: 150px;height: 150px;margin-bottom: 10px;margin-right: 5px;' class='album' src='image_220/{$row3['albumPath']}'></a>";
                                        }else{
                                            echo "<a href='admin/uploads/{$row3['albumPath']}' target='_blank'><img style='width: 150px;height: 150px;' class='album' src='image_220/{$row3['albumPath']}</a></br>'";
                                        }

                                    }
                                }
                                ?>
                            </div>
                            <?php }else{?>
                                <div class="content" style="background:#f2f2f5;border: 1px solid #ccc;margin-right: 20px;padding-left: 10px;margin-bottom: 5px;">
                                    该条微博已被删除
                                </div>
                            <?php }?>
                        <?php }?>
                        <?php $sql3="select albumPath from album where blogId={$blogId}";
                        if(@$rows3=fetchAll($link,$sql3)){
                            //print_r($rows3);
                            $i=1;
                            foreach($rows3 as $row3){
                                if($i%3!=0){
                                    echo "<a href='admin/uploads/{$row3['albumPath']}' target='_blank'><img class='album' src='image_220/{$row3['albumPath']}'></a>";
                                }else{
                                    echo "<a href='admin/uploads/{$row3['albumPath']}' target='_blank'><img class='album' src='image_220/{$row3['albumPath']}'></a><br/>";
                                }$i++;
                            }
                        }

                        ?>
                    </div>

                </div>


                <div class="operation">
                    <ul>
                        <?php
                        $sql="select * from collectblog where uid={$myid} and blogId={$row2['id']}";
                        $res=fetchOne($link,$sql);
                        if($res){
                            ?>
                            <li><a style="color: red;" id="colbid-<?php echo $row2['id'];?>" onclick="colblog(<?php echo  $myid;?>,<?php echo $row2['id'];?>)" href="javascript:void(0)">已收藏(<?php echo $row2['collectNum'];?>)</a></li>
                        <?php }else{?>
                            <li><a id="colbid-<?php echo $row2['id'];?>" onclick="colblog(<?php echo  $myid;?>,<?php echo $row2['id'];?>)" href="javascript:void(0)">收藏(<?php echo $row2['collectNum'];?>)</a></li>
                        <?php }?>
                        <li><a href="discussPage.php?uid=<?php echo $row2['uid'];?>&blogId=<?php echo $row2['id']?>#disArea">转发(<?php echo $row2['tranNum'];?>)</a></li>
                        <li><a href="discussPage.php?uid=<?php echo $row2['uid'];?>&blogId=<?php echo $row2['id']?>#disArea">评论(<?php echo $row2['discussNum'];?>)</a></li>
                        <?php
                        $sql="select * from approveblog where uid={$myid} and blogId={$row2['id']}";
                        $res=fetchOne($link,$sql);
                        if($res){
                            ?>
                            <li style="border-right: none;"><a style="color: red;" id="appbid-<?php echo $row2['id'];?>" onclick="appblog(<?php echo  $myid;?>,<?php echo $row2['id'];?>)" href="javascript:void(0)" >已点赞(<?php echo $row2['approveNum'];?>)</a></li>
                        <?php }else{?>
                            <li style="border-right: none;"><a id="appbid-<?php echo $row2['id'];?>" onclick="appblog(<?php echo  $myid;?>,<?php echo $row2['id'];?>)" href="javascript:void(0)" >点赞(<?php echo $row2['approveNum'];?>)</a></li>
                        <?php }?>

                    </ul>
                </div>

            </div>

            <div class="showList">
                <form  id="disArea" action="doAction.php?act=addDiscuss&duid=<?php echo $uid;?>&blogId=<?php echo $blogId;?>" method="post" enctype="multipart/form-data" onsubmit="return checkTextIsNull();">
                    <div class="releaseBox" style="height: 175px;">
                        <div class="release_top">
                            <div class="release_text"></div>
                            <div class="release_links">
                                <span class="weibo_num">还可以输入<strong class="num">140</strong>字</span>
                            </div>
                        </div>
                        <input type="hidden" name="uid" value="<?php echo $myid;?>">
                        <input type="hidden" id="show" name="text">

                        <div class="release_area">
                            <textarea  id="saytext" class="weibo_text" onkeydown="weibo_num();"></textarea>
                        </div>
                        <div class="release_select">
                            <ul class="release_texts">
                                <li><a href="javascript:void(0)" class="emotion"><img src="img/expression.png" width="22px" height="22px" style="margin:-3px 2px 0 0;">表情</a></li>
                                <!--<li><a href="javascript:void(0)" id="selectFileBtn">图片</a></li>-->
                                <li><input type="checkbox" name="isTran" value="1" ><span style="display:inline-block;height: 13px;margin-top: -5px;">同时转发</span></li>
                                <li id="attachList" class="clear"></li>
                            </ul>
                            <div class="release_btn">
                                <input id="send" type="button" class="btn btn-info" value="发布" style="width: 100px;"><!--class="u_release_btn"-->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php  $link=mysqli_connect( 'localhost','root','');
            mysqli_select_db($link,'microblog');
            mysqli_query($link, "SET NAMES 'utf8'");
            $sql="select p.id,p.username,p.sex,p.face,c.text,c.time,c.did from user as p join discuss c on p.id=c.uid where c.blogId={$blogId} order by  c.time desc";
            $totalRows=getResultNum($link,$sql);
            //echo $totalRows;
            $num=getResultNum($link,$sql);
            $pageSize=5;
            $totalPage=ceil($totalRows/$pageSize);
            $page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
            if($page<1||$page==null||!is_numeric($page))$page=1;
            if($page>$totalPage)$page=$totalPage;
            $offset=($page-1)*$pageSize;
            $sql="select p.id,p.username,p.sex,p.face,c.text,c.time,c.did from user as p join discuss c on p.id=c.uid where c.blogId={$blogId} order by  c.time desc limit {$offset},{$pageSize}";
            $rows=fetchAll($link,$sql);
            //$num=getResultNum($link,$sql);

            //print_r($rows);?>
            <div class="showList" style="padding-left: 15px;">
                <div class="item" style="float: left;">评论数:<?php echo $num;?></div>
                <div class="operation" style="height: 0px;border-top: none;"></div>
            </div>
            <?php
            foreach($rows as $row){
            ?>
            <div class="showList">
                <div class="item">
                    <div class="portrait">
                        <a href="myHomePage.php?uid=<?php echo  $row['id'];?>"><img src="uploads/<?php echo $row['face'];?>" ></a>
                    </div>
                    <div class="content">
                        <div>
                            <a href="#"><?php echo $row['username'];?></a>
                            <img src="<?php if($row['sex']=='男'){echo 'img/boy.png';}else if($row['sex']=='女'){echo 'img/gril.png';}?>"  style="width: 20px;height: 20px;margin-top: 0px">
                            <?php if($myid==$row['id']){?>
                                <div class="btn-group" style="float: right;margin-right: 20px;">
                                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0);" onclick="deleteDiscuss(<?php echo $row['did'];?>,<?php echo $blogId;?>)">删除该条评论</a></li>
                                    </ul>
                                </div>
                            <?php }?>
                        </div>
                        <p><?php echo $row['text'];?></p>
                        <div><?php echo $row['time'];?>&nbsp;&nbsp;来自微博luckystarss.cn</div>

                       <!-- --><?php /*$sql3="select albumPath from album where blogId={$blogId}";
                        if(@$rows3=fetchAll($link,$sql3)){
                            print_r($rows3);
                            $i=1;
                            foreach($rows3 as $row3){
                                if($i%3!=0){
                                    echo "<img class='album' src='image_220/{$row3['albumPath']}'>";
                                }else{
                                    echo "<img class='album' src='image_220/{$row3['albumPath']}<br/>'>";
                                }$i++;
                            }
                        }

                        */?>
                    </div>

                </div>


                <div class="operation" style="height: 0">
                   <!-- <ul>
                        <li><a href="">收藏(<?php /*echo $row2['collectNum'];*/?>)</a></li>
                        <li><a href="">转发(<?php /*echo $row2['tranNum'];*/?>)</a></li>
                        <li><a href="">评论(<?php /*echo $row2['discussNum'];*/?>)</a></li>
                        <li style="border-right: none;"><a href="">点赞(<?php /*echo $row2['approveNum'];*/?>)</a></li>
                    </ul>-->
                </div>

            </div>
            <?php }?>
            <?php if($totalRows>$pageSize):?>
                <div class="showList">
                    <?php echo showPage($page, $totalPage,"uid={$uid}&blogId={$blogId}");?>
                    <div class="operation" style="height: 0;">
                    </div>
                </div>

            <?php endif;?>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">

    function follow(uid){
        // window.location="doAction.php?act=followBysearchPage&uid="+uid+"&keywords="+keywords;
        $.ajax({
            type : "POST",
            url:'doAction2.php?act=follow',
            dataType : "json",
            data :{uid:uid},
            success :function(response){
                if(response.errno==0){
                    alert(response.mes);
                    //location.href="search.php?keywords="+keywords;
                    location.reload();//重新加载该页面
                }else{
                    alert(response.mes);

                }
            },
            error:function(){alert('error')}
        });
    }
    function nofollow(uid){
        $.ajax({
            type : "POST",
            url:'doAction2.php?act=nofollow',
            dataType : "json",
            data :{uid:uid},
            success :function(response){
                if(response.errno==0){
                    alert(response.mes);
                    //location.href="search.php?keywords="+keywords;
                    location.reload();//重新加载该页面
                }else{
                    alert(response.mes);
                }
            },
            error:function(){alert('error')}
        });
    }

    function deleteDiscuss(did,blogId){
        $.ajax({
            type : "POST",
            url:'doAction2.php?act=delDiscuss',
            dataType : "json",
            data :{did:did,blogId:blogId},
            success :function(response){
                if(response.errno==0){
                    alert(response.mes);
                    //location.href="search.php?keywords="+keywords;
                    location.reload();//重新加载该页面
                }else{
                    alert(response.mes);
                }
            },
            error:function(){alert('error')}
        });
    }
</script>
<script  src="zztuku2179/js/jquery.min.js"></script>
<script type="text/javascript" src="zztuku2179/js/jquery.qqFace.js"></script>
<script type="text/javascript">
    $(function(){
        $('.emotion').qqFace({
            id : 'facebox',
            assign:'saytext',
            path:'zztuku2179/arclist/'	//表情存放的路径
        });
        $("#send").click(function(){
            var str = $("#saytext").val();
            $("#show").val(replace_em(str));
        });

    });

    /* function click(){
     document.getElementById("saytext").innerHTML(";;;;;;");

     }*/
    //查看结果
    function replace_em(str){
        str = str.replace(/\</g,'&lt;');
        str = str.replace(/\>/g,'&gt;');
        str = str.replace(/\n/g,'<br/>');
        str = str.replace(/\[em_([0-9]*)\]/g,'<img src="zztuku2179/arclist/$1.gif" border="0" />');
        return str;
    }
</script>
<script type="javascript" src="js/jquery-1.12.3.js"></script>
<script >
    $(function(){
        $('#send').click(
            function () {
                if($('#show').val().trim()==""){
                    alert("发布内容不能为空");
                }else{
                    $.ajax({
                        type : "POST",
                        url:'doAction2.php?act=addDiscuss&blogId=<?php echo $blogId;?>',
                        dataType : "json",
                        data :$('#disArea').serialize(),
                        success :function(response){
                            if(response.errno==0){
                                alert(response.mes);
                                location.reload();//重新加载该页面
                            }else{
                                alert(response.mes);
                                location.reload();//重新加载该页面
                            }
                        },
                        error:function(){alert('error')}
                    });
                }

            }
        );

    });
    function weibo_num() {
        var total = 280;
        var len = $('.weibo_text').val().length;
        var temp = 0;
        if (len > 0) {
            for (var i = 0; i < len; i++) {
                if ($('.weibo_text').val().charCodeAt(i) > 255) {
                    temp += 2;
                } else {
                    temp ++;
                }
            }
            var result = parseInt((total - temp)/2)+1;
            if (result >= 0) {
                $('.weibo_num').html('还可以输入<strong class="num">' + result + '</strong>字');
                $('#send').removeAttr('disabled');
                return true;
            } else {
                $('.weibo_num').html('已超出<strong class="num" style="color: red;">' + (-result) + '</strong>字');
                $('#send').attr('disabled','disabled');
                return false;
            }
        }

    }

    function colblog(uid,bid){
        $.ajax({
            type : "POST",
            url:'doAction2.php?act=collectblog',
            dataType : "json",
            data :{uid:uid,blogId:bid},
            success :function(response){
                if(response.errno==0){
                    alert(response.mes);
                    $('#colbid-'+bid).css('color','red');
                    $('#colbid-'+bid).html('已收藏('+response.collectNum+')');
                }else{
                    alert(response.mes);
                    $('#colbid-'+bid).css('color','#8f8080');
                    $('#colbid-'+bid).html('收藏('+response.collectNum+')');
                }
            },
            error:function(){alert('error')}
        });
    }
    function appblog(uid,bid){
        $.ajax({
            type : "POST",
            url:'doAction2.php?act=approveblog',
            dataType : "json",
            data :{uid:uid,blogId:bid},
            success :function(response){
                if(response.errno==0){
                    alert(response.mes);
                    $('#appbid-'+bid).css('color','red');
                    $('#appbid-'+bid).html('已点赞('+response.approveNum+')');
                }else{
                    alert(response.mes);
                    $('#appbid-'+bid).css('color','#8f8080');
                    $('#appbid-'+bid).html('点赞('+response.approveNum+')');
                }
            },
            error:function(){alert('error')}
        });
    }

</script>
</html>