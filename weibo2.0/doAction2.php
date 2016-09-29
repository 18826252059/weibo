<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2016/6/19
 * Time: 23:03
 */
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
require_once "ajaxFunction.php";

session_start();
$act=$_REQUEST['act'];
if(isset($_REQUEST['uid'])){
    $uid=$_REQUEST['uid'];
}
if(isset($_REQUEST['blogId'])){
    $blogId=$_REQUEST['blogId'];
}
if(isset($_REQUEST['did'])){
    $did=$_REQUEST['did'];
}
//$uid=$_REQUEST['uid']?$_REQUEST['uid']:null;
//$duid=$_REQUEST['duid']?$_REQUEST['duid']:null;
//$blogId=$_REQUEST['blogId']?$_REQUEST['blogId']:null;
if($act==="login"){
    doLogin();
}else if($act==="collectblog"){
    collectblog();
}else if($act==="approveblog"){
    approveblog();
}else if($act=="addDiscuss"){
    doAddDiscuss($blogId);
}else if($act=="delDiscuss"){
    doDelDiscuss($did,$blogId);
}
else if($act=="changePassword"){
    doChangePassword();
}else if($act=="follow"){
    doFollow($uid);
}else if($act=="nofollow"){
    doNoFollow($uid);
}else if($act=="delBlog"){
    doDelBlog($blogId);
}