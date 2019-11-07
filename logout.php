<?php session_start(); ?>
<?php 
unset($_SESSION);
session_destroy();
$_SESSION = [];

header('Location: login.php');
exit;
?>
