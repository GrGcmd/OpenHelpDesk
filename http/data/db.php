<?php

function write_log ($text) {
  $tek_date = date("d-m-Y h:i:s A");
  $log_file="log/log.log";
  $i=1;
  $file_read = file($log_file);
  if (count($file_read)>5000) {
    while ($i!=0) {
      if (file_exists($log_file.$i)==FALSE) {
        rename($log_file, $log_file.$i);
        file_put_contents($log_file,$tek_date.' || IP: '.$_SERVER['REMOTE_ADDR'].' || '.$text.PHP_EOL, FILE_APPEND | LOCK_EX);
        $i=0;
      } else {
        $i++;
      }
    }
  }
  else {
    file_put_contents($log_file,$tek_date.' || IP: '.$_SERVER['REMOTE_ADDR'].' || '.$text.PHP_EOL, FILE_APPEND | LOCK_EX);
  }
}

//Подключение к БД
$sdd_db_host='localhost'; // Host
$sdd_db_name='ithelp'; //Name BD
$sdd_db_user='ithelpuser'; //Логин
$sdd_db_pass='*******'; //Пароль
$link = mysqli_connect($sdd_db_host,$sdd_db_user, $sdd_db_pass, $sdd_db_name);
if (!$link) {
    echo "Ошибка: Невозможно установить соединение с сервером БД." . PHP_EOL;
    echo "</br>";
    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "</br>";
    //echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$tek_date = strtotime(date("d-m-Y h:i:s A"));
?>
