<?php
	$out['self'] = 'video';	
	require 'header.php';
	include "connectSql.php";
	
	require_once('lib/PhpConsole.php');
	
	PhpConsole::start();

	if(isGET('show')&&isLogin()){
		$alphaid = $_GET['show'];
		$result = mysql_fetch_array(searchVideoByID($alphaid));
		debug($alphaid);
		$out['content'] = $result['ytoutubeID'];
	}else{


	}
	require 'footer.php';


?>