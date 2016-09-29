<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2016/5/15
 * Time: 16:24
 */
header("content-type:text/html;charset=utf-8");
//检查账号密码是否匹配
function checkAdmin($sql){

    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");

    return fetchOne($link,$sql);
}

//检查是否已登录
function checkLogined(){
    if($_SESSION['adminId']==""&&$_COOKIE['adminId']==""){
        alertMes("请先登录","login.php");
    }
}

function addAdmin(){
    $arr=$_POST;
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    if(insert($link,"admin",$arr)){
        $mes="添加成功！<br/> <a href='addAdmin.php'>继续添加</a>
丨<a href='listAdmin.php'>查看管理员列表</a>";
    }else{
        $mes="添加失败！<br/><a href='addAdmin.php'>重新添加</a>";
    }
    return $mes;
}

/**
 * 得到所有管理员
 * @return array|null
 */
function getAllAdmin(){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $sql="select id,username,email from admin ";
    $rows=fetchAll($link,$sql);
    return $rows;
}


function getAdminByPage($pageSize=2){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");

    $sql="select * from admin";
    $totalRows=getResultNum($link,$sql);
    global $totalPage;
    $totalPage=ceil($totalRows/$pageSize);
    $page=$_REQUEST['page']?(int)$_REQUEST['page']:1;
    if($page<1||$page==null||!is_numeric($page)){
        $page=1;
    }
    if($page>=$totalPage)$page=$totalPage;
    $offset=($page-1)*$pageSize;
    $sql="select id,username,email from admin limit {$offset},{$pageSize}";
    $rows=fetchAll($link,$sql);

    return $rows;
}
/**
 * 编辑管理员信息
 * @param $id
 * @return string
 */
function editAdmin($id){
    $arr=$_POST;
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    if(update($link,"admin",$arr,"id={$id}")){
        $mes="编辑成功！<br/><a href='listAdmin.php'>查看管理员列表</a>";
    }else{
        $mes="编辑失败！<br/><a href='listAdmin.php'>请重新修改</a>";
    }
    return $mes;
}

/**
 * 删除管理员的操作
 * @param $id
 * @return string
 */
function delAdmin($id){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    if(delete($link,"admin","id={$id}")){
        $mes="删除成功<br/><a href='listAdmin.php'>查看管理员列表</a>";
    }else{
        $mes="删除失败！<br/><a href='listAdmin.php'>请重新删除</a>";
    }
    return $mes;
}

//注销管理员
function logout(){
    $_SESSION=array();
    if(isset($_COOKIE[session_name()])){
        setcookie(session_name(),"",time()-1);
    }
    if(isset($_COOKIE['adminId'])){
        setcookie("adminId","",time()-1);
    }
    if(isset($_COOKIE['adminName'])){
        setcookie("adminName","",time()-1);
    }

    //session_destroy();
    alertMes("注销成功！","login.php");
    //alertMes("登录失败，重新登录","login.php");
}

/**
 * 添加用户的操作
 * @param int $id
 * @return string
 */
function addUser(){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $arr=$_POST;
    //$arr['password']=md5($_POST['password']);
    $arr['password']=$_POST['password'];
    $arr['regTime']=date('Y-m-d H:i:s');
    //$arr['regTime']=time();
    $uploadFile=uploadFile("../uploads");
    if($uploadFile&&is_array($uploadFile)){
        $arr['face']=$uploadFile[0]['name'];
    }else{
        return "添加失败<a href='addUser.php'>重新添加</a>";
    }
    if(insert($link,"user", $arr)){
        $arr2['uid']=getInsertId($link);
        $arr2['followNum']=0;
        $arr2['fanNum']=0;
        $arr2['blogNum']=0;
        insert($link,"user_information", $arr2);
        $mes="添加成功!<br/><a href='addUser.php'>继续添加</a>|<a href='listUser.php'>查看列表</a>";
    }else{
        $filename="../uploads/".$uploadFile[0]['name'];
        if(file_exists($filename)){
            unlink($filename);
        }
        $mes="添加失败!<br/><a href='addUser.php'>重新添加</a>|<a href='listUser.php'>查看列表</a>";
    }
    return $mes;
}

/**
 * 删除用户的操作
 * @param int $id
 * @return string
 */
function delUser($id){
    $res= checkBlogExist($id);
    if(!$res){
        $link=mysqli_connect( 'localhost','root','');
        mysqli_select_db($link,'microblog');
        mysqli_query($link, "SET NAMES 'utf8'");
        $sql="select face from user where id=".$id;
        $row=fetchOne($link,$sql);
        $face=$row['face'];
        if(file_exists("../uploads/".$face)){
            unlink("../uploads/".$face);
        }
        if(delete($link,"user","id={$id}")){
            delete($link,"user_information","uid={$id}");
            $mes="删除成功!<br/><a href='listUser.php'>查看用户列表</a>";
        }else{
            $mes="删除失败!<br/><a href='listUser.php'>请重新删除</a>";
        }
        return $mes;
    }else{
        alertMes("不能删除该用户，请先删除该用户的微博", "listBlog.php");
    }

}

/**
 * 编辑用户的操作
 * @param int $id
 * @return string
 */
function editUser($id){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");

    $arr=$_POST;
    //$arr['password']=md5($_POST['password']);
    //$arr['password']=$_POST['password'];

    $uploadFile=uploadFile("../uploads");
    if($uploadFile&&is_array($uploadFile)){
        //若编辑时有上传头像，则删除原头像文件，并添加新头像
        $sql="select face from user where id=".$id;
        $row=fetchOne($link,$sql);
        $face=$row['face'];
        if(file_exists("../uploads/".$face)){
            unlink("../uploads/".$face);
        }
        $arr['face']=$uploadFile[0]['name'];
    }
    if(update($link,"user", $arr,"id={$id}")){
        $mes="编辑成功!<br/><a href='listUser.php'>查看用户列表</a>";
    }else{
        $filename="../uploads/".$uploadFile[0]['name'];
        if(file_exists($filename)){
            unlink($filename);
        }
        $mes="编辑失败!<br/><a href='listUser.php'>请重新修改</a>";
    }
    return $mes;
}

/**
 * @param $did
 * @param $blogId
 * 删除评论
 */
function delDis($did,$blogId){
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
        $mes="删除成功!<br/><a href='delDis.php?blogId={$blogId}'>查看删除列表</a>";
    }else{
        $mes="删除失败!<br/><a href='delDis.php?blogId={$blogId}'>请重新删除</a>";
    }
    return $mes;
}
