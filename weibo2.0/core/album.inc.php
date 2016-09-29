<?php
function addAlbum($arr){
    $link=mysqli_connect( 'localhost','root','');
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    insert($link,"album",$arr);
    //echo "插入成功！";
}