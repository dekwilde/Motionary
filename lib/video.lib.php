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

		$alpha = alphaID(mysql_insert_id(),false,7, 'KOvideo99623773in');

  		mysql_query("UPDATE videoData SET identity = '".$alpha."' WHERE vid =".mysql_insert_id());

  		return $alpha;
	}

	function searchVideoByID($alphaID){
		return mysql_query("SELECT * FROM videoData WHERE identity='".$alphaID."'");
	}


	function listAllVideo(){
		$result = mysql_query("SELECT * FROM videoData");
		$htmlFrag = '';
		

		while ($row = mysql_fetch_array($result)) {
			$htmlFrag .= '<div class="col-sm-6 col-md-3" style="margin-bottom:5px;">
			<a href="/kinect/video.php/dtls/'.$row['identity'].'"><div class="thumbnail">
			<img src="http://img.youtube.com/vi/'.$row['ytoutubeID'].'/0.jpg" alt="...">
			<div class="caption">'
				.$row['ytoutubeID'].
			'</div>
			</div></a>
			</div>';

			// $htmlFrag .= '<a href="/kinect/video.php/dtls/'.$row['identity'].'"><img class="img-thumbnail" src="http://img.youtube.com/vi/'.$row['ytoutubeID'].'/0.jpg" style="width:200px; margin:5px;"><br/>'.$row['ytoutubeID'].'</a>';  
		}
		return $htmlFrag;
	}

	function listUserVideo(){
		$result = mysql_query("SELECT * FROM videoData WHERE owner ='".$_SESSION['mail']."'");
		$htmlFrag = '';
		while ($row = mysql_fetch_array($result)) {
				$htmlFrag .= '
				<div class="col-sm-4" style="margin-bottom:5px;">
				<a href="/kinect/video.php/dtls/'.$row['identity'].'"><div class="thumbnail">
				<img src="http://img.youtube.com/vi/'.$row['ytoutubeID'].'/0.jpg" alt="...">
				<div class="caption">'
					.$row['ytoutubeID'].
				'</div>
				</div></a>
				</div>
				';
		}
		return $htmlFrag;
	}

	function generateTagLink($tags){
		$tagArr = explode(",", $tags);
		$labelArr = array("default", "primary", "success", "info", "warning", "danger");

		$htmlFrag = '';

		foreach ($tagArr as $key => $value) {
			$htmlFrag .= '<a class="btn-tag-link" href="#"><span class="label label-'.$labelArr[$key%6].'">'.$value.'</span></a>';
		}

		return $htmlFrag;
	}

?>