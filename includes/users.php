<?php
function userExists($username){
	$username = sanitize($username);
	ob_start();
	return (mysql_result(mysql_query("SELECT * FROM adminusers WHERE username = '$username'"), 0) == 1) ? true : false;
	ob_end_flush();
}
function user_id_from_username($username){
	$username = sanitize($username);
	return mysql_result(mysql_query("SELECT * FROM adminusers WHERE username = '$username'"), 0, 'user_id');
}

function login($username, $password){
	$user_id = user_id_from_username($username);
	
	$username = sanitize($username);
	$password = md5($password);
	
	return (mysql_result(mysql_query("SELECT COUNT('user_id') FROM adminusers WHERE username = '$username' AND password = '$password'"), 0) == 1) ? $user_id : false;
}
?>