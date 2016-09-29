<?php
/**
 * 得到全部用户
 * @return array|null
 */
function getAllUser(){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $sql="select id,username from user";
    $rows=fetchAll($link,$sql);
    return $rows;
}

/**注册
 * @return string
 */
function reg(){
    $arr=$_POST;
    $arr['password']=md5($_POST['password']);
    //$arr['password']=$_POST['password'];
    $arr['regTime']=date('Y-m-d H:i:s');

    // print_r($arr);

    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");

    if(insert($link,"user", $arr)){
        $arr2['uid']=getInsertId($link);
        $arr2['followNum']=0;
        $arr2['fanNum']=0;
        $arr2['blogNum']=0;
        insert($link,"user_information", $arr2);
        $mes="注册成功!<br/>3秒钟后跳转到登陆页面!<meta http-equiv='refresh' content='3;url=login.php'/>";
    }else{
        $mes="注册失败!<br/><a href='register.html'>重新注册</a>|<a href='index.php'>查看首页</a>";
    }
    return $mes;
}


/**
 * 用户登录
 * @return string
 */
function login(){
    session_start();
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

        $password=$_POST['password'];
        //$password=md5($_POST['password']);
        $sql="select * from user where username='{$username}' and password='{$password}'";
        //$resNum=getResultNum($sql);
        $row=fetchOne($link,$sql);
        //echo $resNum;
        if($row){
            $_SESSION['uid']=$row['id'];
            $_SESSION['username']=$row['username'];
            $_SESSION['face']=$row['face'];
            $_SESSION['sex']=$row['sex'];
            $sql="select followNum,fanNum,blogNum from user_information where uid=".$row['id'];
            $row2=fetchOne($link,$sql);
            $_SESSION['followNum']=$row2['followNum'];
            $_SESSION['fanNum']=$row2['fanNum'];
            $_SESSION['blogNum']=$row2['blogNum'];
            $mes="登陆成功！<br/>3秒钟后跳转到首页<meta http-equiv='refresh' content='3;url=index.php'/>";

        }else{
            $mes="登陆失败！<a href='login.php'>重新登陆</a>";
            alertMes("账号或密码错误！","login.php");
        }

        return $mes;
    }else{
        alertMes("验证码错误！","login.php");

        return null;
    }
}

function userOut(){
    $_SESSION=array();
    /*if(isset($_COOKIE[session_name()])){
        setcookie(session_name(),"",time()-1);
    }*/
    if(isset($_COOKIE['username'])){
        setcookie('username',"",time()-1);
        setcookie('uid',"",time()-1);
        setcookie('sex',"",time()-1);
    }
    session_destroy();
    //header("location:login.php");
    echo "<script>alert('注销成功')</script>";
    echo "<script>window.location='login.php'</script>";
}


/**
 * 关注用户
 */

function follow($uid){
    $myid=$_SESSION['uid'];
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
        $mes="关注成功我的id为".$_SESSION['uid']."关注的人id是".$uid;
    }
    return $mes;
}

/**
 * 关注用户
 */

function nofollow($uid){
    $myid=$_SESSION['uid'];
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
        $mes="取关成功";
    }
    return $mes;
}

//检查是否已登录
function checkuserLogined(){
    if($_SESSION['username']==""&&$_COOKIE['username']==""){
        alertMes("你还没登录哦，请先登录！","login.php");
    }
}


function changeFace($uid){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");

    $uploadFile=uploadFile("./uploads");
    if($uploadFile&&is_array($uploadFile)){
        //若编辑时有上传头像，则删除原头像文件，并添加新头像
        $sql="select face from user where id=".$uid;
        $row=fetchOne($link,$sql);
        $face=$row['face'];
        if(file_exists("./uploads/".$face)){
            unlink("./uploads/".$face);
            //echo "删除原头像成功";
        }
        $arr['face']=$uploadFile[0]['name'];
    }
    if(update($link,"user", $arr,"id={$uid}")){
        $mes="编辑成功!<br/><a href='listUser.php'>查看用户列表</a>";
    }else{
        $filename="./uploads/".$uploadFile[0]['name'];
        if(file_exists($filename)){
            unlink($filename);
        }
        $mes="编辑失败!<br/><a href='listUser.php'>请重新修改</a>";
    }
    return $mes;
}