<?php

if(!isset($out))
{
	exit;
}
header('Content-Type: text/html; charset=UTF-8');
include "connectSql.php";

?>
<!DOCTYPE html>
<html>
<head>
	<title>Motionary - Find the motions you get interested in.</title>
	<!-- Style Sheet -->
	<link rel="stylesheet" type="text/css" href="/kinect/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="/kinect/css/index.css" />

	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="/kinect/css/nprogress.css" />
	 <!-- JavaScript Library -->
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script type="text/javascript" src="/kinect/js/bootstrap.js"></script>
    <script type="text/javascript" src="/kinect/js/RequestAnimationFrame.js"></script>
    <script type="text/javascript" src="/kinect/js/lib/nprogress.js"></script>
    <script type="text/javascript" src="http://timeago.yarp.com/jquery.timeago.js"></script>
	<script src="http://swfobject.googlecode.com/svn/tags/rc3/swfobject/src/swfobject.js" type="text/javascript"></script>    
	<script type="text/javascript">
	    jQuery(document).ready(function() {
	    	jQuery("abbr.timeago").timeago();	
	    	
	    	$('#input-search-tag').keyup(function(e){	
	    		e.preventDefault();
		    	if(e.keyCode==13){
		    		var data = $(this).html();
		    		console.log(data);
		    		NProgress.start();		
		    		$.post('/kinect/search.php/info',{tag: data},function(msg){
		    			NProgress.done();
		    			if(msg.status==1){
		    				console.log("status "+msg.status);
		    				$('.area_body').html('Result:');
		    				// location.reload();
		    			}
		    		},'json');
		    	}
			});
	    });
	    
		// function searchTag(){
		// 	$("#form-search-tag-btn").click();
		// }
		// $('#form-search-tag-input').keypress(function(e){
		// 	if (e.which==13) {
		// 		$('#form-search-tag').submit();
		// 	}
		// });
    </script>
</head>
<body>
	<div class="navbar navbar-fixed-top navbar-inverse">
		  <div class="container">

			  <a class="navbar-brand" href="/kinect/index.php"><span class="glyphicon glyphicon-home"></span> Motionary</a>
			  <ul class="nav navbar-nav">
			    <li id="link-about"  class="disabled"><a href="/kinect/index.php">About</a></li>	  
			    <li id="link-contribute"><a href="/kinect/application.php">Contribute</a></li>
			    <li id="link-request"><a href="/kinect/request.php">Request</a></li>
			    <li id="link-contact"  class="disabled"><a href="/kinect/index.php">Contact us</a></li>	  
			  </ul>
			  


			  <input type="text" class="navbar-form pull-left form-control col-lg-8" id="input-search-tag" placeholder="Search Motion(s)">

			  <!-- Check if the user log in. -->
			  <?php
			  	if(isLogin()){
			  		$result = mysql_fetch_array(searchUserBymail($_SESSION['mail']));
			  		if(!isset($_SESSION['nickName']))
			  			$_SESSION['nickName'] = $result['nickName'];
			  		echo '<a href="/kinect/logout.php" class="btn btn-default navbar-btn pull-right" id="sign-btn">Log out</a><a href="/kinect/user.php" id="link-user-name" class="btn btn-primary navbar-btn pull-right"><span class="glyphicon glyphicon-user"></span> '.$_SESSION['nickName'].'<span class="badge">0</span></a>
			  		';
			  	}else{
			  		echo '<a href="/kinect/login.php" class="btn btn-default navbar-btn pull-right">Sign in with Google</a>';
			  	}
			  ?>	
		  
		  </div>
	</div>


	<!-- Output the html content by php -->
	<div class="container  area_body">
			<?php echo $out['content'];?>
	</div>
	<?php
		if(isGET('act')){
			echo '<div id="pluginContainer">
					<object id="zigPlugin" type="application/x-zig" width="0" height="0">
            			<param name="onload" value="zigPluginLoaded">
        			</object>
    			  </div>';
    	}
    ?>
    <footer class="bs-footer">
    	2013 Motionary_version_git is powered by <a href="http://zigfu.com/" target="_blank">Zigfu</a>.
    </footer>

</body>
</html>