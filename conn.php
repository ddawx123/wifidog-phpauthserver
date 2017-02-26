<?php
	$gw_address="192.168.1.254";//Your WifiDog Server Or Router Address.
	$gw_port="2060";//Your WifiDog Service Port.
	$regsiter_allowed="true";//Whether Allow Registration.Use "false" value to block regsiter action and "true" value to allow regsiter action.
	$mysql_server_name="sqld.bcehost.com:3306"; //Your MySQL Server Address And Service Port.
    $mysql_username="wifidog";//Your MySQL Database UserName.
    $mysql_password="123456";//Your MySQL Database PassWord.
	$mysql_dbname="wifidog_db";//Your MySQL Database Name.
    $conn=mysqli_connect($mysql_server_name, $mysql_username,$mysql_password);//Establish connection,you don't need to modify the content.
    mysqli_select_db($conn,$mysql_dbname);//Select database,you don't need to modify the content.
?>