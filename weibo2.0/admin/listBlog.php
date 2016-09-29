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
$sql="select p.id,p.uid,p.time,p.text,c.username,p.isTran,p.tranBlogId from blog as p join user c on p.uid=c.id {$where}";
$totalRows=getResultNum($link,$sql);
$pageSize=20;
$totalPage=ceil($totalRows/$pageSize);
$page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
if($page<1||$page==null||!is_numeric($page))$page=1;
if($page>$totalPage)$page=$totalPage;
$offset=($page-1)*$pageSize;
//$sql="select p.id,p.uid,p.time,p.text,p.collectNum,p.tranNum,p.discussNum,p.approveNum,c.username from blog as p join user c on p.uid=c.id {$where} {$orderBy} limit {$offset},{$pageSize}";
$sql="select p.id,p.uid,p.time,p.text,p.collectNum,p.tranNum,p.discussNum,p.approveNum,c.username ,p.isTran,p.tranBlogId from blog as p join user c on p.uid=c.id {$where} order by p.time desc limit {$offset},{$pageSize}";
$rows=fetchAll($link,$sql);
if(!$rows){
    alertMes("sorry,没有微博,请添加!","addBlog.php");
    exit;
}
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
        <input type="text" value="" id="searchtxt"  placeholder="搜索微博">
    </div>
    <!--表格-->
    <table class="table table-bordered">
        <tr>
            <th width="10%">编号</th>
            <th width="10%">用户名</th>
            <th width="20%">发布时间</th>
            <th  width="30%">微博内容</th>
            <th  width="5%">是否转发</th>
            <th  width="5%">转发微博</th>
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
                <td ><?php if($row['isTran']==1){echo "是";}else{echo "否";} ?></td>
                <td ><?php if($row['isTran']==1)echo $row['tranBlogId']; ?></td>

                <td align="center">
                    <input type="button" value="详情" class="btn btn-success" onclick="showDetail(<?php echo $row['id'];?>,'<?php echo $row['username'];?>')">
                    <input type="button" value="修改" class="btn btn-info" onclick="editBlog(<?php echo $row['id'];?>)"><input type="button" value="删除" class="btn btn-danger" onclick="delBlog(<?php echo $row['id'];?>,<?php echo $row['uid'];?>)">
                    <div id="showDetail<?php echo $row['id'];?>" style="display:none;">
                        <table class="table" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="100px" align="right">用户名</td>
                                <td><?php echo $row['username'];?></td>
                            </tr>
                            <tr>
                                <td width="100px"  align="right">发布时间</td>
                                <td><?php echo $row['time'];?></td>
                            </tr>
                            <tr>
                                <td width="100px"  align="right" >微博内容</td>
                                <td><?php echo $row['text'];?></td>
                            </tr>
                            <tr>
                                <td width="100px"  align="right" >收藏数</td>
                                <td><?php echo $row['collectNum'];?></td>
                            </tr>
                            <tr>
                                <td width="100px"  align="right" >转发数</td>
                                <td><?php echo $row['tranNum'];?></td>
                            </tr>
                            <tr>
                                <td width="100px"  align="right" >评论数</td>
                                <td><?php echo $row['discussNum'];?></td>
                            </tr>
                            <tr>
                                <td width="30%"  align="right" >点赞数</td>
                                <td><?php echo $row['approveNum'];?></td>
                            </tr>
                            <tr>
                                <td width="20%"  align="right">微博图片</td>
                                <td>
                                    <?php
                                    $blogImgs=getAllImgByBlogId($row['id']);
                                    foreach($blogImgs as $img):
                                        ?>
                                        <img width="100" height="100" src="uploads/<?php echo $img['albumPath'];?>" alt=""/> &nbsp;&nbsp;
                                    <?php endforeach;?>
                                </td>
                            </tr>

                        </table>

                    </div>
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
    function showDetail(id,t){


        $("#showDetail"+id).dialog({
            height:"auto",
            width: "auto",
            position: {my: "center", at: "center",  collision:"fit"},
            modal:false,//是否模式对话框
            draggable:true,//是否允许拖拽
            resizable:true,//是否允许拖动
            title:"微博："+t,//对话框标题
            show:"slide",
            hide:"explode"
        });

    }

    function editBlog(id){
        window.location='editBlog.php?id='+id;
    }
    function delBlog(id,uid){
        if(window.confirm("您确认要删除吗？")){
            window.location="doAdminAction.php?act=delBlog&id="+id+"&uid="+uid;
        }
    }
    function search(){
        var val=document.getElementById("searchtxt").value;
        window.location="listBlog.php?keywords="+val;

    }

</script>
</body>
</html>