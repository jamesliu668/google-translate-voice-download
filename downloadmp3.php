<?php
 /**
  * @copyright  Copyright (C) 2014 JMSLIU.com All rights reserved.
  * @license    GNU General Public License version 2 or later; see LICENSE
  * @author		James Liu
  * @website	http://jmsliu.com
  */
  
	if(empty($_POST["language"])) {
		echo "Please select your language!";
		exit;
	} else {
		$language = trim($_POST["language"]);
		if(empty($language)) {
			echo "Please select your language!";
			exit;
		}
	}
	
	if(empty($_POST["word"])) {
		echo "Please enter your word!";
		exit;
	} else {
		$word = trim($_POST["word"]);
		if(empty($word)) {
			echo "Please enter your word!";
			exit;
		}
	}
	
	//check folder if exists
	$audioFolder = "./audio";
	if(!file_exists($audioFolder)) {
		mkdir($audioFolder);
	} else if(!is_dir($audioFolder)) {
		echo "audio is not a folder.";
		exit;
	}
	
	//check file if exists
	$filePath = $audioFolder."/".$word.".mp3";
	if(!file_exists($filePath))
	{
		$file = fopen($filePath, 'w');
		$searchULR = "http://translate.google.com/translate_tts?tl=".$language."&q=%22".$word."%22";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $searchULR);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT,
	"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
		
		//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); // write cookies
		//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); // read cookies
		
		//downloading file
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FILE, $file);
		
		$result = curl_exec($ch);
		curl_close($ch);
		//echo 'Curl error: ' . curl_error($ch);
		//$reponseInfo = curl_getinfo($ch);
		//$reponseInfo['http_code'].":".$reponseInfo['total_time']."\n";
	}
	
	//download the mp3
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.basename($filePath));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($filePath));
	
	ob_clean();
	flush();
	//normal download approach
	readfile($filePath);
	exit;
?>