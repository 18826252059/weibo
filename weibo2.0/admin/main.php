<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Insert title here</title>
    <link href="styles/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<center>
    <h3>系统信息</h3>
    <!--<table width="70%" border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">-->
    <table class="table table-bordered" style="width: 70%;">
        <tr>
            <th>操作系统</th>
            <td><?php echo PHP_OS;?></td>
        </tr>
        <tr>
            <th>Apache版本</th>
            <td><?php echo apache_get_version();?></td>
        </tr>
        <tr>
            <th>PHP版本</th>
            <td><?php echo PHP_VERSION;?></td>
        </tr>
        <tr>
            <th>运行方式</th>
            <td><?php echo PHP_SAPI;?></td>
        </tr>
    </table>
    <h3>网页信息</h3>
    <!--<table width="70%" border="1" cellpadding="5" cellspacing="0" bgcolor="#cccccc">-->
    <table class="table table-bordered" style="width: 70%;">
        <tr>
            <th>系统名称</th>
            <td>Tell</td>
        </tr>
        <tr>
            <th>开发者</th>
            <td>信计133-黄明智</td>
        </tr>
        <tr>
            <th>成功案例</th>
            <td>自媒体系统平台</td>
        </tr>
    </table>
</center>

</body>
</html>