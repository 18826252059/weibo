<?php
require_once "../core/admin.inc.php";
require_once "../core/user.inc.php";
require_once "../core/weibo.inc.php";
require_once "../core/album.inc.php";
require_once "../lib/commom.func.php";
require_once "../lib/mysql.func.php";
require_once "../lib/upload.func.php";
require_once "../lib/image.func.php";
require_once "../lib/page.func.php";
require_once "../lib/string.func.php";
header("content-type:text/html;charset=utf-8");
$act=$_REQUEST['act'];
$id=$_REQUEST['id'];
$uid=$_REQUEST['uid'];
$did=$_REQUEST['did'];
$blogId=$_REQUEST['blogId'];
if($act=="logout"){
    logout();
}else if($act=="addAdmin"){
    $mes=addAdmin();
}else if($act=="editAdmin"){
    $mes=editAdmin($id);
}else if($act=="delAdmin"){
    $mes=delAdmin($id);
}else if($act=="addUser"){
    $mes=addUser();
}else if($act=="delUser"){
    $mes=delUser($id);
}else if($act=="editUser"){
    $mes=editUser($id);
}else if($act=="addBlog"){
    $mes=addBlog();
}else if($act=="editBlog"){
    $mes=editBlog($id);
}else if($act=="delBlog"){
    $mes=delBlog($id,$uid);
}else if($act=="delDis"){
    $mes=delDis($did,$blogId);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php
if($mes){
    echo $mes;
}
?>
</body>
</html>
