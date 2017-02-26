<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
		@$gw_id = $_REQUEST['gw_id'];
        if(empty($gw_address) || empty($gw_port)){
			die("非法操作，系统拒绝处理您的请求。");
        }
        include("../conn.php");
        session_start(10800);
        if (!empty(@$_SESSION['loginname'])) {
			header("Location: ../portal/?gw_address=".$gw_address."&gw_port=".$gw_port."&gw_id=".$gw_id."&url=http://www.dingstudio.cn");
		}
    ?>
    <title>上网web认证系统|需要认证</title>
</head>
<body>
    <div id="container" align="center">
        <div id="header" align="center"><strong>上网认证系统2.0（HTML5支持已被禁用）</strong></div>
        <div id="loginbox" align="center">
            <form method="post">
			<?php
			if (isset($_POST['btnLogin'])) {
				if (!empty($_POST['us_name']) && !empty($_POST['us_pass'])) {
					$username=mysqli_real_escape_string($conn,$_POST['us_name']);
					$password=mysqli_real_escape_string($conn,$_POST['us_pass']);
					$sql="select * from users where userid='{$username}'";
					$res=mysqli_query($conn,$sql);
					if (mysqli_num_rows($res)>0) {
						$row=mysqli_fetch_array($res);
						if ($password==$row['userpass']) {
							if ($row['status']=="false") {//账户可用性确认
								die('<script>alert("您的账户处于审核期，请等待管理员激活您的账号后方可正常登陆。");</script>');//返回账户未通过审核
							}
							$token = md5(uniqid());                                       
							$_SESSION['loginname']=$username;
							$_SESSION['loginurl']=@$_GET['url'];
							$sql="update users set usertoken='{$token}' where userid='{$username}'";
							mysqli_query($conn,$sql);
							mysqli_close($conn);//释放服务器mysql资源，关闭连接
							header("Location: http://".$gw_address.":".$gw_port."/wifidog/auth?token=".$token);//跳转到wifidog的上网授权回调地址，正式申请上网～
						}
						else {
							echo '<script>alert("账号和密码不匹配，请重试！");</script>';
						}
					}
					else {
						echo '<script>alert("无效的账号，请重试！");</script>';
					}
				}
				else {
					echo '<script>alert("请输入账号和密码哦。");</script>';
				}
			}
			?>
                <p align="center"><label for="us_name">用户账号：</label><input type="text" id="us_name" name="us_name" placeholder="在此键入您的账号" /></p>
                <p align="center"><label for="us_pass">用户口令：</label><input type="password" id="us_pass" name="us_pass" placeholder="在此键入您的口令" /></p>
                <p align="center"><input type="submit" id="btnLogin" name="btnLogin" value="登录到网络" class="special" /></p>
            </form>
        </div>
        <div id="notice" align="center">注意：您正在通过旧版上网认证接口登录网络，该接口不支持用户注册。如果需要注册，请使用支持html5的浏览器接入本网络！</div>
    </div>
    <div id="footer" align="center">上网认证系统2.0-HTML5兼容模式|<a href="http://www.dingstudio.cn">小丁工作室</a>版权所有</div>
</body>
</html>
