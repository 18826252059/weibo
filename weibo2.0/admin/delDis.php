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

$blogId=$_REQUEST['blogId'];
//echo $blogId;
$link=mysqli_connect( 'localhost','root','');
mysqli_select_db($link,'microblog');
mysqli_query($link, "SET NAMES 'utf8'");

$keywords=$_GET['keywords']?$_GET['keywords']:null;
$where=$keywords?"and (u.username  like '%{$keywords}%' or d.text like '%{$keywords}%')":null;
$sql="select d.did,d.uid,u.username,d.text,d.time from discuss as d join user u on d.uid=u.id where d.blogId={$blogId} {$where} order by d.time desc";
$totalRows=getResultNum($link,$sql);
$pageSize=10;
$totalPage=ceil($totalRows/$pageSize);
$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
if($page<1||$page==null||!is_numeric($page))$page=1;
if($page>$totalPage)$page=$totalPage;
$offset=($page-1)*$pageSize;
$sql="select d.did,d.uid,u.username,d.text,d.time from discuss as d join user u on d.uid=u.id where d.blogId={$blogId} {$where} order by d.time desc limit {$offset},{$pageSize}";
$rows=fetchAll($link,$sql);
//print_r($rows);
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
    <link rel="stylesheet" href="scripts/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
    <script src="scripts/jquery-ui/js/jquery-1.10.2.js"></script>
    <script src="scripts/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
    <script src="scripts/jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
</head>

<body>

<div class="main2">
    <div class="search">
        <button id="searchbtn" class="btn btn-info" onclick="search(<?php echo $blogId;?>)">搜索</button>
        <input type="text" value="" id="searchtxt"  placeholder="搜索评论">
    </div>
    <!--表格-->
    <table class="table table-bordered">
        <tr>
            <th width="10%">编号</th>
            <th width="10%">用户名</th>
            <th width="20%">评论时间</th>
            <th  width="30%">评论内容</th>
            <th width="20%">操作</th>
        </tr>
        <tbody>
        <?php foreach($rows as $row):?>
            <tr>
                <!--这里的id和for里面的c1 需要循环出来-->
                <td ><?php echo $row['did'];?></td>
                <td ><?php echo $row['username'];?></td>
                <td ><?php echo $row['time']; ?></td>
                <td ><?php echo $row['text']; ?></td>
                <td align="center">
                    <input type="button" value="删除该条评论" class="btn btn-danger" onclick="delDis(<?php echo $row['did'];?>,<?php echo $blogId;?>)">
                </td>
            </tr>

        <?php  endforeach;?>
        <?php if($totalRows>$pageSize):?>
            <tr>
                <td colspan="7"><?php echo showPage($page, $totalPage,"keywords={$keywords}&blogId={$blogId}");?></td><!--/*&order={$order}*/-->
            </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function delDis(did,blogId){
        if(window.confirm("您确定要删除吗？")) {
            window.location = "doAdminAction.php?act=delDis&did=" + did + "&blogId=" + blogId;
        }
    }

    function search(blogId){
        var val=document.getElementById("searchtxt").value;
        window.location="delDis.php?keywords="+val+"&blogId="+blogId;

    }

</script>
</body>


