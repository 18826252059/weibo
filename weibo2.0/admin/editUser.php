<?php
require_once "../core/admin.inc.php";
require_once "../lib/commom.func.php";
require_once "../lib/mysql.func.php";
require_once "../lib/upload.func.php";
require_once "../lib/image.func.php";
require_once "../lib/page.func.php";
require_once "../lib/string.func.php";
$id=$_REQUEST['id'];
$sql="select id,username,password,email,sex from user where id='{$id}'";
$link=mysqli_connect( 'localhost','root','');
mysqli_select_db($link,'microblog');
mysqli_query($link, "SET NAMES 'utf8'");
$row=fetchOne($link,$sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Insert title here</title>
    <link href="styles/mystyle.css" rel="stylesheet">
    <link href="styles/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="styles/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</head>
<body>
<div class="main">
<h3>编辑用户</h3>
<form action="doAdminAction.php?act=editUser&id=<?php echo $id;?>" method="post" enctype="multipart/form-data">
    <table class="table table-bordered table-hover">
        <tr>
            <td align="right">用户名</td>
            <td><input type="text" name="username" value="<?php echo $row['username'];?>"/></td>
        </tr>
        <tr>
            <td align="right">密码</td>
            <td><input type="password" name="password" value="<?php echo $row['password'];?>"/></td>
        </tr>
        <tr>
            <td align="right">邮箱</td>
            <td><input type="text" name="email" value="<?php echo $row['email'];?>"/></td>

        </tr>
        <tr>
            <td align="right">性别</td>
            <td><input type="radio" name="sex" value="男"  <?php echo $row['sex']=="男"?"checked='checked'":null;?>/>男
                <input type="radio" name="sex" value="女" <?php echo $row['sex']=="女"?"checked='checked'":null;?>/>女
                <input type="radio" name="sex" value="保密" <?php echo $row['sex']=="保密"?"checked='checked'":null;?>/>保密
            </td>
        </tr>
        <tr>
            <td align="right">头像</td>
            <td><input type="file" name="myFile" /></td>
        </tr>

    </table>
    <input type="submit" class="btn btn-info" value="编辑用户"/>
</form>
</div>
</body>
</html>