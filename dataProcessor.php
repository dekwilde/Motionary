<?php
	$out['self'] = 'dataCenter';	
	require 'header.php';
	include "connectSql.php";
	require_once('lib/PhpConsole.php');
	PhpConsole::start();
	
	$errors = array();
	$arrs = array();

	if(isGET('upduser')&&isLogin()){
		$arr = array(
			'nickName' => filter_input(INPUT_POST,'nickName',FILTER_CALLBACK,array('options'=>'validate_text')),
			'budget' => filter_input(INPUT_POST,'budget',FILTER_CALLBACK,array('options'=>'validate_text'))
		);
		updateUserBymail($_SESSION['mail'], $arr);
		echo '{"status":1}';
	}else if(isGET('updrqst')&&isLogin()){

		$arr = array(
			'addTime' => filter_input(INPUT_POST,'unixtime',FILTER_CALLBACK,array('options'=>'validate_text'))+
				filter_input(INPUT_POST,'MoreTimeNeeded',FILTER_CALLBACK,array('options'=>'validate_text'))*24*60*60,
			'budget' => filter_input(INPUT_POST,'budget',FILTER_CALLBACK,array('options'=>'validate_text')),
			'identity' => filter_input(INPUT_POST,'identity',FILTER_CALLBACK,array('options'=>'validate_text'))
		);
		updateRequest($arr);
		echo '{"status":1,"identity":"'.$arr['identity'].'"}';
	}else if(isGET('reqvid')&&isLogin()){

		if(! ($arrs['ytoutubeID'] = filter_input(INPUT_POST,'vid',FILTER_CALLBACK,array('options'=>'validate_text')))){
			$errors['vid'] = 'Please specify the video ID';
		}

		if(! ($arrs['tag'] = filter_input(INPUT_POST,'tag',FILTER_CALLBACK,array('options'=>'validate_text')))){
			$errors['tag'] = 'Please tag at least one motion';
		}

		if(! ($arrs['budget'] = filter_input(INPUT_POST,'budget',FILTER_CALLBACK,array('options'=>'validate_text')))){
			$errors['budget'] = 'Please specify the budget';
		}

		if(! ($arrs['start'] = filter_input(INPUT_POST,'start_value',FILTER_CALLBACK,array('options'=>'validate_text')))){
			$errors['period'] = 'Please specify the period';
		}

		if(! ($arrs['end'] = filter_input(INPUT_POST,'end_value',FILTER_CALLBACK,array('options'=>'validate_text')))){
			$errors['period'] = 'Please specify the period';
		}


		if(!empty($errors)){
			echo '{"status":0, "errors":'.json_encode($errors).'}';
		}else{
			//This block will process the datas we get and store into the database.
			$arrs['owner'] = $_SESSION['mail'];
			$arrs['ip'] = (string)$_SERVER['REMOTE_ADDR'];
			$arrs['deadline'] = (filter_input(INPUT_POST,'TimeNeeded',FILTER_CALLBACK,array('options'=>'validate_text'))*24*60*60)+time();

			
			$alphaid = insertVideo($arrs);
			
			echo '{"status":1, "alphaid":"'.$alphaid.'"}'; // return successful msg to the client.
		}

	}else if(isGET('rate')&&isLogin()){

		echo '{"status":1}';
	}else{
		echo '{"status":2}';		
	}
?>