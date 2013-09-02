<?php
  $out['self'] = 'mtnDataRetriver';
  include "connectSql.php";
  require "header.php";

  if(!isLogin()){
    return;
  }

  //get contributor's ip address and tranform into tag in float format.
  $ip	= (string)$_SERVER['REMOTE_ADDR'];
  $tag = sprintf('%u',ip2long($_SERVER['REMOTE_ADDR']));
  

  $mtnData = $_POST['mtnData'];//Skeleton data retrieved from kinect.
  $mail = $_SESSION['mail'];//contributor's e-mail address.

  //specify the .txt file name and the directory name where we want to save.
  $directory = './skeletonData/';
  $accountName = explode("@", $mail);
  $filename = $_POST['vid'].'_'.$accountName[0].'.txt';

  //save the information about this contribution. 
  if(!mysql_query("INSERT INTO motiondata (vid, fileName, owner , onickName, score) VALUES 
  ('".$_POST['vid']."', '".$filename."','".$_SESSION['mail']."','".$_SESSION['nickName']."',0)")){
    //failed to save to database.
    echo '{"status:" 0}';
  }

  //write to the file.
  $fp = fopen($directory.$filename, "a+");
  $write = fputs($fp, $mtnData);
  fclose($fp);

  //return the successfull msg to the client.
  echo '{"status": 1, "alphaid":"'.$_POST['vid'].'"}';






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
