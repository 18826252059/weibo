<?php
//require_once '../include.php';
require_once "../lib/commom.func.php";
require_once "../core/admin.inc.php";
require_once "../lib/mysql.func.php";
require_once "../lib/page.func.php";

$link=mysqli_connect( 'localhost','root','');
mysqli_select_db($link,'microblog');
mysqli_query($link, "SET NAMES 'utf8'");

$sql="select * from admin";
$totalRows=getResultNum($link,$sql);
$pageSize=4;
$totalPage=ceil($totalRows/$pageSize);
$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
if($page<1||$page==null||!is_numeric($page))$page=1;
if($page>$totalPage)$page=$totalPage;
$offset=($page-1)*$pageSize;
$sql="select id,username,email from admin limit {$offset},{$pageSize}";
$rows=fetchAll($link,$sql);


//$rows=getAdminByPage($pageSize);
if(!$rows){
    alertMes("sorry,没有管理员,请添加!","addAdmin.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>-.-</title>
    <!--<link rel="stylesheet" href="styles/backstage.css">-->
    <link href="styles/mystyle.css" rel="stylesheet">
    <link href="styles/buttonstyle.css" rel="stylesheet">
    <link href="styles/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="styles/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</head>

<body>
<div class="main2">
   <!-- <div class="details_operation clearfix">
        <div class="bui_select">
            <input type="button" value="添&nbsp;&nbsp;加" class="add"  onclick="addAdmin()">
        </div>

    </div>-->
    <!--表格-->
    <table  class="table table-bordered" >
        <thead>
        <tr>
            <th width="15%">编号</th>
            <th width="25%">管理员名称</th>
            <th width="30%">管理员邮箱</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  foreach($rows as $row):?>
            <tr>
                <!--这里的id和for里面的c1 需要循环出来-->
                <td style="vertical-align: middle"><?php echo $row['id'];?></label></td>
                <td style="vertical-align: middle"><?php echo $row['username'];?></td>
                <td style="vertical-align: middle"><?php echo $row['email'];?></td>
                <td style="vertical-align: middle"><input type="button" value="修改" class="btn btn-info" onclick="editAdmin(<?php echo $row['id'];?>)"><input type="button" value="删除" class="btn btn-danger"  onclick="delAdmin(<?php echo $row['id'];?>)"></td>
            </tr>
        <?php endforeach;?>
        <?php if($totalRows>$pageSize):?>
            <tr>
                <td colspan="4"><?php echo showPage($page, $totalPage);?></td>
            </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>
</body>
<script type="text/javascript">

    function addAdmin(){
        window.location="addUser.php";
    }
    function editAdmin(id){
        window.location="editAdmin.php?id="+id;
    }
    function delAdmin(id){
        if(window.confirm("您确定要删除吗？")){
            window.location="doAdminAction.php?act=delAdmin&id="+id;
        }
    }
</script>
</html>