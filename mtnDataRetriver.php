<?php
  include "connectSql.php";
  
  // echo $_POST['mtnData'];
  $ip	= (string)$_SERVER['REMOTE_ADDR'];
  $tag = sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
  $mtnData = $_POST['mtnData'];
  
  //create table
  mysql_query("CREATE TABLE `mtnData` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`motion` longtext collate utf8_unicode_ci,
	`ip` text collate utf8_unicode_ci NOT NULL,
	`tag` int(10) unsigned NOT NULL default '0',
	`time` timestamp NOT NULL default CURRENT_TIMESTAMP,
	PRIMARY KEY  (`id`,`tag`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
  
  //save mtnData
  mysql_query("INSERT INTO `mtnData` (`motion`,`ip`,`tag`) VALUES ('"
  .$mtnData."',
  '".$ip."',
  '".$tag."')");

  $filename = 'test.txt';
  $fp = fopen($filename, "a+");
  $write = fputs($fp, $mtnData);
  fclose($fp);

  //read from data
  // to open file
  $fp = fopen($filename, 'r'); // use 'rw' to open file in read/write mode
   // to output entire file
  echo fread($fp, filesize($filename));
   // to close file
  fclose($fp);
  
  $mtnTArr = explode(":", $mtnData);
  $mtnArr = array();

  foreach($mtnTArr as $key => $value){
  	array_push($mtnArr, explode(", ", $value));
  }

  // //return the array
  // foreach ($mtnArr as $key => $value) {
  // 	// echo $key.": ".$value[0]." ".$value[1]." ".$value[2]." ".$value[3]." ";
  // }
?>
