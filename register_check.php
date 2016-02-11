<?php
include("db/connect.php");
include("functions.php"); 
$data = array();
parse_str($_POST['data'],$data);

$username = $data['username'];
$password = $data['password'];
$f_name = $data['f_name'];
$l_name = $data['l_name'];
$email = $data['email'];
$group = $data['group'];




$sql = "SELECT COUNT(username) FROM users WHERE username=:username";
$psql = $conn->prepare($sql);
$psql->execute(array(":username"=>$username));
$row = $psql->fetch();

// validate that it should be inserted

if($row[0] != '0') { // username exists
	$status = "failed";
	$errorMessage = "Username " . $username . " already exists!"
}

else{
	$salt = createSalt();

	$password = hashPassword($data['password'], $salt);

	$sql = "INSERT INTO users (username, salt, password, f_name, l_name, email, organization, permissions) 
			VALUES (:username,:salt,:password,:f_name,:l_name,:email,:group,:permissions)";
	$psql = $conn->prepare($sql);
	$psql->execute(array(	":username"=>$data['username'],
							":salt"=>$salt,
							":password"=>$password,
							":f_name"=>$data['f_name'],
							":l_name"=>$data['l_name'],
							":email"=>$data['email'],
							":group"=>$data['group'],
							":permissions"=>$data['permissions']
							));
	$status = "inserted";
}

echo json_encode(array("username"=>$username, "status"=>$status, "errorMessage"=>$errorMessage));
?>