<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2016/5/15
 * Time: 11:45
 */

/**
 * 连接数据库
 * @return mysqli
 */
date_default_timezone_set("PRC");//调整时区
function connect(){
require_once ('../configs/configs.php');
    /*$link=mysqli_connect(DB_HOST,DB_HOST,DB_PWD) or die("数据库连接失败Error".mysqli_errno().":".mysqli_error());
    mysqli_set_charset($link,DB_CHARSET);
    mysqli_query($link, "SET NAMES 'utf8'");
    mysqli_select_db($link,DB_DBNAME) or die("指定数据库打开失败");
    return $link;*/

    //$link=mysqli_connect(DB_HOST,DB_HOST,DB_PWD);
    $link=mysqli_connect( 'localhost','root','');
    //mysqli_select_db($link,DB_DBNAME);
    mysqli_select_db($link,'microblog');
    mysqli_query($link, "SET NAMES 'utf8'");
    return $link;
}

//增加数据
function insert($link,$table,$array){
    $keys=join(",",array_keys($array));
    $vals="'".join("','",array_values($array))."'";
    $sql="insert {$table}($keys) values({$vals})";
    mysqli_query($link,$sql);
    return mysqli_insert_id($link);

    /* $key = join(",", array_keys($array));
     $vals = "'" . join(",", array_values($array)) . "'";
     $sql = "insert {$table}($key) values({$vals})";
     mysqli_query($link, $sql);
     return mysqli_insert_id($link);*/
}

/*
 * 更新
 */
//更新update imooc_admin set username='king' where id=1;

function update($link,$table,$array,$where=null){
    $str="";
    foreach($array as $key=>$val){
        if($str==null){
            $sep="";
        }else{
            $sep=",";
        }
        $str.=$sep.$key."='".$val."'";
    }
    $sql="update {$table} set {$str} ".($where==null?null:" where ".$where);
    $result=mysqli_query($link,$sql);
    //var_dump($result);
    //var_dump(mysql_affected_rows());exit;
    if($result){
        return mysqli_affected_rows($link);
    }else{
        return false;
    }
}
/**
 * 删除
 * @param $link
 * @param $table
 * @param null $where
 * @return int
 */

function delete($link,$table,$where=null){
    $where=$where==null?null:" where ".$where;
    $sql="delete from {$table} {$where}";
    mysqli_query($link,$sql);
    return mysqli_affected_rows($link);
}

/**
 * 得到一条指定的记录
 * @param $link
 * @param $sql
 * @param int $result_type
 * @return array|null
 */
function fetchOne($link,$sql,$result_type=MYSQLI_ASSOC){
    $result=mysqli_query($link,$sql);
    $row=mysqli_fetch_array($result,$result_type);
    //$row=mysqli_fetch_array($result);
    return $row;
}


/**
 * 得到结果集的所有记录
 * @param $link
 * @param $sql
 * @param int $result_type
 * @return array|null
 */

function fetchAll($link,$sql,$result_type=MYSQLI_ASSOC){
    $result=mysqli_query($link,$sql);
    while(@$row=mysqli_fetch_array($result,$result_type)){
        $rows[]=$row;
    }
    return $rows;
}
/**
 * 得到结果集中的记录条数
 * @param $link
 * @param $sql
 * @return int
 */
function getResultNum($link,$sql){
    $result=mysqli_query($link,$sql);
    return mysqli_num_rows($result);
}

//得到上一步插入记录的ID号
function getInsertId($link){
    return mysqli_insert_id($link);
}