<?php
	
	function createVideoTable(){		
		mysql_query("CREATE TABLE videoData (
			vid int(10) unsigned NOT NULL auto_increment,
			ytoutubeID text collate utf8_unicode_ci NOT NULL,						
			requestTime timestamp NOT NULL default CURRENT_TIMESTAMP,
			start int(10) unsigned NOT NULL default '0',
			end int(10) unsigned NOT NULL default '0',	
			owner text collate utf8_unicode_ci,		
			tag text collate utf8_unicode_ci NOT NULL,
			deadline int(15) unsigned NOT NULL default '0',							
			ip text collate utf8_unicode_ci NOT NULL,						
			ipTag int(10) unsigned NOT NULL default '0',
			PRIMARY KEY  (vid,ipTag)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
	}

	function insertVideo($arr){
		mysql_query("INSERT INTO videoData (ytoutubeID, identity, start,end,owner,tag,deadline,ip,ipTag) VALUES ('"
  		.$arr['ytoutubeID']."',
   		'".$arr['ytoutubeID']."', 		
  		'".$arr['start']."',
  		'".$arr['end']."',
  		'".$arr['owner']."',
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
		$out = '';

		while ($row = mysql_fetch_array($result)) {
			$out .= '<a href="/kinect/video.php/dtls/'.$row['identity'].'"><img class="img-thumbnail" src="http://img.youtube.com/vi/'.$row['ytoutubeID'].'/0.jpg" style="width:200px; margin:5px;"></a>';  
		}
		return $out;
	}

?>