<?php
	error_reporting(0);
	$UIDresult=$_POST["UIDresult"];
	$UIDresult='andi bayu Putra';
	$Write="<?php $" . "UIDresult='" . $UIDresult . "'; " . "echo $" . "UIDresult;" . " ?>";
	file_put_contents('/opt/lampp/htdocs/E-KTM/public/UIDContainer.php',$Write);
?>