<?php
require_once "../core/admin.inc.php";
require_once "../lib/commom.func.php";
require_once "../lib/mysql.func.php";
require_once "../lib/upload.func.php";
require_once "../lib/image.func.php";
require_once "../lib/page.func.php";
require_once "../lib/string.func.php";
//checkLogined();
$link=mysqli_connect( 'localhost','root','');
mysqli_select_db($link,'microblog');
mysqli_query($link, "SET NAMES 'utf8'");
$keywords=$_GET['keywords']?$_GET['keywords']:null;
$where=$keywords?"where username  like '%{$keywords}%'":null;
$sql="select id,username,sex,email,face,regTime from user {$where} order by regTime desc";
$totalRows=getResultNum($link,$sql);
$pageSize=10;
$totalPage=ceil($totalRows/$pageSize);
$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
if($page<1||$page==null||!is_numeric($page))$page=1;
if($page>$totalPage)$page=$totalPage;
$offset=($page-1)*$pageSize;
$sql="select id,username,sex,email,face,regTime from user {$where} order by regTime desc limit {$offset},{$pageSize}";
$rows=fetchAll($link,$sql);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>-.-</title>
   <!-- <link rel="stylesheet" href="styles/backstage.css">-->
    <link href="styles/mystyle.css" rel="stylesheet">
    <link href="styles/buttonstyle.css" rel="stylesheet">
    <link href="styles/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="styles/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
</head>

<body>
<div class="main2">
    <div class="search">
        <button id="searchbtn" class="btn btn-info" onclick="search()">搜索</button>
        <input type="text" value="" id="searchtxt" placeholder="搜索用户" >
    </div>
    <!--表格 -->
    <table class="table table-bordered" >
        <tr>
            <th width="5%" >编号</th>
            <th width="10%" >用户名称</th>
            <th width="20%" >用户邮箱</th>
            <th width="5%" >性别</th>
            <th width="15%" >头像</th>
            <th width="20%" >注册时间</th>
            <th>操作</th>
        </tr>
        <?php $i=1; foreach($rows as $row):?>
            <tr>
                <!--这里的id和for里面的c1 需要循环出来-->
                <td style="vertical-align: middle"><?php echo $row['id']/*$row['id']*/;?></td>
                <td style="vertical-align: middle"><?php echo $row['username'];?></td>
                <td style="vertical-align: middle"><?php echo $row['email'];?></td>
                <td style="vertical-align: middle"><?php echo $row['sex'];?></td>
                <td style="vertical-align: middle"><img src="../uploads/<?php echo $row['face'];?> " width="50" height="50"></td>
                <td style="vertical-align: middle"><?php echo $row['regTime'];?></td>
                <td style="vertical-align: middle"><input type="button" value="修改" class="btn btn-info" onclick="editUser(<?php echo $row['id'];?>)"><input type="button" value="删除" class="btn btn-danger"  onclick="delUser(<?php echo $row['id'];?>)"></td>
            </tr>
            <?php $i++;endforeach;?>
        <?php if($totalRows>$pageSize):?>
            <tr>
                <td colspan="7"><?php echo showPage($page, $totalPage,"keywords={$keywords}");?></td><!--/*&order={$order}*/-->
            </tr>
        <?php endif;?>
    </table>
</div>
</body>
<script type="text/javascript">

    function editUser(id){
        window.location="editUser.php?id="+id;
    }
    function delUser(id){
        if(window.confirm("您确定要删除吗？")){
            window.location="doAdminAction.php?act=delUser&id="+id;
        }
    }
    function search(){
        var val=document.getElementById("searchtxt").value;
        window.location="listUser.php?keywords="+val;

    }
</script>
</html>