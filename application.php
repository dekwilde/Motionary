<?php

$out['self'] = 'application';
require 'header.php';
include "connectSql.php";

$libary = '
	<script src="/kinect/js/loadAvatar.js"></script>
	<script type="text/javascript" src="/kinect/js/Three.js"></script>
    <script src="/kinect/js/zig.min.js"></script>
    <script type="text/javascript" src="/kinect/js/mtnDataProcessor.js"></script>';

if(isGET('list')){
	$out['content'] = '這一頁將會列出已經有哪些的request video，可以點進去個別影片頁看已經有哪些motion或自己也可以contribute<a href="http://lockys.hopto.org/kinect/application.php/act"><br/>Kinect骨架資料收集demo頁</a>';
}
else if(isGET('act')){
	$out['content'] = $libary.'
	<div class="content">
		<div id="area_motion">
			<div class="btn-group">
			  <button type="button" id="record-btn" class="btn btn-default">Record</button>
			  <button type="button" id="replay-btn" class="btn btn-default">Replay</button>
			  <button type="button" id="store-btn" class="btn btn-default">Store</button>
			</div>
			<div id="instruction">
				Please stand in front of your kinect. :)
			</div>
		</div>
	</div>';
}
else{
	redirect('application.php/list');
}
require 'footer.php';

?>
