<!DOCTYPE HTML>
<html>
	<head>
		<title>登录成功|上网认证系统</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<?php
            session_start(10800);
            if (empty($_SESSION['loginname'])) {
                die("您没有权限访问该页面！");
            }
			else {
				$return_url = $_SESSION['loginurl'];
				echo '<script>function logoutHandler() { document.location="../auth/?act=logout"; }</script>';
				echo '<script>function GoHistoryUrl() { document.location="'.$return_url.'"; }</script>';
			}
        ?>
		<script>function logoutHandler() { document.location="http://portal.home.ipv4.dingstudio.cn/wifi/auth.php?act=logout"; }</script>
	</head>
	<body>
        <div id="container" align="center">
            <article>
                <h2>您已通过上网认证,可以开始畅游互联网了~</h2>
                <ul>
					<input type="button" value="继续上网" class="special" onclick="GoHistoryUrl();" />&nbsp;&nbsp;<input type="button" value="注销" class="special" onclick="logoutHandler();" />
                </ul>
            </article>
        </div>
        <div id="footer" align="center"><address>Copyright 2017 <a href="http://www.dingstudio.cn">DingStudio.Tech</a>小丁工作室-WLan上网认证系统_v1.2</address></div>
	</body>
</html>
