<?php
session_start();

if($_SERVER['PHP_AUTH_USER']!="admin"||$_SERVER['PHP_AUTH_PW']!="123456"){
	header("WWW-Authenticate: Basic realm = my GB");
	header("HTTP/1.0 401 Unauthorized");
	die("未經驗證");
	}
else{
	$_SESSION['Authdone'] = true;
	header("Location: index.php");
	}
?>