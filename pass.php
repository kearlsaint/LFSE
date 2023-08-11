<?php
@session_start();
if(isset($_POST['pass'])) {
	if($_POST['pass'] == 'kearl') {
		$_SESSION['LFSE_PASS'] = true;
		header('Location: index.php');
		exit;
	}
}
session_destroy();
?>
<form method="post">
	<input type='password' placeholder='Password' name='pass' autofocus>
	<input type='submit' value='Submit'>
</form>
<style>
form {
	margin-top: -50px;
	margin-left: -100px;
	width: 200px;
	height: 40px;
	position: fixed;
	top: 50%;
	left: 50%;
	background: #eee;
	border-radius: 2px;
	text-align: center;
	padding-top: 12px;
	padding-bottom: 24px;
}
input {
	width: 180px;
	margin-left: 6px;
	margin-right: 6px;
	padding: 4px;