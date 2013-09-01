<?php
	$out['self'] = 'user';
	require 'header.php';
	include "connectSql.php";

	$libray = '<script type="text/javascript" src="/kinect/js/request.js"></script>
  <script src="/kinect/js/lib/tag-it.min.js" type="text/javascript" charset="utf-8"></script>
  <link href="/kinect/css/jquery.tagit.css" rel="stylesheet" type="text/css">
  <link href="/kinect/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
	';

	if(!isLogin()){
		$out['content'] = 'Please login in first.';
	}else{
		$out['content'] = $libray.'
		<div class="row">
		<div class="col-lg-2">
		<div class="list-group">
		<a href="/kinect/user.php/info" class="list-group-item">
		You Information
		</a>
		<a href="/kinect/user.php/vid" class="list-group-item">Your Requests</a>
		<a href="#" class="list-group-item">Your Contributions</a>
		</div>
		</div>
		';
	}


	if(isGET('info')&&isLogin()){
		$result = mysql_fetch_array(searchUserBymail($_SESSION['mail']));
		$out['content'] .= '
		 <div class="col-lg-6">
			<div class="well well-sm">
					<form class="form-horizontal" id="form-user-update" role="form">
					<div class="form-group">
					<label class="col-lg-2 control-label">Email</label>
					<div class="col-lg-10">
					<input type="text" class="form-control" id="disabledInput" placeholder="'.$result['email'].'" disabled>
					</div>
					</div>
					<div class="form-group">
					<label for="lastName" class="col-lg-2 control-label">LastName</label>
					<div class="col-lg-10">
					<input type="text" name="lastName"  class="form-control" placeholder="'.$result['lastName'].'" disabled>
					</div>
					</div>
					<div class="form-group">
					<label for="firstName" class="col-lg-2 control-label">FirstName</label>
					<div class="col-lg-10">
					<input type="text" name="firstName"  class="form-control" placeholder="'.$result['firstName'].'" disabled>
					</div>
					</div>
					<div class="form-group">
					<label for="nickName" class="col-lg-2 control-label">NickName</label>
					<div class="col-lg-10">
					<input type="text" name="nickName" class="form-control" value="'.$result['nickName'].'">
					</div>
					</div>
					<button id="btn-upd-user" type="submit" class="btn btn-primary">Update your Information</button>

					</form>
			</div>
		 </div>
		 <div class="col-lg-4">
		 	<div class="well well-sm">
		 		'.$result['nickName'].', you have <strong>'.$result['coin'].'</strong> coin(s) in your pocket now.
		 	</div> 
		 </div>
		</div>
		';
	}else if(isGET('vid')&&isLogin()){
		$out['content'] .= '
		 <div class="col-lg-7">
			'.listUserVideo().'
		</div>
		</div>
		';

	}else if(isLogin()){
		redirect('user.php/info');
	}
	require 'footer.php';


?>