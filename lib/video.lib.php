<?php
	
	function createVideoTable(){		
		mysql_query("CREATE TABLE videoData (
			vid int(10) unsigned NOT NULL auto_increment,
			ytoutubeID text collate utf8_unicode_ci NOT NULL,						
			requestTime timestamp NOT NULL default CURRENT_TIMESTAMP,
			start int(10) unsigned NOT NULL default '0',
			end int(10) unsigned NOT NULL default '0',	
			owner text collate utf8_unicode_ci,	
			budget int(10) unsigned NOT NULL default '0',
			tag text collate utf8_unicode_ci NOT NULL,
			deadline int(15) unsigned NOT NULL default '0',							
			ip text collate utf8_unicode_ci NOT NULL,						
			ipTag int(10) unsigned NOT NULL default '0',
			PRIMARY KEY  (vid,ipTag)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
	}

	function insertVideo($arr){
		mysql_query("INSERT INTO videoData (ytoutubeID, identity, start,end,owner,budget,tag,deadline,ip,ipTag) VALUES ('"
  		.$arr['ytoutubeID']."',
   		'".$arr['ytoutubeID']."', 		
  		'".$arr['start']."',
  		'".$arr['end']."',
  		'".$arr['owner']."',
  		'".$arr['budget']."',
  		'".$arr['tag']."',
   		'".$arr['deadline']."',
   		'".$arr['ip']."',
  		'".sprintf('%u',ip2long($arr['ip']))."'
  		)");

		$vid = mysql_insert_id();
		$alpha = alphaID($vid,false,7, 'KOvideo99623773in');

		// Split the tags & insert to tagData table
		$tag_split = explode(",", $arr['tag']);
		mysql_query("UPDATE videoData SET identity = '".$alpha."' WHERE vid =".$vid);

		foreach($tag_split as &$tagName){
			$tagData = searchTagByName($tagName);
			if(mysql_num_rows($tagData) < 1){
				mysql_query("INSERT INTO tagData (tagName) VALUES ('".$tagName."')");
				$tid = mysql_insert_id();
				mysql_query("INSERT INTO tagMap (vid, tid) VALUES ('".$vid."', '".$tid."')");
			}else{
				$array = mysql_fetch_array(mysql_query("SELECT tid FROM tagData WHERE tagName='".$tagName."'"));
				$tid = $array['tid'];
				mysql_query("INSERT INTO tagMap (vid, tid) VALUES ('".$vid."', '".$tid."')");
			}
		}


  		return $alpha;
	}

	function searchVideoByID($alphaID){
		return mysql_query("SELECT * FROM videoData WHERE identity='".$alphaID."'");
	}

	// Find tid from tagData table
	function searchTagByName($tagName){
		return mysql_query("SELECT * FROM tagData WHERE tagName='".$tagName."'");
	}

	function listAllmotion($vid){
		$result = mysql_query("SELECT * FROM motiondata WHERE vid ='".$vid."' ORDER BY mid DESC");
		$htmlFrag = '<ul class="list-group">
					  <li class="list-group-item" style="background: #eee;"><span class="glyphicon glyphicon-align-justify"></span> There are '.mysql_num_rows($result).' motion(s)...<br/></li>';
		$hasContribute = false;

		if($result){
			while ($row = mysql_fetch_array($result)) {
				if($row['owner']==$_SESSION['mail']){
					$hasContribute = true;
				}

				$htmlFrag .= '
					  <li class="list-group-item" style="background: #d9edf7;">'.$row['onickName'].' contributed a motion @ <abbr class="timeago" title="'.intoISOTimestamp($row['contributeTime']).'"></abbr><li>
					  <li class="list-group-item">
					    Motion\'s Rating:
					    <div id="star'.$row['mid'].'"></div>
					    <script>
							$("#star'.$row['mid'].'").raty({ path: "http://lockys.hopto.org/kinect/js/rat-lib/img/", readOnly: true, score: '.$row['score'].' });
					    </script>
					    <br/><a data-toggle="modal" href="#myModal" class="btn btn-primary btn-lg" id="'.$row['mid'].'" onclick="getReplayPage('.$row['mid'].');"><span class="glyphicon glyphicon-play"></span> Replay and Rate this Motion!</a>

					  </li>';
			}	
		}
		$htmlFrag .= '</ul>';
		$rMsg = array('htmlFrag' => $htmlFrag, 'hasContribute' => $hasContribute );
		return $rMsg;
	}

	function listUserMotion($owner){
		$result = mysql_query("SELECT * FROM motiondata WHERE owner ='".$owner."' ORDER BY mid DESC");
		$htmlFrag = '<ul class="list-group">
					  <li class="list-group-item" style="background: #eee;"><span class="glyphicon glyphicon-align-justify"></span> You have contributed '.mysql_num_rows($result).' motion(s)...<br/></li>';
		$hasContribute = false;

		if($result){
			while ($row = mysql_fetch_array($result)) {
				if($row['owner']==$_SESSION['mail']){
					$hasContribute = true;
				}

				$htmlFrag .= '
					  <li class="list-group-item" style="background: #d9edf7;">'.$row['onickName'].' contributed a motion @ <abbr class="timeago" title="'.intoISOTimestamp($row['contributeTime']).'"></abbr><li>
					  <li class="list-group-item">
					    Motion\'s Rating:
					    <div id="star'.$row['mid'].'"></div>
					    <script>
							$("#star'.$row['mid'].'").raty({ path: "http://lockys.hopto.org/kinect/js/rat-lib/img/", readOnly: true, score: '.$row['score'].' });
					    </script>
					    <a data-toggle="modal" href="/kinect/video.php/dtls/'.$row['vid'].'" class="btn btn-primary btn-lg" id="'.$row['mid'].'" ">Go to Request!</a>
					  </li>';
			}	
		}
		$htmlFrag .= '</ul>';
		$rMsg = array('htmlFrag' => $htmlFrag, 'hasContribute' => $hasContribute );
		return $rMsg;
	}


	function listAllVideo(){
		$result = mysql_query("SELECT * FROM videoData ORDER BY vid DESC");
		$htmlFrag = '';
		

		while ($row = mysql_fetch_array($result)) {

			$unixTime = $row['deadline'];
			$deadDate = new DateTime("@$unixTime");

			if(time()<$unixTime){
				$remainDays = floor(($unixTime - time())/(24*60*60)).' day(s) remained';
				$deadDate = $deadDate->format('Y-m-d H:i:s');

			}else{
				$remainDays = 'Time is up!';
				$deadDate = 'pasted';
			}
			
			$htmlFrag .= '
			<div class="col-sm-6 col-md-3 video-block" style="margin-bottom:5px;">
				<a class="list-video-btn" href="/kinect/video.php/dtls/'.$row['identity'].'">
					<div class="thumbnail">
						<img class="video-thumbnail" src="http://img.youtube.com/vi/'.$row['ytoutubeID'].'/0.jpg" alt="...">
							<div class="caption">
							'.$row['ytoutubeID'].' <span class="label label-danger" title="Deadline is '.$deadDate.'">'.$remainDays.'</span>
							</div>
						<div class="caption" style="text-align:right;">
							Requested <abbr class="timeago" title="'.intoISOTimestamp($row['requestTime']).'"></abbr></div>
						</div>
				</a>
			</div>';

		}
		return $htmlFrag;
	}

	function listUserVideo(){
		$result = mysql_query("SELECT * FROM videoData WHERE owner ='".$_SESSION['mail']."' ORDER BY deadline DESC ");
		$htmlFrag = '';

		while ($row = mysql_fetch_array($result)) {
			$unixTime = $row['deadline'];
			$deadDate = new DateTime("@$unixTime");
			if(time()<$unixTime){
				$remainDays = floor(($unixTime - time())/(24*60*60)).' day(s) remained';
				$deadDate = $deadDate->format('Y-m-d H:i:s');
			}else{
				$remainDays = 'Time is up!';
				$deadDate = 'pasted';
			}
			$htmlFrag .= '
			<div class="col-sm-4 video-block" style="margin-bottom:5px;">
				<a class="list-video-btn" href="/kinect/video.php/dtls/'.$row['identity'].'">
					<div class="thumbnail">
						<img class="video-thumbnail" src="http://img.youtube.com/vi/'.$row['ytoutubeID'].'/0.jpg" alt="...">
							<div class="caption">
							'.$row['ytoutubeID'].' <span class="label label-danger" title="Deadline is '.$deadDate.'">'.$remainDays.'</span>
							</div>
						<div class="caption" style="text-align:right;">
							Requested <abbr class="timeago" title="'.intoISOTimestamp($row['requestTime']).'"></abbr></div>
						</div>
				</a>
			</div>';
		}
		return $htmlFrag;
	}

	function generateTagLink($tags){
		$tagArr = explode(",", $tags);
		$labelArr = array("default", "primary", "success", "info", "warning", "danger");

		$htmlFrag = '';

		foreach ($tagArr as $key => $value) {
			$htmlFrag .= ' <span class="label label-'.$labelArr[$key%6].'">'.$value.'</span>';
		}

		return $htmlFrag;
	}

?>