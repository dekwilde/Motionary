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
		if(time()<$unixTime){
			$deadDate = $deadDate->format('Y-m-d H:i:s');
		}else{
			$deadDate = '<b style="color:red;">Time is up!</b>';
		}
		//create the timeago 
		$reqTimeTexts = 'Requested by '.$userResult['nickName'].' @ <abbr class="timeago" title="'.intoISOTimestamp($requestTime).'"></abbr>';

		// $content = file_get_contents("http://youtube.com/get_video_info?video_id=".$ytoutubeID);
		// parse_str($content, $ytarr);
		// debug($ytarr['title']);

		//output html content.

		//listAllMotion is defined in the video.lib.php
		$listAllMotion = listAllMotion($alphaid);

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
				<h5>Deadline: </h5>'.$deadDate.'<br/>
				<h5>Budget: </h5>'.$budget.'(NTD)<br/>
				<h5>Tag(s): </h5>'.generateTagLink($tag).'<br/><br/>'.$reqTimeTexts.'
			</div>
			</div>
				
			</div>
		</div>';
		if(!$listAllMotion['hasContribute'] && time()<$unixTime){
			$out['content'] .= '<a class="btn btn-primary btn-lg btn-block" href="/kinect/application.php/act/'.$alphaid.'">Contribute Your Motion Now!</a>';
		}else{
			$out['content'] .= '<button class="btn btn-primary btn-lg btn-block disabled">You have already contributed or time is up.</button>';
		}

		//list the motion
		$out['content'] .= '<div class="row">
			<div class="panel-body">
				'.$listAllMotion['htmlFrag'].'
			</div>
		</div>
		</div>';
	}else{
		$out['content'] = '<div class="panel panel-default col-lg-6 col-lg-offset-3" style="text-align:center;"><div class="panel-body"><h1>Error!</h1>Please Login in first to enjoy our service.</div></div>';

	}
	require 'footer.php';


?>