<?php
	$out['self'] = 'user';
	require 'header.php';
	include "connectSql.php";
	// require_once('lib/PhpConsole.php');
	// PhpConsole::start();
	
	$libray = '';

	
	if(isGET('info')&&isLogin()){
		$tagData = mysql_fetch_array(mysql_query("SELECT * FROM tagData WHERE tagName='".$_POST['tag']."'"));
		
		if($tagData){
			$tid = $tagData['tid'];
			$vidArray = mysql_query("SELECT vid FROM tagMap WHERE tid='".$tid."'");
			$vids = array();
			while($row = mysql_fetch_array($vidArray)){
				array_push($vids, alphaID((int)$row['vid'],false,7, 'KOvideo99623773in'));
			}
			echo '{"status":1, "result":'.json_encode($vids).'}';

		}else{
			echo '{"status":0, "tag":"'.$_POST['tag'].'"}';
		}
	}else if(isGET('vid')&&isLogin()){

	}else{
	}
?>