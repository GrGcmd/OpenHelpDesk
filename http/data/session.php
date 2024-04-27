<?php
if (isset($_COOKIE['id_user'])==null or isset($_COOKIE['u_name'])==null or isset($_POST['logout']))
{
  setcookie ("id_user", "", time() - 36000,'/');
  setcookie ("u_name", "", time() - 36000,'/');
  header('location: /login.php');
  exit();
} else {
  $id_user = substr(md5(base64_decode($_COOKIE['u_name']).'it_helpi'), 0, 15);
  if (isset($_COOKIE['id_user'])==$id_user) {
    setcookie ('id_user',$_COOKIE['id_user'],time()+604800,'/');
    setcookie ('u_name',$_COOKIE['u_name'],time()+604800,'/');
  } else {
   setcookie ("id_user", time() - 36000);
   setcookie ("u_name", time() - 36000);
   header('location: /login.php');
    exit();
  }
}
?>
