<?php
	$out['self'] = 'user';
	require 'header.php';
	include "connectSql.php";

	$libray = '';

	
	if(isGET('info')&&isLogin()){
		echo '{"status":1}';
	}else if(isGET('vid')&&isLogin()){

	}
?>