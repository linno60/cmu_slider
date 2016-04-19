<?php
session_start();
include("functions.php");
include("db/connect.php");

$id = (int)$_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];

$message = "Failed";
$feedback = "Failed to update slide!";

if($_SESSION['permissions'] == 'admin'){

	$sql = "UPDATE slide SET title=:title, content=:content WHERE id=:id";
	$psql = $conn->prepare($sql);
	$query = $psql->execute(array(":id"=>$id, ":title"=>$title, ":content"=>$content ));
	
	$message = "Success";
	$feedback = "Successfully updated slide " . $id;
}

echo json_encode(array("message"=>$message, "feedback"=>$feedback));
?>

