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
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="/kinect/css/nprogress.css" />
	<link rel="stylesheet" type="text/css" href="/kinect/css/index.css" />
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
		    		var data = $(this).val();
		    		// console.log(data);
		    		NProgress.start();		
		    		$.post('/kinect/search.php/info',{tag: data},function(msg){
		    			NProgress.done();
		    			// console.log(msg);
		    			if(msg.status==1){
		    				console.log("status "+msg.status);
		    				var htmlfarg = 'Result:<br/>';
		    				var identities = msg.identity;
		    				var ytoutubeIDs = msg.ytoutubeID;
		    				var requestTimes = msg.requestTime;

		    				for(var i = 0; i<identities.length ;i++){
		    					htmlfarg += '<div class="col-sm-6 col-md-3 video-block" style="margin-bottom:5px;"><a class="list-video-btn" href="/kinect/video.php/dtls/'+identities[i]+'"><div class="thumbnail"><img class="video-thumbnail" src="http://img.youtube.com/vi/'+ytoutubeIDs[i]+'/0.jpg" alt="..."><div class="caption">'+ytoutubeIDs[i]+'</div><div class="caption" style="text-align:right;">Requested <abbr class="timeago" title="'+requestTimes[i]+'"></abbr></div></div></div></a></div>';
		    				}
		    				$('.area_body').html(htmlfarg);
		    			}else if(msg.status==2){
		    				$('.area_body').html('<div class="panel panel-default col-lg-6 col-lg-offset-3" style="text-align:center;"><div class="panel-body"><h1>Sorry!</h1> We could not find the related motions whose tag is <span class="label label-danger">'+msg.tag+'</span></div></div>');
		    			}else{
		    				$('.area_body').html('<div class="panel panel-default col-lg-6 col-lg-offset-3" style="text-align:center;"><div class="panel-body"><h1>Error!</h1>Please Login in first to enjoy our service.</div></div>');
		    			}
		    			jQuery("abbr.timeago").timeago();
		    		},'json');
		    	}
			});

			$(function() {
				var availableTags;
				// console.log(availableTags);
				//從資料庫取得所有的tag資料
				$.post("/kinect/search.php/gettag",{},function(reMsg){
					availableTags = reMsg.tagArr;
					// console.log(availableTags);
					$( "#input-search-tag" ).autocomplete({
						source: availableTags
					  });
				},"json");

				// $( "#input-search-tag" ).autocomplete({
				// 	source: availableTags
				// 	function(request, response) {
				// 		$.ajax({url: "/kinect/search.php/auto",
				// 			//注意：這裡是設定post的變數及值，Server端就是以這個變數名稱取值
				// 			data: {term: 'tennis'},  
				// 			dataType: "json",
				// 			type: "POST",
				// 			success: function(data){
				// 				response([
				// 						"ActionScript",
				// 						"AppleScript",
				// 						"Asp",
				// 						"BASIC",
				// 						"C",
				// 						"C++",
				// 						"Clojure",
				// 						"COBOL",
				// 						"ColdFusion",
				// 						"Erlang",
				// 						"Fortran",
				// 						"Groovy",
				// 						"Haskell",
				// 						"Java",
				// 						"JavaScript",
				// 						"Lisp",
				// 						"Perl",
				// 						"PHP",
				// 						"Python",
				// 						"Ruby",
				// 						"Scala",
				// 						"Scheme"
				// 					]
				// 				);
				// 			},
				// 			error: function(data){
				// 				response([
				// 						"ActionScript",
				// 						"AppleScript",
				// 						"Asp",
				// 						"BASIC",
				// 						"C",
				// 						"C++",
				// 						"Clojure",
				// 						"COBOL",
				// 						"ColdFusion",
				// 						"Erlang",
				// 						"Fortran",
				// 						"Groovy",
				// 						"Haskell",
				// 						"Java",
				// 						"JavaScript",
				// 						"Lisp",
				// 						"Perl",
				// 						"PHP",
				// 						"Python",
				// 						"Ruby",
				// 						"Scala",
				// 						"Scheme"
				// 					]
				// 				);
				// 			},
				// 		});
				// 	}
				// });
			});
			// $("#input-search-tag").bind("keydown", function( event ) {
			//     if ( event.keyCode === $.ui.keyCode.TAB &&
			// 		$(this).data("uiAutocomplete").menu.active ) {
			// 		event.preventDefault();     
			//     }
			// })
			// .autocomplete({
			// 	source: function(request, response) {
			// 		$.ajax({url: "/kinect/search.php/auto",
			// 			//注意：這裡是設定post的變數及值，Server端就是以這個變數名稱取值
			// 			data: {term: extractLast( request.term )},  
			// 			dataType: "json",
			// 			type: "POST",
			// 			success: function(data){
			// 				response(data);
			// 			}
			// 		});
			// 	},
			// 	search: function() {
			// 		var term = extractLast(this.value);
			// 		if (term.length < 2 ) { //當字數小於兩個字時，不抓資料
			// 			return false;
			// 		}
			// 	},
			// 	focus: function() {return false;},
			// 	select: function(event, ui) {
			// 		var terms = split( this.value );
			// 		terms.pop();
			// 		terms.push( ui.item.value );
			// 		terms.push( "" );
			// 		this.value = terms.join( ", " );
			// 		return false;
			// 	}
			// });
			// function split( val ) {
			//     return val.split( /,\s*/ );
			// }
			// function extractLast(term) {
			//     return split( term ).pop();
			// }
	    });

    </script>
</head>
<body>
	<div class="navbar navbar-fixed-top navbar-inverse">
		  <div class="container">

			  <a class="navbar-brand" href="/kinect/index.php"><span class="glyphicon glyphicon-home"></span> Motionary</a>
			  <ul class="nav navbar-nav">
			    <li id="link-about"  class="disabled"><a href="/kinect/index.php">About</a></li>	  
			    <li id="link-contribute"><a href="/kinect/video.php/list">Contribute</a></li>
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