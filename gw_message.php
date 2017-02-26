<?php
header('Content-type: text/html; charset=utf-8');
$msg = _get('message');//import url action to variable
switch ($msg) {//action judge
	case "denied":
		DeniedTip();//Show No Permission
		break;
	case "activate":
		ActivateTip();//Show Already Login
		break;
	case "failed_validation":
		FailedValidationTip();//Show Authentication Failed
		break;
	case "unknown":
		MyErrorHandler();//No Message Show
		break;
	default:
		header('Location: ./gw_message.php?message=unknown');//Default Redirect
		break;
}
function DeniedTip() {
	echo "本次上网申请被系统拒绝了！可能的原因有：帐号或口令输入错误、账号被封禁等等。";
}
function ActivateTip() {
	echo "未知错误，无法继续。请联系网络管理人员！";
}
function FailedValidationTip() {
	echo "本次上网申请被系统拒绝了！可能的原因有：帐号或口令输入错误、账号被封禁等等。";
}
function MyErrorHandler() {
	echo "恭喜您，账号登出成功！";
}
function _get($gstr){
    $val = !empty($_GET[$gstr]) ? $_GET[$gstr] : null;
    return $val;
}
?>