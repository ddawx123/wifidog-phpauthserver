<?php
	$gw_address="192.168.1.1";
	$gw_port="2060";
	$mysql_server_name="192.168.1.1:3306"; 
    $mysql_username="dbuser";
    $mysql_password="dbpasswd";
    $conn=mysqli_connect($mysql_server_name, $mysql_username,$mysql_password);
    mysqli_select_db($conn,"dbname");
?>