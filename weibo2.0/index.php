<?php
require_once "core/admin.inc.php";
require_once "core/album.inc.php";
require_once "core/user.inc.php";
require_once "core/weibo.inc.php";
require_once "lib/commom.func.php";
require_once "lib/mysql.func.php";
require_once "lib/upload.func.php";
require_once "lib/image.func.php";
require_once "lib/page.func.php";
require_once "lib/string.func.php";
session_start();
checkuserLogined();
/*$cates=getAllCate();
print_r($cates);*/
//echo "该用户的id：".$_SESSION['uid'];
$uid=$_SESSION['uid'];
$sex=$_SESSION['sex'];
$myname=$_SESSION['username'];
if(isset($_COOKIE['username'])){
    $uid=$_COOKIE['uid'];
    $sex=$_COOKIE['sex'];
    $myname=$_COOKIE['username'];
}
/*if(!($cates&&is_array($cates))){
    alertMes("不好意思，网站维护中","http://www.bilibili.com/");
}*/
//echo $uid;
//echo $sex;
$link=mysqli_connect( 'localhost','root','');
mysqli_select_db($link,'microblog');
mysqli_query($link, "SET NAMES 'utf8'");
$sql="select u.face,u_i.followNum,u_i.fanNum,u_i.blogNum from user_information as u_i join user u on u.id=u_i.uid where u_i.uid={$uid}";
$rows=fetchOne($link,$sql);
// print_r($rows);


$sql3="select uid from fan where fid={$uid}";
$rows3=fetchAll($link,$sql3);
//print_r($rows3);
$arr[0]=$uid;
$i=1;
foreach($rows3 as $row3){
    $arr[$i]=$row3['uid'];
    $i++;
}

//print_r($arr);
$str="(".implode(",",$arr).")";
//echo $str;
echo "<br/>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tell</title>
   <link href="style/index_style.css" rel="stylesheet">

    <script type="javascript" src="js/jquery-1.12.3.js"></script>
    <script src="js/indexJS.js"></script>

    <script type="text/javascript" charset="utf-8" src="plugins/kindeditor/kindeditor.js"></script>
    <script type="text/javascript" charset="utf-8" src="plugins/kindeditor/lang/zh_CN.js"></script>
    <script type="text/javascript" src="admin/scripts/jquery-1.6.4.js"></script>

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="bootstrap/js/jquery-1.11.1.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="face/css/rl_exp.css" />
    <link rel="stylesheet" href="css/mid-text.css">
   <script>
        var i=0;
        KindEditor.ready(function(K) {
            window.editor = K.create('#editor_id');
        });
        $(document).ready(function(){

            $("#selectFileBtn").click(function(){
                i=i+1;
                $fileField = $('<input type="file" name="thumbs2[]" accept=".gif,.jpeg,.png,.jpg,.wbmp"/>');
                $fileField.hide();
                $("#attachList").append($fileField);
                $fileField.trigger("click");
                $fileField.change(function(){
                    $path = $(this).val();
                    $filename = $path.substring($path.lastIndexOf("\\")+1);
                    if(i%3==0){
                        $('#attachList').css("height",(i/3+1)*17+"px");
                    }
                    $attachItem = $('<div class="attachItem"><div class="left">a.gif</div><div class="right" style="float: right;display: inline;"><a href="javascript:void(0)" title="删除附件">删除</a></div></div>');
                    if($filename.length<=8){
                        $attachItem.find(".left").html($filename);
                    }else{
                        $attachItem.find(".left").html($filename.substring(0,7)+"...");
                    }

                    $("#attachList").append($attachItem);
                });
            });
            $("#attachList>.attachItem").find('a').live('click',function(obj,i){
                $(this).parents('.attachItem').prev('input').remove();
                $(this).parents('.attachItem').remove();
            });
        });
    </script>
    <style type="text/css">
        #attachList{
            width:510px;
            padding-top:5px;
            margin-bottom: 30px;
        }
        .attachItem{
            width:150px;
            height:25px;
            float:left;
            padding:5px;
            margin-right:10px;
            margin-bottom:5px;
            background:#E6ECF5;
        }

        .attachItem .left{
            float:left;
            display:inline;
        }

        .attachItem .right{
            float:right;
            display:inline;
        }

        .attachItem .right a{
            display:block;
            width:16px;
            height:16px;
            overflow:hidden;
            text-indent:-9999px;
            background:url(admin/images/delete.png);
        }

        .nav {
            font-size: 12px;
            font-family: "Microsoft Yahei", "微软雅黑";
            line-height: 34px;
        }
        .nav a {
            color: #FFF;
            height: 50px;
            display: inline-block;
            padding-left: 15px;
            text-decoration: none;
        }
        .nav li {
            list-style: none;
        }
        .nav li:hover {
            /*background-color: #98cee7;*/
            background-color:#5bc0de
        }

        .num{
            font-size:22px;
            /*color:#049;*/
            color: #8c8c8c;
            font-family:Constantia,Georgia;
        }

        .weibo_num{
            font-size:16px;
            /*color: #5bc0de;*/
            color: #8c8c8c;
        }

        .spinner {
            margin: 100px auto 0;
            width: 150px;
            text-align: center;
            display: none;
            z-index:1002;
        }

        .spinner > div {
            width: 30px;
            height: 30px;
            background-color: #67CF22;

            border-radius: 100%;
            display: inline-block;
            -webkit-animation: bouncedelay 1.4s infinite ease-in-out;
            animation: bouncedelay 1.4s infinite ease-in-out;
            /* Prevent first frame from flickering when animation starts */
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
        }

        .spinner .bounce1 {
            -webkit-animation-delay: -0.32s;
            animation-delay: -0.32s;
        }

        .spinner .bounce2 {
            -webkit-animation-delay: -0.16s;
            animation-delay: -0.16s;
        }

        @-webkit-keyframes bouncedelay {
            0%, 80%, 100% { -webkit-transform: scale(0.0) }
            40% { -webkit-transform: scale(1.0) }
        }

        @keyframes bouncedelay {
            0%, 80%, 100% {
                transform: scale(0.0);
                -webkit-transform: scale(0.0);
            } 40% {
                  transform: scale(1.0);
                  -webkit-transform: scale(1.0);
              }
        }

        #bg{ display: none;  position: absolute;  top: 0%;  left: 0%;  width: 100%;  height: 100%;  background-color: black;  z-index:1001;  -moz-opacity: 0.7;  opacity:.70;  filter: alpha(opacity=70);}
    </style>

</head>
<body style="background-color: #f5f8fa;margin-top: -15px;">

<div class="header">
    <nav class="navbar navbar-default navbar-fixed-top">
            <div class="collapse" id="about" style="background-color: white;">
                <div class="container">
                    <h3>By Sandwich</h3>
                </div>
            </div>
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->


            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


                <ul class="nav navbar-nav navbar-left" style="font-size: 16px;">
                    <li><a href="index.php">首页</a></li>
                    <li><a data-toggle="collapse" href="#about" aria-expanded="false" aria-controls="about">关于我们</a></li>
                    <li><a href="index.php"><?php echo $myname;?></a></li>
                    <li><a href="#">好友动态</a></li>
                    <li class="dropdown">

                    </li>
                </ul>

                <div class="navbar-form navbar-right" role="search" onkeydown="searchbyEnter()">
                                    <div class="form-group">
                                        <input id="search" type="text" class="form-control" placeholder="搜一搜你感兴趣的话题" style="width: 500px;">
                                    </div>
                                    <input type="button" class="btn btn-info" onclick="search()" value="搜索">
                                    <a href="#" class="dropdown-toggle btn-info" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 个人中心 <span class="caret"></span></a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="mycollectionPage.php" style="height: 26px;">我的收藏</a></li>
                                                                <li><a href="myapprovePage.php" style="height: 26px;">我赞过的微博</a></li>
                                                                <li role="separator" class="divider"></li>
                                                                <li><a href="doAction.php?act=userOut"  style="height: 26px;">退出</a></li>
                                                            </ul>
                                </div>
            </div>
        </div>
    </nav>
</div>

<!--内容-->
<div class="main">
    <div class="leftArea">
    
        <div class="userCard" style="width:220px;float:left;margin-right:5px;">
                    <div class="userCardBG">
                    </div>
                    <div class="userHead">
                        <a href="myHomePage.php?uid=<?php echo $uid;?>" ><img class="img-thumbnail" src="uploads/<?php echo $rows['face'];?>" alt=""></a>

                    </div>
                    <ul class="userLinks" style="margin: 10px 22.5px;">
                        <li><a href="followPage.php?uid=<?php echo $uid;?>"><strong><?php echo $rows['followNum'];?></strong>关注</a></li>
                        <li><a href="fanPage.php?uid=<?php echo $uid;?>"><strong><?php echo $rows['fanNum'];?></strong>粉丝</a></li>
                        <li style="border-right: none"><a href="myHomePage.php?uid=<?php echo $uid;?>" ><strong><?php echo $rows['blogNum'];?></strong>微博</a></li>
                    </ul>
                </div>

        <div class="leftCont" >

            <!--<div id="bg"></div>
            <div class="spinner" style="position: absolute;text-align: center;margin: 70px 230px;">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div><br/>
                <span style="font-size: 16px;color: #67CF22;font-weight: bold;">上传中,请稍等...</span>
            </div>-->
            <form id="myform" action="doAction.php?act=addBlog" method="post" enctype="multipart/form-data" onsubmit="return checkTextIsNull();">

                <div class="releaseBox" style="height: auto;">
                    <div class="release_top">
                    <div class="left">
                    <span class="glyphicon glyphicon-leaf btn-lg"></span>
                    </div>

                     <div class="inputpanel">

                     <div class="tip"><span>有事沒事刷Tell……</span></div>
                     <div class="input-wrap"><span class="glyphicon glyphicon-camera"></span></div>

                     </div>
                    </div>
                    <div class="release_bottom">


                    <input type="hidden" name="uid" value="<?php echo $uid;?>">
                    <input type="hidden" id="show" name="text">

                    <div class="release_area">
                        <textarea  id="saytext" class="weibo_text"  onkeydown="weibo_num();"></textarea>
                    </div>
                    <div class="release_select">
                        <ul class="release_texts">
                            <li><a href="javascript:void(0)" class="emotion"><img src="img/expression.png" width="22px" height="22px" style="margin-right: 2px;">表情</a></li>
                            <li><a href="javascript:void(0)" id="selectFileBtn" style="margin-top: 1px;"><img src="img/picture.png" width="22px" height="22px" style="margin-right: 2px;">图片</a></li>
                        </ul>
                        <div class="release_btn">
                            <span class="weibo_num">还可以输入<strong class="num">140</strong>字</span>
                            <input id="send" type="submit" class="btn btn-info" value="发布" style="width: 100px;">
                        </div>
                    </div>
                    </div>
                </div>
                <div id="attachList" class="clear"></div>
            </form>

            <?php

            $sql2="select p.id,p.uid,p.time,p.text,p.collectNum,p.tranNum,p.discussNum,p.approveNum,c.username,c.sex,c.face from blog as p join user c on p.uid=c.id where uid in {$str}  order by time desc  limit 0,100";//限制前总100条
            $rows2=fetchAll($link,$sql2);
            $totalRows=getResultNum($link,$sql2);
            //echo $totalRows;
            $pageSize=10;
            $totalPage=ceil($totalRows/$pageSize);
            $page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
            if($page<1||$page==null||!is_numeric($page))$page=1;
            if($page>$totalPage)$page=$totalPage;
            $offset=($page-1)*$pageSize;
            $sql2="select p.id,p.uid,p.time,p.text,p.collectNum,p.tranNum,p.discussNum,p.approveNum,c.username,c.sex,c.face,p.isTran,p.tranBlogId from blog as p join user c on p.uid=c.id where uid in {$str}  order by time desc limit {$offset},{$pageSize} ";
            $rows2=fetchAll($link,$sql2);
            ;?>
            <?php if($rows2){?>
                <?php  foreach($rows2 as $row2):?>
                    <div class="showList">
                        <div class="item">
                            <div class="portrait">
                                <a href="myHomePage.php?uid=<?php echo $row2['uid'];?>"><img src="uploads/<?php echo $row2['face'];?>" ></a>
                            </div>
                            <div class="content">
                                <div>
                                    <a href="myHomePage.php?uid=<?php echo $row2['uid'];?>"><?php echo $row2['username'];?></a>

                                    <?php if($uid==$row2['uid']){?>
                                        <div class="btn-group" style="float: right;margin-right:20px;">
                                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                            <ul class="dropdown-menu" >
                                                <li><a href="javascript:void(0);" onclick="deleteBlog(<?php echo $row2['id'];?>)" >删除该条微博</a></li>
                                            </ul>
                                        </div>
                                    <?php }?>

                                </div>
                                <div><?php echo $row2['time'];?>&nbsp;&nbsp;</div>
                                <p><?php echo $row2['text'];?></p>
                                <?php if($row2['isTran']==1){
                                    $tsql="select b.id,b.uid,b.time,b.text,u.username,u.sex from blog as b join user u on u.id=b.uid where b.id={$row2['tranBlogId']}";
                                    $trow=fetchOne($link,$tsql);
                                    ?>
                                <?php if($trow){?>
                                    <div class="content" style="background:#f2f2f5;border: 1px solid #ccc;margin-right: 20px;padding-left: 10px;margin-bottom: 5px;">
                                        <div>
                                            <a href="discussPage.php?uid=<?php echo $trow['uid']?>&blogId=<?php echo $trow['id']?>"><?php echo $trow['username'];?></a>
                                            <img src="<?php if($trow['sex']=='男'){echo 'img/boy.png';}else if($trow['sex']=='女'){echo 'img/gril.png';}?>"  style="width: 20px;height: 20px;margin-top: 0px">
                                        </div>
                                        <div><?php echo $trow['time'];?>&nbsp;&nbsp;来自微博luckystarss.cn</div>
                                        <p><?php echo $trow['text'];?></p>

                                        <?php $sql3="select albumPath from album where blogId={$trow['id']}";
                                        if(@$rows3=fetchAll($link,$sql3)){
                                            //print_r($rows3);
                                            $i=1;
                                            foreach($rows3 as $row3){
                                                if($i%3!=0){
                                                    echo "<a href='admin/uploads/{$row3['albumPath']}' target='_blank'><img style='width: 150px;height: 150px;margin-bottom: 10px;margin-right: 5px;' class='album' src='image_220/{$row3['albumPath']}'></a>";
                                                }else{
                                                    echo "<a href='admin/uploads/{$row3['albumPath']}' target='_blank'><img style='width: 150px;height: 150px;' class='album' src='image_220/{$row3['albumPath']}</a><br/>'";
                                                }

                                            }
                                        }

                                        ?>
                                    </div>
                                <?php }else{?>
                                        <div class="content" style="background:#f2f2f5;border: 1px solid #ccc;margin-right: 20px;padding-left: 10px;margin-bottom: 5px;">
                                            该条微博已被删除
                                        </div>
                                    <?php }?>
                                <?php }?>
                                <div id="gallery">
                                <?php $sql3="select albumPath from album where blogId={$row2['id']}";
                                if(@$rows3=fetchAll($link,$sql3)){
                                    //print_r($rows3);
                                    $i=1;
                                    foreach($rows3 as $row3){
                                        if($i%3!=0){
                                            echo "<a href='admin/uploads/{$row3['albumPath']}' target='_blank'><img class='album' src='image_220/{$row3['albumPath']}' style='width: 150px;height: 150px;margin-bottom: 10px;margin-right: 5px;'></a> ";
                                        }else{
                                            echo "<a href='admin/uploads/{$row3['albumPath']}' target='_blank'><img class='album' style='width: 150px;height: 150px;' src='image_220/{$row3['albumPath']}'><br/>";
                                        }

                                    }
                                }

                                ?>
                                </div>
                            </div>

                        </div>

                        <div class="operation">
                            <ul>
                                <?php
                                $sql="select * from collectblog where uid={$uid} and blogId={$row2['id']}";
                                $res=fetchOne($link,$sql);
                                if($res){
                                ?>
                                    <li><a style="color: red;" id="colbid-<?php echo $row2['id'];?>" onclick="colblog(<?php echo  $uid;?>,<?php echo $row2['id'];?>)" href="javascript:void(0)"><span class="glyphicon glyphicon-plus" style="color:#00a1d8" data-toggle="tooltip" title="撤销收藏"></span>(<?php echo $row2['collectNum'];?>)</a></li>
                                <?php }else{?>
                                    <li><a id="colbid-<?php echo $row2['id'];?>" onclick="colblog(<?php echo  $uid;?>,<?php echo $row2['id'];?>)" href="javascript:void(0)"><span class="glyphicon glyphicon-plus" data-toggle="tooltip" title="收藏"></span>(<?php echo $row2['collectNum'];?>)</a></li>
                                <?php }?>
                                <li><a href="discussPage.php?uid=<?php echo $row2['uid']?>&blogId=<?php echo $row2['id']?>#disArea"><span class="glyphicon glyphicon-share-alt" data-toggle="tooltip" title="转发"></span>(<?php echo $row2['tranNum'];?>)</a></li>
                                <li><a href="discussPage.php?uid=<?php echo $row2['uid'];?>&blogId=<?php echo $row2['id']?>"><span class="glyphicon glyphicon-comment" data-toggle="tooltip" title="评论"></span>(<?php echo $row2['discussNum'];?>)</a></li>

                                <?php
                                $sql="select * from approveblog where uid={$uid} and blogId={$row2['id']}";
                                $res=fetchOne($link,$sql);
                                if($res){
                                ?>
                                    <li style="border-right: none;"><a style="color: red;" id="appbid-<?php echo $row2['id'];?>" onclick="appblog(<?php echo  $uid;?>,<?php echo $row2['id'];?>)" href="javascript:void(0)" ><span class="glyphicon glyphicon-heart" style="color:red;" data-toggle="tooltip" title="撤销喜欢"></span>(<?php echo $row2['approveNum'];?>)</a></li>
                                <?php }else{?>
                                    <li style="border-right: none;"><a id="appbid-<?php echo $row2['id'];?>" onclick="appblog(<?php echo  $uid;?>,<?php echo $row2['id'];?>)" href="javascript:void(0)" ><span class="glyphicon glyphicon-heart" data-toggle="tooltip" title="喜欢"></span>(<?php echo $row2['approveNum'];?>)</a></li>
                                <?php }?>

                            </ul>
                        </div>

                    </div>

                <?php endforeach;?>
                <?php if($totalRows>$pageSize):?>
                <div class="showList">
                    <?php echo showPage($page, $totalPage);?>
                    <div class="operation" style="height: 0;">
                    </div>
                </div>

                <?php endif;?>
            <?php }?>


        </div>

    </div>

    <div class="rightArea">

        <!--热门话题-->
        <div class="hotBox">
            <div class="hot_tittle">
                <h3  style="margin-top: 11.3px;">热门微博 Top5</h3>
                <a href="">刷新</a>
            </div>
            <ul class="hot_list">
                <?php
                $sql="select b.id,b.text,u.username,b.uid from blog as b join user u on b.uid=u.id  order by b.approveNum+b.collectNum+b.tranNum+b.discussNum desc limit 0,5";
                $hrows=fetchAll($link,$sql);
                //print_r($hrows);
                foreach($hrows as $hrow){
                    ?>
                    <li><a href="discussPage.php?uid=<?php echo $hrow['uid']?>&blogId=<?php echo $hrow['id'];?>"  ><?php echo "#".$hrow['username']."#"?></a><a href="discussPage.php?uid=<?php echo $hrow['uid']?>&blogId=<?php echo $hrow['id'];?>" style="float: right;">查看</a></li>
                <?php }?>
            </ul>
            <div class="hot_more">
                <a href="hotBlogPage.php">查看更多 &gt;</a>
            </div>
        </div>
        <!--好友动态-->
        <div class="friBox">
            <div class="fri_tittle">
                <h3 style="margin-top: 11.3px;">推荐关注</h3>
                <a href="">换一批</a>
            </div>
            <ul class="fri_list">
                <?php
                $sql="select f.uid,u.username,u.id from fan as f join user u on f.uid=u.id where f.fid={$uid} order by u.lastTimeBlog desc limit 0,5";
                $frows=fetchAll($link,$sql);
                //print_r($frows);
                foreach($frows as $frow){
                    ?>
                    <li><span><?php  echo $frow['username']?></span><a href="myHomePage.php?uid=<?php echo $frow['id'];?>">查看</a></li>
                <?php }?>

            </ul>
            <div class="fri_more">
                <a href="followPage.php?uid=<?php echo $uid;?>">查看更多 &gt;</a>
            </div>
        </div>

    </div>


</div>
</body>
<script  src="zztuku2179/js/jquery.min.js"></script>
<script type="text/javascript" src="zztuku2179/js/jquery.qqFace.js"></script>
<script type="text/javascript">
    $(function(){
        $('.emotion').qqFace({
            id : 'facebox',
            assign:'saytext',
            path:'zztuku2179/arclist/'	//表情存放的路径
        });
        $("#send").click(function(){
            var str = $("#saytext").val();
            $("#show").val(replace_em(str));
        });

    });

   /* function click(){
        document.getElementById("saytext").innerHTML(";;;;;;");

    }*/
    //查看结果
    function replace_em(str){
            str = str.replace(/\</g,'&lt;');
            str = str.replace(/\>/g,'&gt;');
            str = str.replace(/\n/g,'<br/>');
            str = str.replace(/\[em_([0-9]*)\]/g,'<img src="zztuku2179/arclist/$1.gif" border="0" />');
            return str;
    }
</script>
<script type="javascript" src="js/jquery-1.12.3.js"></script>
<script>
$(function () {
//            点击弹出输入
$(".release_bottom").hide();
           $(".release_top").click(function () {
               $(".release_top").hide();
               $(".release_bottom").show();
               $("#saytext").focus();
               });

           });
</script>
<script >
    function weibo_num() {
        var total = 280;
        var len = $('.weibo_text').val().length;
        var temp = 0;
        if (len > 0) {
            for (var i = 0; i < len; i++) {
                if ($('.weibo_text').val().charCodeAt(i) > 255) {
                    temp += 2;
                } else {
                    temp ++;
                }
            }
            var result = parseInt((total - temp)/2)+1;
            if (result >= 0) {
                $('.weibo_num').html('还可以输入<strong class="num">' + result + '</strong>字');
                $('#send').removeAttr('disabled');
                return true;
            } else {
                $('.weibo_num').html('已超出<strong class="num" style="color: red;">' + (-result) + '</strong>字');
                $('#send').attr('disabled','disabled');
                return false;
            }
        }

    }

    function appblog(uid,bid){
        $.ajax({
            type : "POST",
            url:'doAction2.php?act=approveblog',
            dataType : "json",
            data :{uid:uid,blogId:bid},
            success :function(response){
                if(response.errno==0){
                    alert(response.mes);
                    $('#appbid-'+bid).css('color','red');
                    $('#appbid-'+bid).html('已点赞('+response.approveNum+')');
                }else{
                    alert(response.mes);
                    $('#appbid-'+bid).css('color','#8f8080');
                    $('#appbid-'+bid).html('<span class="glyphicon glyphicon-heart" data-toggle="tooltip" title="喜欢"></span>('+response.approveNum+')');
                }
            },
            error:function(){alert('error')}
        });
    }

    function colblog(uid,bid){
        $.ajax({
            type : "POST",
            url:'doAction2.php?act=collectblog',
            dataType : "json",
            data :{uid:uid,blogId:bid},
            success :function(response){
                if(response.errno==0){
                    alert(response.mes);
                    $('#colbid-'+bid).css('color','red');
                    $('#colbid-'+bid).html('<span class="glyphicon glyphicon-plus" style="color:#00a1d8" data-toggle="tooltip" title="撤销收藏"></span>('+response.collectNum+')');
                }else{
                    alert(response.mes);
                    $('#colbid-'+bid).css('color','#8f8080');
                    $('#colbid-'+bid).html('<span class="glyphicon glyphicon-plus" data-toggle="tooltip" title="收藏"></span>('+response.collectNum+')');
                }
            },
            error:function(){alert('error')}
        });
    }

    function deleteBlog(blogId){
        $.ajax({
            type : "POST",
            url:'doAction2.php?act=delBlog',
            dataType : "json",
            data :{blogId:blogId},
            success :function(response){
                if(response.errno==0){
                    alert(response.mes);
                    //location.href="search.php?keywords="+keywords;
                    location.reload();//重新加载该页面
                }else{
                    alert(response.mes);
                }
            },
            error:function(){alert('error')}
        });
    }
</script>

</html>