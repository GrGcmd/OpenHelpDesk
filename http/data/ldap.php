<?php

    $query_ldap = mysqli_query($link,"SELECT status_int FROM bid_system WHERE id=7");
    $data_ldap = mysqli_fetch_assoc($query_ldap);
    if ($data_ldap['status_int']==1) {

        $query_ldap = mysqli_query($link,"SELECT id,status_text FROM bid_system WHERE id in (2,3,4,5,6)");
        $data_ldap = mysqli_fetch_assoc ($query_ldap);
        $server = $data_ldap ['status_text'];
        $data_ldap = mysqli_fetch_assoc ($query_ldap);
        $port = $data_ldap ['status_text'];
        $data_ldap = mysqli_fetch_assoc ($query_ldap);
        $domain = $data_ldap ['status_text'];
        $data_ldap = mysqli_fetch_assoc ($query_ldap);
        $ldap_dn = $data_ldap ['status_text'];
        $data_ldap = mysqli_fetch_assoc ($query_ldap);
        $group_member = $data_ldap ['status_text'];

        // print_r($server);
        // print_r($port);
        // print_r($domain);
        // print_r($ldap_dn);
        // print_r($group_member);
        // exit();

        $ldap_connection = ldap_connect('ldap://'.$server, $port); 

        if (!$ldap_connection) {
            $alarms = $alarms.'<center><div class="alert alert-warning" role="alert">Ошибка подключения к серверу AD.</div></center>';
        } else {
    
          ldap_set_option($ldap_connection, LDAP_OPT_PROTOCOL_VERSION, 3);
          ldap_set_option($ldap_connection, LDAP_OPT_REFERRALS, 0);
          ldap_set_option($ldap_connection, LDAP_OPT_NETWORK_TIMEOUT, 5);
           
          $bind = @ldap_bind($ldap_connection, $login.'@'.$domain, $password_domain);
           
          if (!$bind) {
            $alarms = $alarms.'<div class="alert alert-warning text-center" role="alert">Ошибка авторизации. Логин или пароль не верны</div><br>';
          } else {
            $filter = "(sAMAccountName=" . $login . ")";
            $attr = array("memberof","givenname","sn","mail","mobile","department","title");
            $group_check = Null;
    
            $result = ldap_search($ldap_connection, $ldap_dn, $filter, $attr);
            $entries = ldap_get_entries($ldap_connection, $result);
            //print_r ($entries);
            for ($i = 0; $i != $entries['count']; $i++)  {
              for ($j = 0; $j != $entries[0]['memberof']['count']; $j++) {
                if (strripos($entries[0]['memberof'][$j],$group_member) != False) {
                  $group_check = 1; 
                  break;
                } 
              }
            }
            if (!$group_check) {
                $alarms = $alarms.'<center><div class="alert alert-warning" role="alert">Отсутствуют права доступа к порталу. Запросите доступ в ИТ.</div></center>';
            } else {
                if (isset($entries[0]['givenname'][0])) {$first_name = $entries[0]['givenname'][0];} else {$first_name=' ';}
                if (isset($entries[0]['sn'][0])){$last_name = $entries[0]['sn'][0]; } else {$last_name = ' ';}
                if (isset($entries[0]['mail'][0])){$mail = $entries[0]['mail'][0]; } else {$mail = ' ';}
                if (isset($entries[0]['mobile'][0])){$mobile = $entries[0]['mobile'][0]; } else {$mobile = ' ';}
                if (isset($entries[0]['department'][0])){$department = $entries[0]['department'][0]; } else {$department = ' ';}
                if (isset($entries[0]['title'][0])){$title = $entries[0]['title'][0]; } else {$title = ' ';}
                // echo $first_name.'</br>';
                // echo $last_name.'</br>';
                // echo $department.'</br>';
                // echo $title.'</br>';
                // echo $mobile.'</br>';
                // echo $mail.'</br>';
                // exit();

                $query = mysqli_query($link,"SELECT id FROM users WHERE login='".mysqli_real_escape_string($link,$login)."' and active=1 and local=0 and domain=1 LIMIT 1");
                $data = mysqli_fetch_assoc($query);
                $result = False;
                if (isset($data['id'])) {
                    $result=mysqli_query($link,"UPDATE users SET id_user='".$id_user."',unit='".$department."',position='".$title."',f_name='".$first_name."',s_name='".$last_name."',local=0,domain=1,phone='".$mobile."',email='".$mail."' WHERE login='".$login."' LIMIT 1");
                    if (!$result){
                      $alarms = $alarms.'<div class="alert alert-danger text-center" role="alert"><b>Ошибка #3 обновления данных о пользователе.</b></div><br>';
                    } 
                  } else {
                    $query = mysqli_query($link, "SELECT count(id) AS max_count_user FROM users LIMIT 1");
                    $data = mysqli_fetch_assoc($query);
                    $id = $data['max_count_user'] + 1; 

                    $result=mysqli_query($link,"INSERT INTO users (id,id_user,active,login,password,local,domain,role,unit,position,f_name,s_name,phone,email,telegram_id,last_action_1,last_action_2)
                    VALUES ('$id','$id_user','1','$login','***','0','1','user','$department','$title','$first_name','$last_name','$mobile','$mail','***','*','*')");
                    if (!$result) {$alarms = $alarms.'<div class="alert alert-danger text-center" role="alert"><b>Ошибка #4 добавления данных о пользователе.</b></div><br>';}
                  }
                
                if ($result) {
                  $query = mysqli_query($link,"SELECT f_name,s_name FROM users WHERE login='".mysqli_real_escape_string($link,$login)."' and active=1 and local=0 and domain=1 LIMIT 1");
                  $data = mysqli_fetch_assoc($query);
                  
                  unset($_COOKIE['id_user']);
                  unset($_COOKIE['u_name']);
                  setcookie ('id_user',$id_user,time()+604800);
                  setcookie ('u_name',base64_encode($data['f_name']." ".$data['s_name']),time()+604800);
                  ldap_close($ldap_connection);
                  echo '<meta http-equiv="Refresh" content="0; url=/help/" />';
                } else {
                  $alarms = $alarms.'<div class="alert alert-danger text-center" role="alert"><b>Ошибка #5 авторизаии через домен.</b></div><br>';
                }

                // ldap_close($ldap_connection);
                // header('location: /');
                
                exit();
              }
            }
          ldap_close($ldap_connection);
          } 
    } else {
        $alarms = $alarms.'<div class="alert alert-warning text-center" role="alert">Ошибка авторизации. Логин или пароль не верны</div><br>';
    }

    
    
    
    
?>
