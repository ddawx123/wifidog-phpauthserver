#DingStudio - WifiDog_Server
A simple wifidog auth program writed with PHP
# how to install database ? Please use this SQL Code to install on the MySQL

CREATE DATABASE wifidog;
use wifidog;
CREATE TABLE IF NOT EXISTS `users` (
  `userid` char(16)  primary key,
  `userpass` varchar(32) NOT NULL,
  `usertoken` varchar(32) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
insert into wifi.users(userid,userpass,usertoken,status) values("root","yourpassword","0","true");

# And you should change some config information in file "conn.php"
