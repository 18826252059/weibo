<?php
require_once('PHPMailer/class.phpmailer.php');
require_once("PHPMailer/class.smtp.php");
header("content-type:text/html;charset=utf-8");
session_start();
$username=$_GET['username'];
$email=$_GET['mail'];
$captcha=rand(0,9)*1+rand(0,9)*10+rand(0,9)*100+rand(0,9)*1000+rand(0,9)*10000+rand(0,9)*100000;
//$_SESSION['captcha']=$captcha;
ini_set('date.timezone','Asia/Shanghai');
$mail  = new PHPMailer();

$mail->CharSet    ="UTF-8";                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
$mail->IsSMTP();                            // 设定使用SMTP服务
$mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
$mail->SMTPSecure = "ssl";                  // SMTP 安全协议
$mail->Host       = "smtp.163.com";       // SMTP 服务器
$mail->Port       = 465;                    // SMTP服务器的端口号
$mail->Username   = "server_tell@163.com";  // SMTP服务器用户名
$mail->Password   = "nc00B384";        // SMTP服务器密码
$mail->SetFrom('server_tell@163.com', 'Tell微博管理员');    // 设置发件人地址和名称
$mail->AddReplyTo("server_tell@163.com","Tell微博管理员");
// 设置邮件回复人地址和名称
$mail->Subject    = '星之声微博邮箱验证';                     // 设置邮件标题
// $mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
// 可选项，向下兼容考虑
$mail->MsgHTML('您好，'.$username.'用户，您的邮箱验证码是'.$captcha);                         // 设置邮件内容
$mail->AddAddress($email, $username);
//$mail->AddAttachment("images/phpmailer.gif"); // 附件
if(!$mail->Send()) {
    // echo "发送失败：".$mail->ErrorInfo;
    $arr['errno']=0;
    $arr['mes']="发送失败,请检查邮箱格式";

} else {
    //echo "恭喜，邮件发送成功！";
    $arr['errno']=1;
    $arr['mes']="发送成功,请到邮箱查看验证码";
    $arr['captcha']=$captcha;
}

echo json_encode($arr);