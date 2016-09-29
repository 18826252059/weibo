<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2016/6/19
 * Time: 23:05
 * ajax异步处理
 */
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


/**
 * 用户登录
 */
function doLogin(){
    //session_start();
    $username=$_POST['username'];
    $verify=$_POST['captcha'];
    $verify1=$_SESSION['authcode'];
    if($verify==$verify1){
        //addslashes():使用反斜线引用特殊 字符
        //$username=addslashes($username);
        $link=mysqli_connect( 'localhost','root','');
        mysqli_select_db($link,'microblog');
        mysqli_query($link, "SET NAMES 'utf8'");
        $username=mysqli_escape_string($link,$username);

        //$password=$_POST['password'];
        $password=md5($_POST['password']);
        $sql="select * from user where username='{$username}' and password='{$password}'";
        //$resNum=getResultNum($sql);
        $row=fetchOne($link,$sql);
        //echo $resNum;
        if($row){
            $_SESSION['uid']=$row['id'];
            $_SESSION['username']=$row['username'];

            //判断是否记住了密码
            if (isset($_POST["isSave"])){
                $isSave = $_POST["isSave"];
            }
            if (isset($isSave) == true && $isSave == "true")
            {   //设置登录cookie
                setcookie('username',$row['username'],time()+7*86400);
                setcookie('uid',$row['id'],time()+7*86400);
                setcookie('sex',$row['sex'],time()+7*86400);
            }
            else
            {   //取消cookie
                setcookie("username", "", time() - 86400);
                setcookie("uid", "", time() - 86400);
                setcookie('sex',"",time()+7*86400);
            }


            $_SESSION['face']=$row['face'];
            $_SESSION['sex']=$row['sex'];
            $sql="select followNum,fanNum,blogNum from user_information where uid=".$row['id'];
            $row2=fetchOne($link,$sql);
            $_SESSION['followNum']=$row2['followNum'];
            $_SESSION['fanNum']=$row2['fanNum'];
            $_SESSION['blogNum']=$row2['blogNum'];
            $arr['errno']=0;
            $arr['mes']="登录成功！";
        }else{
            $arr['errno']=1;
            $arr['mes']="用户名或密码错误！";
        }
        echo json_encode($arr);
    }else{
        $arr['errno']=2;
        $arr['mes']="验证码错误！";
        echo json_encode($arr);
    }
}

/**
 * 收藏微博
 */
function collectblog(){
    $uid=$_POST['uid'];
    $blogId=$_POST['blogId'];

    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");

    $sql="select * from collectblog where uid={$uid} and blogId={$blogId}";
    $res=fetchOne($link,$sql);
    if($res){
        $where="uid=$uid and blogId={$blogId}";
        delete($link,"collectblog",$where);
        $sql="select collectNum from blog where id={$blogId}";
        $collNum=fetchOne($link,$sql);
        $arr2['collectNum']=$collNum['collectNum']-1;
        $where="id={$blogId}";
        update($link,"blog",$arr2,$where);
        $arr2['errno']=1;
        $arr2['mes']='取消收藏成功';
    }else{
        /*$sql="select * from collectblog where uid={$uid} and blogId={$blogId}";
    $res1=fetchOne($link,$sql);*/
        $sql="select collectNum from blog where id={$blogId}";
        $collNum=fetchOne($link,$sql);
        $arr2['collectNum']=$collNum['collectNum']+1;
        $where="id={$blogId}";
        update($link,"blog",$arr2,$where);
        $arr['uid']=$uid;
        $arr['blogId']=$blogId;
        $arr['time']=date('Y-m-d H:i:s');
        insert($link,"collectblog",$arr);
        $arr2['errno']=0;
        $arr2['mes']='收藏成功';
//$arr2['collnum']=$newcn;
    }


    echo json_encode($arr2);
}


/**
 * 点赞微博
 */
function approveblog(){
    $uid=$_POST['uid'];
    $blogId=$_POST['blogId'];

    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");

    $sql="select * from approveblog where uid={$uid} and blogId={$blogId}";
    $res=fetchOne($link,$sql);
    if($res){
        $where="uid=$uid and blogId={$blogId}";
        delete($link,"approveblog",$where);
        $sql="select approveNum from blog where id={$blogId}";
        $appNum=fetchOne($link,$sql);
        $arr2['approveNum']=$appNum['approveNum']-1;
        $where="id={$blogId}";
        update($link,"blog",$arr2,$where);
        $arr2['errno']=1;
        $arr2['mes']='取消点赞成功';
    }else{
        /*$sql="select * from collectblog where uid={$uid} and blogId={$blogId}";
    $res1=fetchOne($link,$sql);*/
        $sql="select approveNum from blog where id={$blogId}";
        $appNum=fetchOne($link,$sql);
        $arr2['approveNum']=$appNum['approveNum']+1;
        $where="id={$blogId}";
        update($link,"blog",$arr2,$where);
        $arr['uid']=$uid;
        $arr['blogId']=$blogId;
        $arr['time']=date('Y-m-d H:i:s');
        insert($link,"approveblog",$arr);
        $arr2['errno']=0;
        $arr2['mes']='点赞成功';
//$arr2['collnum']=$newcn;
    }
    echo json_encode($arr2);
}

/**
 * 增加评论
 */
function doAddDiscuss($blogId){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $arr=$_POST;
    $arr['time']=date('Y-m-d H:i:s');
    $arr['blogId']=$blogId;
    insert($link,"discuss",$arr);
    $did=getInsertId($link);

    $sql="select discussNum from blog where id={$blogId}";
    $row=fetchOne($link,$sql);
    $arr2['discussNum']=$row['discussNum']+1;
    $where="id={$blogId}";
    update($link,"blog",$arr2,$where);

    if(isset($arr['isTran'])){
        //是否转发
        if( $arr['isTran']==1){
            $arr3['uid']=$arr['uid'];
            $arr3['text']=$arr['text'];
            $arr3['time']=$arr['time'];
            $arr3['isTran']=$arr['isTran'];
            $arr3['tranBlogId']=$arr['blogId'];
            insert($link,"blog",$arr3);

            //把转发后的微博id存进数据库
            $arr5['afterTranBlogId']=getInsertId($link);
            $where="did={$did}";
            update($link,"discuss",$arr5,$where);



            //同时转发微博数加一
            $sql="select blogNum from user_information where uid={$arr['uid']}";
            $res4=fetchOne($link,$sql);
            $arr4['blogNum']=$res4['blogNum']+1;
            $where="uid={$arr3['uid']}";
            update($link,'user_information',$arr4,$where);

            //该微博的转发数+1
            $sql="select tranNum from blog where id={$blogId}";
            $res6=fetchOne($link,$sql);
            $arr6['tranNum']=$res6['tranNum']+1;
            $where="id={$blogId}";
            update($link,'blog',$arr6,$where);

            $arr['errno']=0;
            $arr['mes']="评论并转发成功！";
        }
    } else{
        $arr['errno']=1;
        $arr['mes']="评论成功！";
    }
    echo json_encode($arr);

}

/**
 * 删除评论
 */

function doDelDiscuss($did,$blogId){
    //删除评论
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");


    //该微博评论数减1
    $sql="select discussNum from blog where id={$blogId}";
    $row=fetchOne($link,$sql);
    $arr2['discussNum']=$row['discussNum']-1;
    $where="id={$blogId}";
    $res1=update($link,"blog",$arr2,$where);

    //删除该条评论
    $where="did={$did}";
    $res2=delete($link,"discuss",$where);

    if($res1&&$res2){
        $arr['errno']=0;
        $arr['mes']="删除该评论成功！";
    }else{
        $arr['errno']=1;
        $arr['mes']="删除该评论失败！";
    }
    echo json_encode($arr);
}

/**
 * 修改密码
 */
function doChangePassword(){
    $username=$_POST['username'];
    $email=$_POST['email'];
    //$password=$_POST['password'];
    $password=md5($_POST['password']);
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");

    $sql="select * from user where username='{$username}' and email='{$email}'";
    $res=fetchOne($link,$sql);
    if($res){
        $arr['password']=$password;
        $where="username='{$username}' and email='{$email}'";
        $res2=update($link,"user",$arr,$where);
        if($res2){
            $arr['errno']=0;
            $arr['mes']="修改成功！";
        }else{
            $arr['errno']=1;
            $arr['mes']="修改失败！你填的是原来的密码吧(⊙o⊙)…";
        }
    }else{
        $arr['errno']=2;
        $arr['mes']="用户账号与邮箱不匹配！";
    }
    echo json_encode($arr);
}

/**
 * 关注好友
 */

function doFollow($uid){
    $myid=$_SESSION['uid'];
    if(isset($_COOKIE['username'])){
        $myid=$_COOKIE['uid'];
    }
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $arr['fid']=$myid;
    $arr['uid']=$uid;
    $arr['time']=date('Y-m-d H:i:s');
    $res=insert($link,"fan",$arr);
    $sql2="select followNum from user_information where uid={$myid}";
    $sql3="select fanNum from user_information where uid={$uid}";
    $res2=fetchOne($link,$sql2);
    $res3=fetchOne($link,$sql3);
    $arr2['followNum']=$res2['followNum']+1;
    $arr3['fanNum']=$res3['fanNum']+1;
    $where1="uid={$myid}";
    $where2="uid={$uid}";
    update($link,"user_information",$arr2,$where1);
    update($link,"user_information",$arr3,$where2);
    if($res){
        $arr['errno']=0;
        $arr['mes']="关注成功！";
    }else{
        $arr['errno']=1;
        $arr['mes']="关注失败！";
    }
    echo json_encode($arr);
}

/**
 * 取消关注
 */

function doNoFollow($uid){
    $myid=$_SESSION['uid'];
    if(isset($_COOKIE['username'])){
        $myid=$_COOKIE['uid'];
    }
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $arr['fid']=$myid;
    $arr['uid']=$uid;
    $where="uid={$uid} and fid={$myid}";
    $res=delete($link,"fan",$where);
    $sql2="select followNum from user_information where uid={$myid}";
    $sql3="select fanNum from user_information where uid={$uid}";
    $res2=fetchOne($link,$sql2);
    $res3=fetchOne($link,$sql3);
    $arr2['followNum']=$res2['followNum']-1;
    $arr3['fanNum']=$res3['fanNum']-1;
    $where1="uid={$myid}";
    $where2="uid={$uid}";
    update($link,"user_information",$arr2,$where1);
    update($link,"user_information",$arr3,$where2);
    if($res){
        $arr['errno']=0;
        $arr['mes']="取消关注成功！";
    }else{
        $arr['errno']=1;
        $arr['mes']="取消关注失败！";
    }
    echo json_encode($arr);
}


/**
 * 删除微博
 */
function doDelBlog($id){
    $uid=$_SESSION['uid'];
    if(isset($_COOKIE['username'])){
        $uid=$_COOKIE['uid'];
    }
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");

    $sql="select isTran,tranBlogId from blog where id={$id}";
    $myres=fetchOne($link,$sql);
    //判断该条微博是否转发其他微博
    if($myres['isTran']==1){
        //该微博的转发数-1
        $sql="select tranNum from blog where id={$myres['tranBlogId']}";
        $res6=fetchOne($link,$sql);
        $arr6['tranNum']=$res6['tranNum']-1;
        $where="id={$myres['tranBlogId']}";
        update($link,'blog',$arr6,$where);
    }

    $where="id=$id";
    $res=delete($link,"blog",$where);
    @$proImgs=getAllImgByBlogId($id);
    if($proImgs&&is_array($proImgs)){
        foreach($proImgs as $proImg){
            if(file_exists("admin/uploads/".$proImg['albumPath'])){
                unlink("admin/uploads/".$proImg['albumPath']);
            }
            if(file_exists("image_50/".$proImg['albumPath'])){
                unlink("image_50/".$proImg['albumPath']);
            }
            if(file_exists("image_220/".$proImg['albumPath'])){
                unlink("image_220/".$proImg['albumPath']);
            }
            if(file_exists("image_350/".$proImg['albumPath'])){
                unlink("image_350/".$proImg['albumPath']);
            }
            if(file_exists("image_800/".$proImg['albumPath'])){
                unlink("image_800/".$proImg['albumPath']);
            }
        }
    }

    $where1="blogId={$id}";
    $res1=delete($link,"album",$where1);
    if($res){
        //echo $uid;
        //微博数减一
        $sql="select blogNum from user_information where uid={$uid}";
        $res2=fetchOne($link,$sql);
        $where="uid={$uid}";
        $arr2['blogNum']=$res2['blogNum']-1;
        update($link,'user_information',$arr2,$where);
        delete($link,"discuss", $where1);
        delete($link,"collectblog",$where1);
        delete($link,"approveblog",$where1);
        $arr['errno']=0;
        $arr['mes']="删除成功！";
    }else{
        $arr['errno']=1;
        $arr['mes']="删除失败！";
    }
    echo json_encode($arr);

}