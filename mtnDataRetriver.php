<?php
  include "connectSql.php";
  
  // echo $_POST['mtnData'];
  $ip	= (string)$_SERVER['REMOTE_ADDR'];
  $tag = sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
  $mtnData = $_POST['mtnData'];
  
  $directory = './skeletonData/';
  $filename = $_POST['vid'].'.txt';

  $fp = fopen($directory.$filename, "a+");
  $write = fputs($fp, $mtnData);
  fclose($fp);

  //read from data
  // to open file
  // $fp = fopen($filename, 'r'); // use 'rw' to open file in read/write mode
  //  // to output entire file
  // echo fread($fp, filesize($filename));
  //  // to close file
  // fclose($fp);
  
  // $mtnTArr = explode(":", $mtnData);
  // $mtnArr = array();

  // foreach($mtnTArr as $key => $value){
  // 	array_push($mtnArr, explode(", ", $value));
  // }

  // //return the array
  // foreach ($mtnArr as $key => $value) {
  // 	// echo $key.": ".$value[0]." ".$value[1]." ".$value[2]." ".$value[3]." ";
  // }
?>
