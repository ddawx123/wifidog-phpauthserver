<?php
header('Content-type: application/json; charset=utf-8');
if ($_GET['message'] == 'denied') {
	echo '{"code":"AccessDenied","message":"This request signature has expired,please contact administrator.","requestId":"'.time().'"}';
}
else {
	echo '{"code":"IllegalOperation","message":"This request is illegal and has been rejected by the system.","requestId":"'.time().'"}';
}
?>
