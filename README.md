# 基于wifidog的web上网认证系统-PHPVersion

## 使用前必读
### 1、本系统可以搭建在外网，然后通过路由器的防火墙放行该外网服务所在ip或域名即可。
### 2、本系统没有设计数据库、配置文件安装/生成/自动更新系统。并不是技术原因无法制作，主要是比较麻烦。。。
### 3、关于本系统数据库的安装、配置文件的调整等在后面会单独介绍！
### 4、本系统的数据库连接方式使用了mysqli，所以php版本给予5.3及以上版本。

## 使用方法及配置、安装数据库
### 1、数据库的安装：
    请先搭建一个phpMyAdmin环境，方便后续登录数据库进行sql语句操作。
    搭建完成后请进入某空闲数据库，执行建表sql指令：
    ```
    CREATE TABLE IF NOT EXISTS `users` (
    `userid` char(16)  primary key,
    `userpass` varchar(32) NOT NULL,
    `usertoken` varchar(32) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    insert into wifi.users(userid,userpass) values("admin","password123123123");
    ```
### 2、上述建表过程中最后一行代码末尾处的admin和password123123123就是首个上网认证新用户的用户名和密码！
### 3、配置conn.php
    编辑conn.php文件，修改对应数据库配置信息。使php程序可以与后端的mysql数据库以及wifidog交互、查询、更新数据。这里举一个例子：
    ```
    <?php
	$gw_address="192.168.1.1";//这个是wifidog所运行的地址，一般是你的认证网关地址。（注意这里一般不可能出现公网ip！）
	$gw_port="2060";//这个是wifidog所监听的端口，一般也是你的认证网关地址。（一样，一般都是2060。除非你自己改了端口号~~）
	$mysql_server_name="192.168.1.1:3306"; //这个是认证服务器所使用的数据库地址，一般是公网ip的数据库服务器。不过不排除你给路由器安装了一个数据库或者本地24小时运行了一台mysql服务器
    $mysql_username="dbuser";//数据库用户名
    $mysql_password="dbpasswd";//数据库密码
    $conn=mysqli_connect($mysql_server_name, $mysql_username,$mysql_password);//这个别改动，这时本系统的数据库核心连接方式代码，当然如果你有能力可以改代码那么就自行修改吧~
    mysqli_select_db($conn,"dbname");//一样，别动。
    ?>
    ```
    这里说明一下上述代码范例注释中所述的认证网关和认证服务器的区别。认证网关，就是你本地的路由器或者配置了wifidog+iptables的内网防火墙。认证服务器，就是你运行了本php认证程序的web服务器+mysql服务器。需要注意一点：一般建议认证服务器建在内网，因为这样可以避免访客通过开放的公网ip地址和端口绕过防火墙的公网信号阻断！
    还有就是关于本系统的上网认证原理以及过程，这个不会在本markdown文档中写上。稍后会在我的技术博客 blog.dingstudio.cn 中详细介绍！
