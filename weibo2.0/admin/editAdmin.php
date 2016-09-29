<?php
//require_once '../include.php';
require_once "../core/admin.inc.php";
require_once "../lib/commom.func.php";
require_once "../lib/mysql.func.php";
$id=$_REQUEST['id'];
$sql="select id,username,password,email from admin where id='{$id}'";
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
<h3>编辑管理员</h3>
<form action="doAdminAction.php?act=editAdmin&id=<?php echo $id;?>" method="post">
    <table class="table table-bordered table-hover">
        <tr>
            <td align="right">管理员名称</td>
            <td><input type="text" name="username" value="<?php echo $row['username'];?>"/></td>
        </tr>
        <tr>
            <td align="right">管理员密码</td>
            <td><input type="password" name="password"  value="<?php echo $row['password'];?>"/></td>
        </tr>
        <tr>
            <td align="right">管理员邮箱</td>
            <td><input type="text" name="email" value="<?php echo $row['email'];?>"/></td>
        </tr>
    </table>
    <input type="submit" class="btn btn-info" value="编辑管理员"/>
</form>
</div>
</body>
</html>