<?php

session_start();
$image=imagecreatetruecolor(100,30);
$bgcolor=imagecolorallocate($image,255,255,255);
imagefill($image,0,0,$bgcolor);



/*随机生成四个数字
for($i=0;$i<4;$i++){
    $fontsize=6;
    $fontcolor=imagecolorallocate($image,rand(0,120),rand(0,120),rand(0,120));
    $fontcontent = rand(0, 9);

    $x=($i*100/4)+rand(5,10);
    $y=rand(5,10);

    imagestring($image,$fontsize,$x,$y,$fontcontent,$fontcolor);

}*/

/*
 * 字母数字混合
 * */

$captch_code="";
for($i=0;$i<4;$i++){
    $fontsize=6;
    $fontcolor=imagecolorallocate($image,rand(0,120),rand(0,120),rand(0,120));
    $data='abcdefghijklmnopqrstuvwxyz3456789';
    $fontcontent=substr($data,rand(0,strlen($data)),1);
    $captch_code.=$fontcontent;

    $x=($i*100/4)+rand(5,10);
    $y=rand(5,10);
    imagestring($image,$fontsize,$x,$y,$fontcontent,$fontcolor);
}

$_SESSION['authcode']=$captch_code;


for($i=0;$i<200;$i++){
    $pointercolor=imagecolorallocate($image,rand(50,200),rand(50,200),rand(50,200));
    imagesetpixel($image,rand(1,99),rand(1,29),$pointercolor);
}

for($i=0;$i<3;$i++){
    $linecolor=imagecolorallocate($image,rand(80,220),rand(80,220),rand(80,220));
    imageline($image,rand(1,99),rand(1,29),rand(1,99),rand(1,29),$linecolor);
}
header('content-type:image/png');
imagepng($image);
//end
imagedestroy($image);