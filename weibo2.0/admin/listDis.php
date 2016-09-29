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
//checkLogined();

//$order=$_REQUEST['order']?$_REQUEST['order']:null;
//$orderBy=$order?"order by p.".$order:null;
$keywords=$_GET['keywords']?$_GET['keywords']:null;
$where=$keywords?"where c.username  like '%{$keywords}%' or p.text like '%{$keywords}%'":null;
$link=mysqli_connect( 'localhost','root','');
mysqli_select_db($link,'microblog');
mysqli_query($link, "SET NAMES 'utf8'");
//$sql="select p.id,p.uid,p.time,p.text,c.username from blog as p join user c on p.uid=c.id {$where}  ";
$sql="select p.id,p.uid,p.time,p.text,c.username from blog as p join user c on p.uid=c.id {$where}";
$totalRows=getResultNum($link,$sql);
$pageSize=20;
$totalPage=ceil($totalRows/$pageSize);
$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
if($page<1||$page==null||!is_numeric($page))$page=1;
if($page>$totalPage)$page=$totalPage;
$offset=($page-1)*$pageSize;
//$sql="select p.id,p.uid,p.time,p.text,p.collectNum,p.tranNum,p.discussNum,p.approveNum,c.username from blog as p join user c on p.uid=c.id {$where} {$orderBy} limit {$offset},{$pageSize}";
$sql="select p.id,p.uid,p.time,p.text,p.collectNum,p.tranNum,p.discussNum,p.approveNum,c.username from blog as p join user c on p.uid=c.id {$where} order by p.time desc limit {$offset},{$pageSize}";
$rows=fetchAll($link,$sql);
//print_r($rows);
/*if(!$rows){
    alertMes("sorry,没有微博,请添加!","addBlog.php");
    exit;
}*/
//print_r($rows);
//$proImgs=getAllImgByProId(1);
//print_r($proImgs);
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
        <button id="searchbtn" class="btn btn-info" onclick="search()">搜索</button>
        <input type="text" value="" id="searchtxt" placeholder="搜索微博" >
    </div>
    <!--表格-->
    <table class="table table-bordered">
        <tr>
            <th width="10%">编号</th>
            <th width="10%">用户名</th>
            <th width="20%">发布时间</th>
            <th  width="30%">微博内容</th>
            <th width="10%">评论数</th>
            <th width="20%">操作</th>
        </tr>
        <tbody>
        <?php foreach($rows as $row):?>
            <tr>
                <!--这里的id和for里面的c1 需要循环出来-->
                <td ><?php echo $row['id'];?></td>
                <td ><?php echo $row['username'];?></td>
                <td ><?php echo $row['time']; ?></td>
                <td ><?php echo $row['text']; ?></td>
                <td ><?php echo $row['discussNum']; ?></td>

                <td align="center">
                    <input type="button" value="查看所有评论" class="btn btn-info" onclick="showAllDis(<?php echo $row['id'];?>)">
                </td>
            </tr>

        <?php  endforeach;?>
        <?php if($totalRows>$pageSize):?>
            <tr>
                <td colspan="7"><?php echo showPage($page, $totalPage,"keywords={$keywords}");?></td><!--/*&order={$order}*/-->
            </tr>
        <?php endif;?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function showAllDis(blogId){
        window.location='delDis.php?blogId='+blogId;
    }

    function search(){
        var val=document.getElementById("searchtxt").value;
        window.location="listDis.php?keywords="+val;

    }

</script>
</body>
</html>