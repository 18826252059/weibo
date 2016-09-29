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
$myid=$_SESSION['uid'];
$myname=$_SESSION['username'];

if(isset($_COOKIE['username'])){
    $myid=$_COOKIE['uid'];
    $myname=$_COOKIE['username'];
}



$uid=$_GET['uid'];

//echo $uid;
$link=mysqli_connect( 'localhost','root','');
mysqli_select_db($link,'microblog');
mysqli_query($link, "SET NAMES 'utf8'");
$sql="select p.id,p.username,p.sex,p.face,c.followNum,c.fanNum,c.blogNum from user as p join user_information c on p.id=c.uid where c.uid={$uid}";
$rows=fetchOne($link,$sql);
//print_r($rows);

$keywords=$_GET['keywords']?$_GET['keywords']:null;
$where=$keywords?"and u.username like '%{$keywords}%'":null;
$sql2="select f.uid,f.time,u.username from fan as f join user u on f.uid=u.id where f.fid={$uid} {$where} order by f.time desc ";
//$rows2=fetchAll($link,$sql2);
$totalRows=getResultNum($link,$sql2);
//echo $totalRows;
$num=getResultNum($link,$sql2);
$pageSize=5;
$totalPage=ceil($totalRows/$pageSize);
$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
if($page<1||$page==null||!is_numeric($page))$page=1;
if($page>$totalPage)$page=$totalPage;
$offset=($page-1)*$pageSize;
$sql="select f.uid,f.time,u.username from fan as f join user u on f.uid=u.id where f.fid={$uid} {$where} order by f.time desc limit {$offset},{$pageSize}";
$rows2=fetchAll($link,$sql);
//print_r($rows2);

//echo $totalRows;


//print_r($rows2);
//$sql3="select b.id from blog as b join album a on b.id="
$sql3="select uid from fan where fid={$myid} ";
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
    <title>Tell</title>

    <script src="js/indexJS.js"></script>
    <!--<link href="style/index_style.css" rel="stylesheet">
    <link href="style/homepage.css" rel="stylesheet">-->


    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="bootstrap/js/jquery-1.11.1.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f5f8fa;
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
            background: url("../img/tellthing.jpg") left center no-repeat;
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
            font-size: 12px;
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
            padding-left: 25px;
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
            background-color: #f5f8fa;
            margin-top: 15px;
            min-height: 60%;
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
            background: #2b7bb9;
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
            height: 50px;
            line-height: 50px;
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
    </style>
</head>
<body style="margin-top: -15px;background-color: #f5f8fa;">
<div class="header">
    <nav class="navbar navbar-default navbar-fixed-top">
            <div class="collapse" id="about" style="background-color: white;">
                <div class="container">
                    <h3>By Sandwich</h3>
                </div>
            </div>
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->


            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


                <ul class="nav navbar-nav navbar-left" style="font-size: 16px;">
                    <li><a href="index.php">首页</a></li>
                    <li><a data-toggle="collapse" href="#about" aria-expanded="false" aria-controls="about">关于我们</a></li>
                    <li><a href="index.php"><?php echo $myname;?></a></li>
                    <li><a href="#">好友动态</a></li>
                    <li class="dropdown">

                    </li>
                </ul>

                <div class="navbar-form navbar-right" role="search" onkeydown="searchbyEnter()">
                                    <div class="form-group">
                                        <input id="search" type="text" class="form-control" placeholder="搜一搜你感兴趣的话题" style="width: 500px;">
                                    </div>
                                    <input type="button" class="btn btn-info" onclick="search()" value="搜索">
                                    <a href="#" class="dropdown-toggle btn-info" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 个人中心 <span class="caret"></span></a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="mycollectionPage.php" style="height: 26px;">我的收藏</a></li>
                                                                <li><a href="myapprovePage.php" style="height: 26px;">我赞过的微博</a></li>
                                                                <li role="separator" class="divider"></li>
                                                                <li><a href="doAction.php?act=userOut"  style="height: 26px;">退出</a></li>
                                                            </ul>
                                </div>
            </div>
        </div>
    </nav>
</div>

<div class="main">

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
                <input type="button" class="btn-info" value="＋关注" onclick="follow(<?php echo $uid;?>)">
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
        <!--<<div style="color: white;">一句话介绍你自己</div><input type="button" class="btn-danger" value="取消关注" onclick="nofollowByHomePage(<?php echo $uid;?>)">-->
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
                    <li ><a href="" >我的关注</a></li>
                <?php }?>

                <li style="visibility: hidden;"><a href="">管理中心</a></li>
            </ul>
        </div>
    </div>

    <div class="main">
        <div class="left_area">
            <div class="mess" style="margin-top: -10px;">
                <ul>
                    <li><a href="followPage.php?uid=<?php echo $uid;?>"><strong><?php echo $rows['followNum'];?></strong>关注</a></li>
                    <li><a href="fanPage.php?uid=<?php echo $uid;?>"><strong><?php echo $rows['fanNum'];?></strong>粉丝</a></li>
                    <li  style="border-right: none;"><a href="myHomePage.php?uid=<?php echo $uid;?>"><strong><?php echo $rows['blogNum'];?></strong>微博</a></li>
                </ul>
            </div>

        </div>
        <div class="right_area">
            <div class="list_top">
                <div class="list_top_left">
                    <span class="both"><a href="followPage.php?uid=<?php echo $uid;?>">全部</a></span>
                    <!--<span class="more"><a href="">更多</a></span>-->
                </div>
                <div class="list_top_right">
                    <div class="navbar-form navbar-left" style="margin-top: -10px;" onkeydown="searchFollowByEnter()">
                        <div class="form-group">

                            <input id="searchFollow" type="text" class="form-control" placeholder="<?php if($myid==$uid){
                                echo "搜一搜我的关注";
                            }else{
                                if($rows['sex'] == '男'){echo "搜一搜他的关注";}
                                else if($rows['sex'] == '女'){echo "搜一搜她的关注";}
                            }?>
                            " style="width: 200px;">

                        </div>
                        <input type="button" class="btn btn-primary" onclick="searchFollow()" value="搜索">
                    </div>
                </div>
            </div>
            <div class="showList">
                <?php if($keywords!=null){?>
                    <div class="item" style="float: left;margin-left: 20px;" >搜索结果:共<span ><?php echo $totalRows;?></span>个&nbsp;<a href="followPage.php?uid=<?php echo $uid;?>">返回全部关注</a></div>
                <?php }else{?>
                    <div class="item" style="float: left;margin-left: 20px;" >关注数:共<span ><?php echo $totalRows;?></span>个</div>
                <?php }?>
                <div class="operation" style="height: 0px;">
                </div>
            </div>
            <?php if($rows2){?>
            <?php  foreach($rows2 as $row2):?>
                <div class="showList" id="list-<?php echo $row2['uid'];?>">
                    <?php
                    //print_r($row2);
                    $sql="select p.id,p.username,p.sex,p.face,c.followNum,c.fanNum,c.blogNum from user as p join user_information c on p.id=c.uid  where c.uid=".$row2['uid'];
                    $row4=fetchOne($link,$sql);
                   // print_r($row4);
                    ?>
                    <div class="item" style="float: left;">
                        <div class="portrait">
                            <a href="myHomePage.php?uid=<?php echo $row4['id'];?>"><img src="uploads/<?php echo $row4['face'];?>" ></a>
                        </div>
                        <div class="content">
                            <div>
                                <a href="myHomePage.php?uid=<?php echo $row4['id'];?>"><?php echo $row4['username'];?></a>
                                <img src="<?php if($row4['sex']=='男'){echo 'img/boy.png';}else if($row4['sex']=='女'){echo 'img/gril.png';}?>"  style="width: 20px;height: 20px;">
                            </div>
                            <div>
                                关注时间：<?php echo $row2['time'];?>
                            </div>
                        </div>
                        <div  class="content">
                            <span>关注数:<?php echo $row4['followNum'];?></span>&nbsp;&nbsp;
                            <span>粉丝数:<?php echo $row4['fanNum'];?></span>&nbsp;&nbsp;
                            <span>微博数:<?php echo $row4['blogNum'];?></span>&nbsp;&nbsp;
                            <?php if($row4['id']!=$myid){
                                $sql3="select uid from fan where fid={$myid}";
                                $rows3=fetchAll($link,$sql3);
                                //print_r($rows3);
                                $flag=true;
                                if($rows3){
                                    foreach($rows3 as $row3){
                                        if($row3['uid']==$row4['id']){
                                            $flag=false;
                                        }
                                    }
                                }

                                if(!$flag){
                                    ?>
                                    <div class="btn-group" style=" float: right;margin-right: 20px">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100px;">
                                            已关注<span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a  href="javascript:void(0);" onclick="nofollow(<?php echo $row4['id'];?>)">取消关注</a></li>
                                            <!--<li role="separator" class="divider"></li>
                                            <li><a href="#">设置分组</a></li>-->
                                        </ul>

                                    </div>
                                    <?php
                                }else {
                                    ?><input type="button" style="float: right;margin-right: 15px;width: 100px;" class="btn btn-info"  value="＋关注" onclick="follow(<?php echo $row4['id'];?>,<?php echo $uid;?>)">
                                <?php }
                                ?>
                                    <!--<input type="button"  class="btn-danger" style="float: right;margin-right: 20px" value="取消关注" onclick="nofollow(<?php /*echo $row4['id'];*/?>,<?php /*echo $uid;*/?>)">-->

                                <a href="myHomePage.php?uid=<?php echo $row4['id'];?>"><input type="button" class="btn-info" style="float: right;width: 100px;margin-right: 20px;" value="去Ta的主页"></a>
                            <?php  }else{?>
                                <a href="myHomePage.php?uid=<?php echo $row4['id'];?>"><input type="button" class="btn-info" style="float: right;width: 100px;margin-right: 20px;" value="去我的主页"></a>
                            <?php }?>
                        </div>

                    </div>
                    <div class="operation" style="height: 0px;">
                        <!--<ul>
                            <li><a href="">收藏<span>(233)</span></a></li>
                            <li><a href="">转发<span>(233)</span></a></li>
                            <li><a href="">评论<span>(233)</span></a></li>
                            <li><a href="">点赞<span>(233)</span></a></li>
                        </ul>-->
                    </div>
                </div>

            <?php endforeach;?>
                <?php if($totalRows>$pageSize):?>
                    <div class="showList">
                        <?php echo showPage($page, $totalPage,"uid={$uid}&keywords={$keywords}");?>
                        <div class="operation" style="height: 0;">
                        </div>
                    </div>

                <?php endif;?>
            <?php }?>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    function searchFollowByEnter(){
        if ( event.keyCode == 13){
            searchFollow();
        }
    }
    function searchFollow(){
        var val=document.getElementById("searchFollow").value.trim();
        if(val!=""){
            window.location="followPage.php?uid=<?php echo $uid;?>&keywords="+val;
        }
    }
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

</script>
</html>
