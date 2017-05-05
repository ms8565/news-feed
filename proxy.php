<?php
	if(array_key_exists('url',$_REQUEST)){
		//$ = variable
		$fileUrl = $_REQUEST['url'];
	}
	else{
		echo "Need file url";
		exit();
	}
	
	if(array_key_exists('format',$_REQUEST)){
		$format = $_REQUEST['format'];
	}
	else{
		$format = "text/xml";
	}
	
	$fileData = file_get_contents($fileUrl);
	
	header("content-type: $format");
	echo $fileData;
?>