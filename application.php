<?php

$out['self'] = 'application';
require 'header.php';
include "connectSql.php";

$libary = '';

if(isGET('list')){
	$out['content'] = listAllVideo();
}
else if(isGET('act')){
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
	redirect('application.php/list');
}
require 'footer.php';

?>
