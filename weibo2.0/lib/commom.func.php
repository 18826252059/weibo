<?php
/**
 * Created by PhpStorm.
 * User: star
 * Date: 2016/5/15
 * Time: 11:45
 */
error_reporting(0);
function alertMes($mes,$url){
    header("Content-type:text/html;charset=utf-8");
    echo "<script>alert('{$mes}')</script>";
    echo "<script>window.location='{$url}'</script>";
}