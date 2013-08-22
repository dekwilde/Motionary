<?php
	$out['self'] = 'video';	
	require 'header.php';
	include "connectSql.php";
	
	require_once('lib/PhpConsole.php');
	
	PhpConsole::start();

	if(isGET('dtls')&&isLogin()){
		$alphaid = $_GET['dtls'];
		$result = mysql_fetch_array(searchVideoByID($alphaid));
		debug($alphaid);
		$unixTime = $result['deadline'];
		$date = new DateTime("@$unixTime");
		$out['content'] = '<div class="panel panel-default col-lg-8 col-lg-offset-2"><div class="row">
		<div class="panel-body col-lg-5"><img class="img-thumbnail" src="http://img.youtube.com/vi/'.$result['ytoutubeID'].'/0.jpg" style="width:300px;"></div>
			<div class="panel-body">ID is '
				.$result['ytoutubeID'].'<br/>Owner is '
				.$result['owner'].'<br/>Request Time is '
				.$result['requestTime'].'<br/>Deadline is '
				.$date->format('Y-m-d H:i:s')
				.'<br/><button type="button" class="btn btn-primary disabled"><span class="glyphicon glyphicon-usd"></span>Contribute Your Motion Now!</button>
			</div>
		</div>
		<div class="row">
			<div class="panel-body">
				<span class="glyphicon glyphicon-align-justify"></span> List of Motion..
			</div>
		</div>
		</div>';
	}else{


	}
	require 'footer.php';


?>