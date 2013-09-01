<?php
	$out['self'] = 'video';	
	require 'header.php';
	include "connectSql.php";
	
	require_once('lib/PhpConsole.php');
	
	PhpConsole::start();
	$library = '<script type="text/javascript" src="/kinect/js/video.js"></script>';

	if(isGET('dtls')&&isLogin()){
		$alphaid = $_GET['dtls'];
		
		//Get video details
		$result = mysql_fetch_array(searchVideoByID($alphaid));
		foreach ($result as $key => $value) {
			$$key = $value;
		}

		$userResult = mysql_fetch_array(searchUserBymail($owner));


		//operate the video data.

		$unixTime = $deadline;
		$deadDate = new DateTime("@$unixTime");

		$unixTime = strtotime($requestTime.' GMT');
		$reqDate = new DateTime("@$unixTime");
		$reqDate = explode("+", $reqDate->format('c'));
		// debug($unixTime);
		$reqTimeTexts = 'Requested by '.$userResult['nickName'].' @ <abbr class="timeago" title="'.$reqDate[0].'"></abbr>';

		$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$ytoutubeID);
		parse_str($content, $ytarr);
		// debug($ytarr['title']);

		//output html content.

		$out['content'] = $library.'<div class="panel panel-default col-lg-8 col-lg-offset-2"><div class="row">
		<div class="panel-body col-lg-5"><div id="video_sec"><img class="img-thumbnail" src="http://img.youtube.com/vi/'.$result['ytoutubeID'].'/0.jpg" style="width:300px;"></div></div>
			<div class="panel-body col-lg-7">
			<div class="panel panel-info">
			<div class="panel-heading">
			<h3 class="panel-title">Request Informations</h3>
			</div>
			<div class="panel-body">
				<h5>Youtube\'s ID: </h5><a id="ytoutubeID" href="http://youtu.be/'.$ytoutubeID.'" target=_blank>'
				.$ytoutubeID.'</a><br/>
				<h5>Period:</h5>
				Start from '.$start.'s to '.$end.'s. ('.($end-$start).' seconds)
				<h5>Deadline: </h5>'.$deadDate->format('Y-m-d H:i:s').'<br/>
				<h5>Budget: </h5>'.$budget.'(NTD)<br/>
				<h5>Tag(s): </h5>'.generateTagLink($tag).'<br/><br/>'.$reqTimeTexts.'
			</div>
			</div>
				
			</div>
		</div>
		<a class="btn btn-primary btn-lg btn-block" href="/kinect/application.php/act/'.$alphaid.'">Contribute Your Motion Now!</a>
		<div class="row">
			<div class="panel-body">
				<span class="glyphicon glyphicon-align-justify"></span>There are $var motions...<br/> listMotion($id);
			</div>
		</div>
		</div>';
	}else{
		

	}
	require 'footer.php';


?>