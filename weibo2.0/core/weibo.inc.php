<?php
/**添加微博
 * @return string
 */
function addBlog($isManager=1){
    $arr=$_POST;
    $arr['time']=date('Y-m-d H:i:s');
    //print_r($arr);

    if($isManager==1){
        $path="./uploads";
        $uploadFiles=uploadFile($path);
        if(is_array($uploadFiles)&&$uploadFiles){
            foreach($uploadFiles as $key=>$uploadFile){
                thumb($path."/".$uploadFile['name'],"../image_50/".$uploadFile['name'],50,50);
                thumb($path."/".$uploadFile['name'],"../image_220/".$uploadFile['name'],220,220);
                thumb($path."/".$uploadFile['name'],"../image_350/".$uploadFile['name'],350,350);
                thumb($path."/".$uploadFile['name'],"../image_800/".$uploadFile['name'],800,800);
            }
        }
    }else{
        $path="admin/uploads";
        $uploadFiles=uploadFile($path);
        //var_dump($uploadFiles);
        if(is_array($uploadFiles)&&$uploadFiles){
            foreach($uploadFiles as $key=>$uploadFile){
                thumb($path."/".$uploadFile['name'],"image_50/".$uploadFile['name'],50,50);
                thumb($path."/".$uploadFile['name'],"image_220/".$uploadFile['name'],220,220);
                thumb($path."/".$uploadFile['name'],"image_350/".$uploadFile['name'],350,350);
                thumb($path."/".$uploadFile['name'],"image_800/".$uploadFile['name'],800,800);
            }
        }

    }
    //$path="http://localhost/course_design_microblog/admin/uploads";

    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $res=insert($link,"blog",$arr);
    $blogId=getInsertId($link);
    //print_r($blogId);
    if($res&&$blogId){
        foreach($uploadFiles as $uploadFile){
            $arr1['blogId']=$blogId;
            $arr1['albumPath']=$uploadFile['name'];
            addAlbum($arr1);
            //echo $arr1['blogId'];
            //echo $arr1['albumPath'];
        }
        //微博数加一
        $sql="select blogNum from user_information where uid={$arr['uid']}";
        $res2=fetchOne($link,$sql);
        $arr2['blogNum']=$res2['blogNum']+1;
        $where="uid={$arr['uid']}";
        update($link,'user_information',$arr2,$where);

        $where="id ={$arr['uid']}";
        $arrOfLastTime['lastTimeBlog']=$arr['time'];
        $res2=update($link,'user',$arrOfLastTime,$where);
       // var_dump($res2);

        $mes="<p>添加成功!</p><a href='addBlog.php' target='mainFrame'>继续添加</a>|<a href='listBlog.php' target='mainFrame'>查看微博列表</a>";
    }else{
        foreach($uploadFiles as $uploadFile){
            if(file_exists("../image_800/".$uploadFile['name'])){
                unlink("../image_800/".$uploadFile['name']);
            }
            if(file_exists("../image_50/".$uploadFile['name'])){
                unlink("../image_50/".$uploadFile['name']);
            }
            if(file_exists("../image_220/".$uploadFile['name'])){
                unlink("../image_220/".$uploadFile['name']);
            }
            if(file_exists("../image_350/".$uploadFile['name'])){
                unlink("../image_350/".$uploadFile['name']);
            }
        }
        $mes="<p>添加失败!</p><a href='addPro.php' target='mainFrame'>重新添加</a>";

    }
    return $mes;
}

/**
 * 根据id得到微博的详细信息
 * @param int $id
 * @return array
 */
function getBlogById($id){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $sql="select p.id,p.uid,p.time,p.text,c.username from blog as p join user c on p.uid=c.id where p.id=".$id;
    $row=fetchOne($link,$sql);
    return $row;
}

/**
 *编辑微博
 * @param int $id
 * @return string
 */
function editBlog($id){
    $arr=$_POST;
    $path="./uploads";
    $uploadFiles=uploadFile($path);
    if(is_array($uploadFiles)&&$uploadFiles){
        foreach($uploadFiles as $key=>$uploadFile){
            thumb($path."/".$uploadFile['name'],"../image_50/".$uploadFile['name'],50,50);
            thumb($path."/".$uploadFile['name'],"../image_220/".$uploadFile['name'],220,220);
            thumb($path."/".$uploadFile['name'],"../image_350/".$uploadFile['name'],350,350);
            thumb($path."/".$uploadFile['name'],"../image_800/".$uploadFile['name'],800,800);
        }
    }
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $where="id={$id}";
    update($link,"blog",$arr,$where);
    $blogId=$id;
    if($blogId){
        if($uploadFiles &&is_array($uploadFiles)){
            foreach($uploadFiles as $uploadFile){
                $arr1['blogId']=$blogId;
                $arr1['albumPath']=$uploadFile['name'];
                addAlbum($arr1);
            }
        }
        $mes="<p>编辑成功!</p><a href='listBlog.php' target='mainFrame'>查看微博列表</a>";
    }else{
        if(is_array($uploadFiles)&&$uploadFiles){
            foreach($uploadFiles as $uploadFile){
                if(file_exists("../image_800/".$uploadFile['name'])){
                    unlink("../image_800/".$uploadFile['name']);
                }
                if(file_exists("../image_50/".$uploadFile['name'])){
                    unlink("../image_50/".$uploadFile['name']);
                }
                if(file_exists("../image_220/".$uploadFile['name'])){
                    unlink("../image_220/".$uploadFile['name']);
                }
                if(file_exists("../image_350/".$uploadFile['name'])){
                    unlink("../image_350/".$uploadFile['name']);
                }
            }
        }
        $mes="<p>编辑失败!</p><a href='listBlog.php' target='mainFrame'>重新编辑</a>";

    }
    return $mes;
}

function delBlog($id,$uid){
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
            foreach($proImgs as $proImg){
                if(file_exists("uploads/".$proImg['albumPath'])){
                    unlink("uploads/".$proImg['albumPath']);
                }
                if(file_exists("../image_50/".$proImg['albumPath'])){
                    unlink("../image_50/".$proImg['albumPath']);
                }
                if(file_exists("../image_220/".$proImg['albumPath'])){
                    unlink("../image_220/".$proImg['albumPath']);
                }
                if(file_exists("../image_350/".$proImg['albumPath'])){
                    unlink("../image_350/".$proImg['albumPath']);
                }
                if(file_exists("../image_800/".$proImg['albumPath'])){
                    unlink("../image_800/".$proImg['albumPath']);
                }

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
        $mes="删除成功!<br/><a href='listBlog.php' target='mainFrame'>查看微博列表</a>";
    }else{
        $mes="删除失败!<br/><a href='listBlog.php' target='mainFrame'>重新删除</a>";
    }
    return $mes;
}

function getAllImgByBlogId($id){

    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $sql="select a.albumPath from album a where blogId={$id}";
    $rows=fetchAll($link,$sql);
    return $rows;
}

/**
 * 检查该用户下是否有微博
 * @param int $cid
 * @return array
 */
function checkBlogExist($uid){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    $sql="select * from blog where uid={$uid}";
    $rows=fetchAll($link,$sql);
    return $rows;
}

/**
 * 增加一条评论
 */
function addDiscuss($blogId){
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


    //是否转发
    if($arr['isTran']==1){
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
    }

}

/**
 * 删除一条评论
 */
function delDiscuss($did,$blogId,$duid){
    //删除评论
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");


    //该微博评论数减1
    $sql="select discussNum from blog where id={$blogId}";
    $row=fetchOne($link,$sql);
    $arr2['discussNum']=$row['discussNum']-1;
    $where="id={$blogId}";
    update($link,"blog",$arr2,$where);

    //若该条评论同时转发了微博，则删除该评论的同时把转发的微博删除
    $sql="select afterTranBlogId from discuss where did={$did}";
    $row2=fetchOne($link,$sql);
    $where="id={$row2['afterTranBlogId']}";
    delete($link,"blog",$where);



    //判断该条评论是否有转发
    $sql="select isTran from discuss where did={$did}";
    $isConTran=fetchOne($link,$sql);
    print_r($isConTran);
    if($isConTran['isTran']!=0){
        //微博数减1
        $sql="select blogNum from user_information where uid={$duid}";
        $res2=fetchOne($link,$sql);
        $where="uid={$duid}";
        $arr3['blogNum']=$res2['blogNum']-1;
        update($link,'user_information',$arr3,$where);


        //该微博的转发数-1
        $sql="select tranNum from blog where id={$blogId}";
        $res6=fetchOne($link,$sql);
        $arr6['tranNum']=$res6['tranNum']-1;
        $where="id={$blogId}";
        update($link,'blog',$arr6,$where);
    }

    //删除该条评论
    $where="did={$did}";
    delete($link,"discuss",$where);
}