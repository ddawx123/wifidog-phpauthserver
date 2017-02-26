<?php
header('Content-type: application/json; charset=utf-8');
if (_get('act') == 'logout') {
	session_start(10800);
	if (empty($_SESSION['loginname'])) {
		die("当前终端没有有效会话或不在本web上网认证系统管控区域内，无需注销！");
	}
	else {
		include("../conn.php");
		$sql="select * from users where userid='{$_SESSION['logiinname']}'";//读取数据库，检索用户登录信息
		$res=mysqli_query($conn,$sql);//执行sql
		if (mysqli_num_rows($res)>0) {//核实数据
			$row=mysqli_fetch_array($res);//载入数组
			$token = $row['usertoken'];//取用户token值
			$sql="update users set usertoken='' where userid={$_SESSION['logiinname']}";//废除数据库中的token
			mysqli_query($conn,$sql);//执行sql
			mysqli_close($conn);//释放服务器mysql资源，关闭连接
		}
		else {
			die("后端服务程序返回异常值，暂时无法注销您的账号。请直接断开网络，10分钟后会自动注销！");
		}
		session_destroy();//摧毁原有会话
		echo '注销成功！您的互联网会话将在下次重新连接时要求再次认证，如果没有提示认证，则说明您的设备已被管理员加入白名单免认证列表！';//显示执行成功的消息
		header("Location: http://".$gw_address.":".$gw_port."/wifidog/auth?logout=1&token=".$token);//从wifidog销毁会话
	}
}
else {
	session_start(10800);
	include("../conn.php");
	$token = $_GET['token'];
	$sql="select * from users where usertoken='{$token}'";
	$res=mysqli_query($conn,$sql);
	if (mysqli_num_rows($res)>0) {
		echo ("Auth: 1\n");
		echo ("Messages: Allow Access\n");
		exit;
	}
	else {
		echo ("Auth: 0\n");
		echo ("Messages: No Access\n");
		exit;
	}
}
function _get($gstr){
    $val = !empty($_GET[$gstr]) ? $_GET[$gstr] : null;
    return $val;
}
?>