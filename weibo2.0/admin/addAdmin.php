<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Insert title here</title>
    <link href="styles/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles/mystyle.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="styles/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</head>
<body>
<div class="main">
<h3>添加管理员</h3>
<form action="doAdminAction.php?act=addAdmin" method="post">
    <table class="table table-bordered table-hover">
        <tr>
            <td align="right" width="150px">管理员名称</td>
            <td><input type="text" name="username" placeholder="请输入管理员名称"/></td>
        </tr>
        <tr>
            <td align="right">管理员密码</td>
            <td><input type="password" name="password" placeholder="请输入管理员密码"/></td>
        </tr>
        <tr>
            <td align="right">管理员邮箱</td>
            <td><input type="text" name="email" placeholder="请输入管理员邮箱"/></td>
        </tr>
    </table>
    <input class="btn btn-info" type="submit"  value="添加管理员"/>
</form>
</div>
</body>
</html>