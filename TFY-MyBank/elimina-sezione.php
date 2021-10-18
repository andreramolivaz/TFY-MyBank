<?php
require_once('includes/connect.php');

$sql = "DELETE FROM portafoglio_azioni WHERE idportafoglio=?";
$result = $db->prepare($sql);
$res = $result->execute(array($_GET['id'])) or die(print_r($result->errorInfo(), true));
if($res){
	$sql = "DELETE FROM portafoglio WHERE id=?";
	$result = $db->prepare($sql);
	$res = $result->execute(array($_GET['id'])) or die(print_r($result->errorInfo(), true));
    header("location: sezioni.php");
}else{
    header("location: sezioni.php");
}
