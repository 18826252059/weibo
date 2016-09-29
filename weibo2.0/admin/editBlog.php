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

$id=$_REQUEST['id'];
$blogInfo=getBlogById($id);
//print_r($blogInfo);
//print_r($id);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>-.-</title>
    <!--<link href="./styles/global.css"  rel="stylesheet"  type="text/css" media="all" />-->
    <link href="./styles/global.css"  rel="stylesheet"  type="text/css" media="all" />
    <script type="text/javascript" charset="utf-8" src="../plugins/kindeditor/kindeditor.js"></script>
    <script type="text/javascript" charset="utf-8" src="../plugins/kindeditor/lang/zh_CN.js"></script>
    <script type="text/javascript" src="./scripts/jquery-1.6.4.js"></script>
    <link href="styles/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles/mystyle.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="styles/bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <script>
        KindEditor.ready(function(K) {
            window.editor = K.create('#editor_id');
        });
        $(document).ready(function(){
            $("#selectFileBtn").click(function(){
                $fileField = $('<input type="file" name="thumbs[]"/>');
                $fileField.hide();
                $("#attachList").append($fileField);
                $fileField.trigger("click");
                $fileField.change(function(){
                    $path = $(this).val();
                    $filename = $path.substring($path.lastIndexOf("\\")+1);
                    $attachItem = $('<div class="attachItem"><div class="left">a.gif</div><div class="right"><a href="#" title="删除附件">删除</a></div></div>');
                    $attachItem.find(".left").html($filename);
                    $("#attachList").append($attachItem);
                });
            });
            $("#attachList>.attachItem").find('a').live('click',function(obj,i){
                $(this).parents('.attachItem').prev('input').remove();
                $(this).parents('.attachItem').remove();
            });
        });
    </script>
</head>
<body>
<div style="width: 800px">
<h3>编辑微博</h3>
<form action="doAdminAction.php?act=editBlog&id=<?php echo $id;?>" method="post" enctype="multipart/form-data">
    <table class="table table-bordered table-hover">

        <tr>
            <td align="right">用户名</td>
            <td><?php echo $blogInfo['username'];?></td>
        </tr>
        <tr>
            <td align="right">微博内容</td>
            <td>
                <textarea name="text" id="editor_id" style="width:100%;height:150px;"><?php echo $blogInfo['text'];?></textarea>
            </td>
        </tr>
        <!--<td>
            <?php
/*            $blogImgs=getAllImgByBlogId($blogInfo['id']);
            foreach($blogImgs as $img):
                */?>
                <img width="100" height="100" src="uploads/<?php /*echo $img['albumPath'];*/?>" alt=""/> &nbsp;&nbsp;
            <?php /*endforeach;*/?>
        </td>-->
        <tr>
            <td align="right">微博图片</td>
            <td>
                <a href="javascript:void(0)" id="selectFileBtn">添加图片</a>
                <div id="attachList" class="clear"></div>
            </td>
        </tr>
    </table>
    <input type="submit" class="btn btn-info" value="编辑微博"/>
</form>
</div>
</body>
</html>