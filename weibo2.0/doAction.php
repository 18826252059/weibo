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
$act=$_REQUEST['act'];
$uid=$_REQUEST['uid'];
$duid=$_REQUEST['duid'];
$blogId=$_REQUEST['blogId'];
//echo $duid;
//echo $blogId;
$returnuid=$_REQUEST['returnuid'];
$keywords=$_REQUEST['keywords'];
$did=$_REQUEST['did'];

if($act==="reg"){
    $mes=reg();
}else if($act=="changeFace"){
    $mes=changeFace($uid);
    alertMes("更换头像成功！","myHomePage.php?uid={$uid}");
    exit();
}
elseif($act==="login"){
    $mes=login();
}elseif($act==="userOut"){
    userOut();
}elseif($act=="addBlog"){
    $mes=addBlog(2);
    alertMes("发布成功！","index.php");
    exit();
}elseif($act=="follow"){
    $mes=follow($uid);
    echo "<script>alert('关注成功')</script>";
    echo "<script>window.location='myHomePage.php?uid={$uid}'</script>";
    exit();
}
elseif($act=="nofollow"){
    $mes=nofollow($uid);
    echo "<script>alert('取关成功')</script>";
    echo "<script>window.location='myHomePage.php?uid={$uid}'</script>";
    exit();
}elseif($act=="followByfollowPage"){
    $mes=follow($uid);
    echo "<script>alert('关注成功')</script>";
    echo "<script>window.location='followPage.php?uid={$returnuid}'</script>";
    exit();
}
elseif($act=="nofollowByfollowPage"){
    $mes=nofollow($uid);
    echo "<script>alert('取关成功')</script>";
    echo "<script>window.location='followPage.php?uid={$returnuid}'</script>";
    exit();
}elseif($act=="followBysearchPage"){
    $mes=follow($uid);
    echo "<script>alert('关注成功')</script>";
    echo "<script>window.location='search.php?keywords={$keywords}'</script>";
    exit();
}elseif($act=="nofollowBysearchPage"){
    $mes=nofollow($uid);
    echo "<script>alert('取关成功')</script>";
    echo "<script>window.location='search.php?keywords={$keywords}'</script>";
    exit();
}elseif($act=="followByfanPage"){
    $mes=follow($uid);
    echo "<script>alert('关注成功')</script>";
    echo "<script>window.location='fanPage.php?uid={$returnuid}'</script>";
    exit();
}elseif($act=="nofollowByfanPage"){
    $mes=nofollow($uid);
    echo "<script>alert('取关成功')</script>";
    echo "<script>window.location='fanPage.php?uid={$returnuid}'</script>";
    exit();
}elseif($act=="deleteBlog"){
    $mes=delBlog($blogId,$uid,2);
    echo "<script>alert('删除成功')</script>";
    echo "<script>window.location='myHomePage.php?uid={$uid}'</script>";
    exit();
}

//增加评论
elseif($act=="addDiscuss"){
    $mes=addDiscuss($blogId);
    echo "<script>alert('评论成功')</script>";
    echo "<script>window.location='discussPage.php?uid={$duid}&blogId={$blogId}'</script>";
    exit();
}elseif($act=="delDiscuss"){
    $mes=delDiscuss($did,$blogId,$duid);
    echo "<script>alert('删除该条评论成功')</script>";
    echo "<script>window.location='discussPage.php?uid={$duid}&blogId={$blogId}'</script>";
    exit();
}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Insert title here</title>
</head>
<body>
<?php
if($mes){
    echo $mes;
}
?>
</body>
</html>