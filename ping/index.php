<?php
echo "Pong";
$gwstatus = "GWID:"._get('gw_id')."\r\n"."SysUptime:"._get('sys_uptime')."\r\n"."SysMemFree:"._get('sys_memfree')."\r\n"."SysLoad:"._get('sys_load')."\r\n"."WifiDogUptime:"._get('wifidog_uptime');
if (file_exists('localcache.plain')) {
    $LatestStr = file_get_contents('wifidog.log');
    $res = $LatestStr."\r\n\r\n".$gwstatus;
    $myfile = fopen("wifidog.log", "w") or die('Pong');
    fwrite($myfile, $res);
    fclose($myfile);
}
else {
    $myfile = fopen("wifidog.log", "w") or die('Pong');
    fwrite($myfile, $gwstatus);
    fclose($myfile);
}
function _get($gstr){
    $val = !empty($_GET[$gstr]) ? $_GET[$gstr] : null;
    return $val;
}
?>