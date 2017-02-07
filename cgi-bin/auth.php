<?php
/*
 *
 *****************************************************************************************************
 *    如果您看到了这个提示，那么我们很遗憾地通知您，您的空间不支持 PHP 。
 *    We regret to inform you that your web hosting not support PHP,
 *    and WifiDogPortalServer CAN'T run on it if you see this prompt.
 *
 *    也就是说，您的空间可能是静态空间，或没有安装PHP，或没有为 Web 服务器打开 PHP 支持。
 *    It means that you may have a web hosting service supporting only static resources.
 *    Is PHP successfully installed on your server?
 *    Or, is HTTP Server configured correctly?
 *
 *    如您使用虚拟主机：
 *
 *        > 联系空间商，更换空间为支持 PHP 的空间。
 *        > Contact your service provider, and let them provice a new service which supports PHP.
 *
 *    如您使用 IIS、Nginx、Lighttpd，推荐您：
 *    Using IIS、Nginx、Lighttpd? Recommend:
 *
 *        > 联系本程序开发者 > http://help.dingstudio.cn
 *        > Contact Me > http://help.dingstudio.cn
 *
 *    如您使用其它 HTTP 服务器，推荐您：
 *    Using other HTTP Server? Recommend:
 *
 *        > 访问 PHP 官方网站获取安装帮助。
 *        > Visit PHP Official Website to get the documentation of installion and configuration.
 *        > http://php.net
 *
 ******************************************************************************************************
 */

/**
 * WifiDogPortalServer with PHP
 * @author 954759397
 * @copyright (C) 2017 DingStudio
 * @version 1.2
**/
header('Content-type: application/json; charset=utf-8');//配置全局以json方式输出执行结果，并确认编码固定为伟大的utf-8
if (file_exists('conn.php')) {//检测系统配置文件是否存在
	include("conn.php");//存在数据库连接文件即装载
}
else {//不存在系统配置文件
	die('{"code":"FatalError","message":"System key files are missing,please contact the administrator.","requestId":"'.time().'"}');//找不到系统配置文件时报错
}
if ($_GET['act'] == "logout") {//上网用户注销过程
	session_start();//走会话流程
	if (empty($_SESSION['loginname'])) {//如果没有检测到会话就报错
		echo '{"code":"AccessDenied","message":"当前本机没有有效会话或不在本web上网认证系统管控区域内，无需注销！3秒后自动返回到之前的页面，如无历史页面则跳转到小丁工作室官方门户首页！","requestId":"'.time().'"}';
		sleep(3);//等待3秒后跳转到发起url
		if ($_SERVER['HTTP_REFERER'] == '') {//判断是否存在发起url，不存在时走缺省url跳转过程
			header("Location: http://www.dingstudio.cn/#/from/cas/wifiportal");//跳转到缺省url
		}
		header("Location: ".$_SERVER['HTTP_REFERER']);//跳转到发起url
	}
	$sql="select * from users where userid='{$_SESSION['logiinname']}'";//读取数据库，检索用户登录信息
	$res=mysqli_query($conn,$sql);//执行sql
	if (mysqli_num_rows($res)>0) {//核实数据
		$row=mysqli_fetch_array($res);//载入数组
		$token = $row['usertoken'];//取用户token值
		$sql="update users set usertoken='' where userid={$_SESSION['logiinname']}";//废除数据库中的token
		mysqli_query($conn,$sql);//执行sql
		mysqli_close($sql);//释放服务器mysql资源，关闭连接
	}
	session_destroy();//摧毁原有会话
	echo '{"code":"Success","message":"注销成功！您的互联网会话将在下次重新连接时要求再次认证，如果没有提示认证，则说明您的设备已被管理员加入白名单免认证列表！","requestId":"'.time().'"}';//显示执行成功的消息
	header("Location: http://".$gw_address.":".$gw_port."/wifidog/auth?logout=1&token=".$token);//从wifidog销毁会话
}
else if ($_GET['act'] == "confirm") {//上网用户认证过程
	session_start(10800);//开始会话
	if (!empty($_SESSION['loginname'])) {//确认是否已经认证，如果是的则直接转到用户中心
		header("Location: http://cas.dingstudio.cn/wifi/portal");//转到用户中心
	}
	if (!empty($_POST['us_name']) && !empty($_POST['us_pass'])) {//确认post过来的用户及密码的字符串值是否为空
		$username=mysqli_real_escape_string($conn,$_POST['us_name']);//用户名处理
		$password=mysqli_real_escape_string($conn,$_POST['us_pass']);//密码处理
		$sql="select * from users where userid='{$username}'";//查询指定用户的数据库
		$res=mysqli_query($conn,$sql);//执行sql
		if (mysqli_num_rows($res)>0) {//核实数据
			$row=mysqli_fetch_array($res);//载入数组
			if ($password==$row['userpass']) {//取用户password值并比对之前输入的值是否一致，一致则开始申请开发互联网访问权限
				echo '{"code":"Success","message":"登录成功，正在申请开放互联网访问权限！请不要关闭本页面，3秒后自动跳转到上网门户。","requestId":"'.time().'"}';//返回用户授权信息比对成功的消息
				$token = md5(uniqid());//生成token并写入字符串变量
				$_SESSION['loginname']=$username;//操作会话，写入用户名
				$_SESSION['loginurl']=@$_GET['url'];//操作会话，写入来路url
				$sql="update users set usertoken='{$token}' where userid='{$username}'";//写入token到数据库方便后期二次校验以及调用
				mysqli_query($conn,$sql);//执行sql
				mysqli_close($sql);//释放服务器mysql资源，关闭连接
				sleep(3);//等待3秒，让wifidog服务器处理一些安全事务以及减少系统负载。。
				header("Location: http://".$gw_address.":".$gw_port."/wifidog/auth?token=".$token);//跳转到wifidog的上网授权回调地址，正式申请上网～
			}
			else {//密码错误时的返回
				die('{"code":"AccessDenied","message":"无效的用户名或密码，请重试！","requestId":"'.time().'"}');//json返回密码错误
			}
		}
		else {//用户帐号不存在时的返回
			die('{"code":"AccessDenied","message":"无效的用户名或密码，请重试！","requestId":"'.time().'"}');//json返回用户帐号不存在
		}
	}
	else {//post数据为空时的返回
		die('{"code":"AccessDenied","message":"用户名或密码为空或非法，请重试！","requestId":"'.time().'"}');//json返回表单为空
	}
}
else {//没有传递act参数时的返回
	die('{"code":"AccessDenied","message":"非法操作，拒绝访问！请返回。-小丁工作室上网认证系统_v1.2","requestId":"'.time().'"}');//json返回非法调用
}
?>
