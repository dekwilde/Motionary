<?php

$out['self'] = 'application';
require 'header.php';
include "connectSql.php";

$libary = '';

if(isGET('act')&&isLogin()){
	$vid = $_GET['act'];
	$libary .= '
	<script src="/kinect/js/loadAvatar.js"></script>
	<script type="text/javascript" src="/kinect/js/Three.js"></script>
    <script src="/kinect/js/zig.min.js"></script>
    <script type="text/javascript" src="/kinect/js/mtnDataProcessor.js"></script>';

	$out['content'] = $libary.'
	
	<div class="row">
		<div class="col-lg-4 col-lg-offset-2">
			<img src="http://thecogentcoach.com/wordpress/wp-content/uploads/2012/11/video-play-2.gif" style="width:350px;">
		</div>
		<div id="area_motion" class="col-lg-4">
			<div class="btn-group">
			  <button type="button" id="record-btn" class="btn btn-default">Record</button>
			  <button type="button" id="replay-btn" class="btn btn-default">Replay</button>
			  <button type="button" id="store-btn" class="btn btn-default">Store</button>
			</div>
			
		</div>
	</div>
	
	<div class="row">
		<div id="instruction" class="col-lg-8 col-lg-offset-2 alert alert-info">
				Please stand in front of your kinect. :)
		</div>
	</div>
	
	<div id="vid" style="display:none">'.$vid.'</div>';

}
else{
	if(!isLogin()){
		$out['content'] = '<div class="panel panel-default col-lg-6 col-lg-offset-3" style="text-align:center;"><div class="panel-body"><h1>Error!</h1>Please Login in first to enjoy our service.</div></div>';
	}else if(isLogin()){
		redirect('application.php/act');
	}
}
require 'footer.php';

?>
