<?php

	session_start();
	error_reporting(0);

	if($_SESSION['master']=='masterOpenUID'){
		$UIDresult = $_POST["UID"];
		$Write="<?php 
		session_start();
		if($"."_SESSION['master']=='masterOpenUID'){
			$"."UIDresult= '$UIDresult'; 
			echo $"."UIDresult; 
		}
		?>";
		
		$rootPath = $_SERVER['DOCUMENT_ROOT'];
		$thisPath = dirname($_SERVER['PHP_SELF']);
		$onlyPath = str_replace($rootPath, '', $thisPath);
		file_put_contents('/opt/lampp/htdocs/'.$onlyPath.'/masterContainer.php',$Write);
		
	}else{
		header("HTTP/1.1 403 Not Found");
	}

?>