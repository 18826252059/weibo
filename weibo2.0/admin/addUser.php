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
<h3>添加用户</h3>
<form action="doAdminAction.php?act=addUser" method="post" enctype="multipart/form-data">
    <table class="table table-bordered table-hover" >
        <tr>
            <td align="right">用户名</td>
            <td><input type="text" name="username" placeholder="请输入用户名称"/></td>
        </tr>
        <tr>
            <td align="right">密码</td>
            <td><input type="password" name="password" placeholder="请输入密码"/></td>
        </tr>
        <tr>
            <td align="right">邮箱</td>
            <td><input type="text" name="email" placeholder="请输入用户邮箱"/></td>
        </tr>
        <tr>
            <td align="right">性别</td>
            <td><input type="radio" name="sex" value="男" checked="checked"/>男
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="sex" value="女" />女
            </td>
        </tr>
        <tr>
            <td align="right">头像</td>
            <td><input type="file" name="myFile" /></td>
        </tr>
    </table>
    <input class="btn btn-info" type="submit"  value="添加用户"/>
</form>

</div>
</body>
</html>