<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2016/5/15
 * Time: 13:55
 */

require_once "../lib/image.func.php";
//require_once "../lib/mysql.func.php";
//require_once "../include.php";
//include "../include.php";


//verifyImage();
verifyImage(1,4,30,4);

/*$con=connect();
if($con=connect()){
    echo 'ok';
}*//* $link=mysqli_connect('localhost','root','');

mysqli_select_db($link,'shopimooc');
mysqli_query($link, "SET NAMES 'utf8'");*/
/*$sql = "insert into imooc_admin(username,password,Email) values ('周10','123','975777829')";
$query = mysqli_query(connect(),$sql);
if($query){
    echo "0";
}else{
    echo "1";
}*/