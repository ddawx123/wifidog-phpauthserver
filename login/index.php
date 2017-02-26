<!DOCTYPE HTML>
<!--
	Dimension by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>上网认证系统</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<?php
			@$gw_id = $_REQUEST['gw_id'];
			@$gw_address = $_REQUEST['gw_address'];
			@$gw_port = $_REQUEST['gw_port'];
            if(empty($gw_address) || empty($gw_port)){
				die("非法操作，系统拒绝处理您的请求。");
            }
            include("../conn.php");
            session_start(10800);
            if (!empty($_SESSION['loginname'])) {
				header("Location: ../portal/?gw_address=".$gw_address."&gw_port=".$gw_port."&gw_id=".$gw_id."&url=http://www.dingstudio.cn");
			}
        ?>
		<link rel="stylesheet" href="../assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="../assets/css/ie9.css" /><![endif]-->
		<noscript><link rel="stylesheet" href="../assets/css/noscript.css" /></noscript>
	</head>
	<body>

<!--[if lt IE 9]>
<script>document.location="../ieblock.html";</script>
<![endif]-->

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="logo">
							<span class="icon fa-diamond"></span>
						</div>
						<div class="content">
							<div class="inner">
								<h1>访客上网认证系统</h1>
								<p><!--[-->本网络需要通过web认证后方可继续使用，获取账号请联系该网络管理员或所有者！ <a href="tel:15857587920">联系网管</a><!--]--><br />
								<!--[-->The network is protected by the Internet authentication system<!--]--></p>
							</div>
						</div>
						<nav>
							<ul>
								<li><a href="#login">登录网络</a></li>
								<li><a href="#regsiter">申请账号</a></li>
								<li><a href="#contact">意见反馈</a></li>
								<!--<li><a href="#elements">Elements</a></li>-->
							</ul>
						</nav>
					</header>

				<!-- Main -->
					<div id="main">

						<!-- login -->
							<article id="login">
								<h2 class="major">登录到网络</h2>
								<span class="image main"><img src="../images/pic01.jpg" alt="" /></span>
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
												header("Location: http://".$gw_address.":".$gw_port."/wifidog/auth?token=".$token."&url=".$_GET['url']);//跳转到wifidog的上网授权回调地址，正式申请上网～
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
									<div class="field half first">
										<label for="us_name">账号</label>
										<input type="text" id="us_name" name="us_name" placeholder="在此键入您的账号" />
									</div>
									<div class="field half">
										<label for="us_pass">口令</label>
										<input type="password" id="us_pass" name="us_pass" placeholder="在此键入您的口令" />
									</div>
									<ul class="actions">
										<li><input type="submit" id="btnLogin" name="btnLogin" value="登录" class="special" /></li>
										<li><input type="reset" value="重置" /></li>
									</ul>
								</form>
							</article>

						<!-- regsiter -->
							<article id="regsiter">
								<h2 class="major">帐号申请系统</h2>
								<span class="image main"><img src="../images/pic03.jpg" alt="" /></span>
								<form method="post" action="../regsiter">
									<div class="field half first">
										<label for="newuser">新账号</label>
										<input type="text" id="newuser" name="newuser" placeholder="键入账号，建议英文" ondragenter="return false;" oncontextmenu="return false;" style="ime-mode: disabled" />
									</div>
									<div class="field half">
										<label for="newpasswd">新口令</label>
										<input type="password" id="newpasswd" name="newpasswd" placeholder="键入口令" ondragenter="return false;" oncontextmenu="return false;" style="ime-mode: disabled" />
									</div>
									<ul class="actions">
										<li><input type="submit" value="注册" class="special" /></li>
										<li><input type="reset" value="重置" /></li>
									</ul>
								</form>
							</article>

						<!-- Contact -->
							<article id="contact">
								<h2 class="major">在线反馈系统</h2>
								<form method="post" action="http://portal.home.ipv4.dingstudio.cn/dingstudio/secauth/process.php?act=post">
									<div class="field half first">
										<label for="name">用户称谓</label>
										<input type="text" name="name" id="name" placeholder="昵称" />
									</div>
									<div class="field half">
										<label for="ctu">电邮地址</label>
										<input type="text" name="ctu" id="ctu" placeholder="联系方式(电话或邮箱、QQ皆可)" />
									</div>
									<div class="field">
										<label for="message">留言内容</label>
										<textarea name="message" id="message" placeholder="留言内容"></textarea>
									</div>
									<ul class="actions">
										<li><input type="submit" value="投递留言" class="special" /></li>
										<li><input type="reset" value="重置" /></li>
									</ul>
								</form>
							</article>

						<!-- Elements -->
							<article id="elements">
								<h2 class="major">Elements</h2>

								<section>
									<h3 class="major">Text</h3>
									<p>This is <b>bold</b> and this is <strong>strong</strong>. This is <i>italic</i> and this is <em>emphasized</em>.
									This is <sup>superscript</sup> text and this is <sub>subscript</sub> text.
									This is <u>underlined</u> and this is code: <code>for (;;) { ... }</code>. Finally, <a href="#">this is a link</a>.</p>
									<hr />
									<h2>Heading Level 2</h2>
									<h3>Heading Level 3</h3>
									<h4>Heading Level 4</h4>
									<h5>Heading Level 5</h5>
									<h6>Heading Level 6</h6>
									<hr />
									<h4>Blockquote</h4>
									<blockquote>Fringilla nisl. Donec accumsan interdum nisi, quis tincidunt felis sagittis eget tempus euismod. Vestibulum ante ipsum primis in faucibus vestibulum. Blandit adipiscing eu felis iaculis volutpat ac adipiscing accumsan faucibus. Vestibulum ante ipsum primis in faucibus lorem ipsum dolor sit amet nullam adipiscing eu felis.</blockquote>
									<h4>Preformatted</h4>
									<pre><code>i = 0;

while (!deck.isInOrder()) {
    print 'Iteration ' + i;
    deck.shuffle();
    i++;
}

print 'It took ' + i + ' iterations to sort the deck.';</code></pre>
								</section>

								<section>
									<h3 class="major">Lists</h3>

									<h4>Unordered</h4>
									<ul>
										<li>Dolor pulvinar etiam.</li>
										<li>Sagittis adipiscing.</li>
										<li>Felis enim feugiat.</li>
									</ul>

									<h4>Alternate</h4>
									<ul class="alt">
										<li>Dolor pulvinar etiam.</li>
										<li>Sagittis adipiscing.</li>
										<li>Felis enim feugiat.</li>
									</ul>

									<h4>Ordered</h4>
									<ol>
										<li>Dolor pulvinar etiam.</li>
										<li>Etiam vel felis viverra.</li>
										<li>Felis enim feugiat.</li>
										<li>Dolor pulvinar etiam.</li>
										<li>Etiam vel felis lorem.</li>
										<li>Felis enim et feugiat.</li>
									</ol>
									<h4>Icons</h4>
									<ul class="icons">
										<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
										<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
										<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
										<li><a href="#" class="icon fa-github"><span class="label">Github</span></a></li>
									</ul>

									<h4>Actions</h4>
									<ul class="actions">
										<li><a href="#" class="button special">Default</a></li>
										<li><a href="#" class="button">Default</a></li>
									</ul>
									<ul class="actions vertical">
										<li><a href="#" class="button special">Default</a></li>
										<li><a href="#" class="button">Default</a></li>
									</ul>
								</section>

								<section>
									<h3 class="major">Table</h3>
									<h4>Default</h4>
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													<th>Name</th>
													<th>Description</th>
													<th>Price</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Item One</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Two</td>
													<td>Vis ac commodo adipiscing arcu aliquet.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Three</td>
													<td> Morbi faucibus arcu accumsan lorem.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Four</td>
													<td>Vitae integer tempus condimentum.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Five</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"></td>
													<td>100.00</td>
												</tr>
											</tfoot>
										</table>
									</div>

									<h4>Alternate</h4>
									<div class="table-wrapper">
										<table class="alt">
											<thead>
												<tr>
													<th>Name</th>
													<th>Description</th>
													<th>Price</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Item One</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Two</td>
													<td>Vis ac commodo adipiscing arcu aliquet.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Three</td>
													<td> Morbi faucibus arcu accumsan lorem.</td>
													<td>29.99</td>
												</tr>
												<tr>
													<td>Item Four</td>
													<td>Vitae integer tempus condimentum.</td>
													<td>19.99</td>
												</tr>
												<tr>
													<td>Item Five</td>
													<td>Ante turpis integer aliquet porttitor.</td>
													<td>29.99</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2"></td>
													<td>100.00</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</section>

								<section>
									<h3 class="major">Buttons</h3>
									<ul class="actions">
										<li><a href="#" class="button special">Special</a></li>
										<li><a href="#" class="button">Default</a></li>
									</ul>
									<ul class="actions">
										<li><a href="#" class="button">Default</a></li>
										<li><a href="#" class="button small">Small</a></li>
									</ul>
									<ul class="actions">
										<li><a href="#" class="button special icon fa-download">Icon</a></li>
										<li><a href="#" class="button icon fa-download">Icon</a></li>
									</ul>
									<ul class="actions">
										<li><span class="button special disabled">Disabled</span></li>
										<li><span class="button disabled">Disabled</span></li>
									</ul>
								</section>

								<section>
									<h3 class="major">Form</h3>
									<form method="post" action="#">
										<div class="field half first">
											<label for="demo-name">Name</label>
											<input type="text" name="demo-name" id="demo-name" value="" placeholder="Jane Doe" />
										</div>
										<div class="field half">
											<label for="demo-email">Email</label>
											<input type="email" name="demo-email" id="demo-email" value="" placeholder="jane@untitled.tld" />
										</div>
										<div class="field">
											<label for="demo-category">Category</label>
											<div class="select-wrapper">
												<select name="demo-category" id="demo-category">
													<option value="">-</option>
													<option value="1">Manufacturing</option>
													<option value="1">Shipping</option>
													<option value="1">Administration</option>
													<option value="1">Human Resources</option>
												</select>
											</div>
										</div>
										<div class="field half first">
											<input type="radio" id="demo-priority-low" name="demo-priority" checked>
											<label for="demo-priority-low">Low</label>
										</div>
										<div class="field half">
											<input type="radio" id="demo-priority-high" name="demo-priority">
											<label for="demo-priority-high">High</label>
										</div>
										<div class="field half first">
											<input type="checkbox" id="demo-copy" name="demo-copy">
											<label for="demo-copy">Email me a copy</label>
										</div>
										<div class="field half">
											<input type="checkbox" id="demo-human" name="demo-human" checked>
											<label for="demo-human">Not a robot</label>
										</div>
										<div class="field">
											<label for="demo-message">Message</label>
											<textarea name="demo-message" id="demo-message" placeholder="Enter your message" rows="6"></textarea>
										</div>
										<ul class="actions">
											<li><input type="submit" value="Send Message" class="special" /></li>
											<li><input type="reset" value="Reset" /></li>
										</ul>
									</form>
								</section>

							</article>

					</div>

				<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; Untitled. Design: <a href="http://www.dingstudio.cn">DingStudio</a>.</p>
					</footer>

			</div>

		<!-- BG -->
			<div id="bg"></div>

		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>
