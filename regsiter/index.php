<?php
header('Content-type: text/html; charset=utf-8');//配置全局以json方式输出执行结果，并确认编码固定为伟大的utf-8
include("../conn.php");//存在数据库连接文件即装载
if ($regsiter_allowed == 'false') {
	die('非常抱歉，管理员关闭了注册通道！您现在不能注册。');
}
if (!empty($_POST['newuser']) && !empty($_POST['newpasswd'])) {//表单提交判断程序
	$username=mysqli_real_escape_string($conn,$_POST['newuser']);//用户名处理
	$password=mysqli_real_escape_string($conn,$_POST['newpasswd']);//密码处理
	$sql="insert into " . $mysql_dbname . ".users (userid,userpass) values ('{$username}','{$password}')";//插入该用户的新记录
	mysqli_query($conn,$sql);//执行sql
	mysqli_close($sql);//释放服务器mysql资源，关闭连接
	echo '恭喜您，注册请求提交成功！请等候管理员审核并激活您的上网账号。';//返回注册成功的消息
}
else {
	die('用户名或密码均不能为空或输入内容存在非法字段，请重试！');//返回注册失败的消息
}
?>
