<?php
session_start();
unset($_COOKIE['id_user']);
unset($_COOKIE['u_name']);
setcookie ('id_user','',time() - 36000);
setcookie('u_name', '', time() - 36000);
header('location: /');
?>
