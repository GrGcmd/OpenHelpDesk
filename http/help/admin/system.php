<?php
require("../../data/header.php");
require("../../data/db.php");
require("../../data/session.php");
require("../../menu.php");
require("../../data/clean.php");

//Проверка на роль
if ($data['role']!='superadmin') {
  echo '<meta http-equiv="Refresh" content="0; url=/help/" />';
  exit();
}

if (isset($_POST['ldap_update'])) {
 
  if (isset($_POST["ldap_server"])) {$ldap_server = clean($_POST['ldap_server']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["ldap_port"])) {$ldap_port = clean($_POST['ldap_port']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["ldap_domain"])) {$ldap_domain = clean($_POST['ldap_domain']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["ldap_directory"])) {$ldap_directory = clean($_POST['ldap_directory']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["ldap_group"])) {$ldap_group = clean($_POST['ldap_group']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["ldap_active"])) {$ldap_active = clean($_POST['ldap_active']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

  try {
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$ldap_server."' WHERE id=2 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$ldap_port."' WHERE id=3 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$ldap_domain."' WHERE id=4 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$ldap_directory."' WHERE id=5 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$ldap_group."' WHERE id=6 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_int='".$ldap_active."' WHERE id=7 LIMIT 1");
  
    if ($result=='true') {
      echo '</br><div style="text-align:center;"><div class="alert alert-success text-center" role="alert">Данные обновлены!</div></div>
      <div style="text-align:center;">
        <a href="/help/admin/system.php">
          <button type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
            </svg>
              Вернуться назад
          </button>
        </a>
      </div>
      <meta http-equiv="refresh" content="2;url=/help/admin/system.php">
      ';
      goto end_load;
    }
    else {
      echo '</br><div class="alert alert-warning text-center" role="alert">Ошибка обновления данных LDAP! Просьба обратиться к разработчику.</div>
      <div style="text-align:center;">
        <a href="/help/admin/system.php">
          <button type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
            </svg>
              Вернуться назад
          </button>
        </a>
      </div>
      <meta http-equiv="refresh" content="2;url=/help/admin/system.php">
      ';
      goto end_load;
    }
  } catch (Error $ex) {
    echo'</br>s<div style="text-align:center;"><div class="alert alert-warning text-center" role="alert">Что то пошло не так.</div></div>';
    //echo str($exs);
  }

}

if (isset($_POST['smtp_update'])) {
   if (isset($_POST["smtp_address"])) {$smtp_address = clean($_POST['smtp_address']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
   if (isset($_POST["smtp_port"])) {$smtp_port = clean($_POST['smtp_port']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
   if (isset($_POST["smtp_login"])) {$smtp_login = clean($_POST['smtp_login']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
   if (isset($_POST["smtp_password"])) {$smtp_password = clean($_POST['smtp_password']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
   if (isset($_POST["smtp_from"])) {$smtp_from = clean($_POST['smtp_from']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
   if (isset($_POST["smtp_active"])) {$smtp_active = clean($_POST['smtp_active']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

   try {
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$smtp_address."' WHERE id=8 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$smtp_port."' WHERE id=9 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$smtp_login."' WHERE id=11 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$smtp_password."' WHERE id=12 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_text='".$smtp_from."' WHERE id=13 LIMIT 1");
    $result=mysqli_query($link,"UPDATE bid_system SET status_int='".$smtp_active."' WHERE id=14 LIMIT 1");
  
    if ($result=='true') {
      echo '</br><div style="text-align:center;"><div class="alert alert-success text-center" role="alert">Данные обновлены!</div></div>
      <div style="text-align:center;">
        <a href="/help/admin/system.php">
          <button type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
            </svg>
              Вернуться назад
          </button>
        </a>
      </div>
      <meta http-equiv="refresh" content="2;url=/help/admin/system.php">
      ';
      goto end_load;
    }
    else {
      echo '</br><div class="alert alert-warning text-center" role="alert">Ошибка обновления данных SMTP! Просьба обратиться к разработчику.</div>
      <div style="text-align:center;">
        <a href="/help/admin/system.php">
          <button type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
            </svg>
              Вернуться назад
          </button>
        </a>
      </div>
      <meta http-equiv="refresh" content="2;url=/help/admin/system.php">
      ';
      goto end_load;
    }
  } catch (Error $ex) {
    echo'</br>s<div style="text-align:center;"><div class="alert alert-warning text-center" role="alert">Что то пошло не так.</div></div>';
    //echo str($exs);
  }
   
}

if (isset($_POST['edit_actual_status_system'])) {
  if (isset($_POST["tek_status"])) {$tek_status = clean($_POST['tek_status']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

  $knopka_error = '
  <div class="alert alert-danger text-center" role="alert">Что то пошло не так. Просьба обратиться к разработчику.</div></br>
  <div style="text-align:center;">
    <a href="/help/admin/"><button type="submit" class="btn btn-warning">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
      </svg>
      Вернуться назад
      </button>
    </a>
  </div>';

  $result=mysqli_query($link,"UPDATE bid_system SET status_int='$tek_status' WHERE id=1 LIMIT 1");
  if ($result=='true') {
    echo '<div class="alert alert-success text-center" role="alert">Статус режима обслуживания обновлён.</div></br>
    <div style="text-align:center;">
      <a href="/help/admin/system.php">
        <button type="submit" class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
          </svg>
            Вернуться назад
        </button>
      </a>
    </div>
    <meta http-equiv="refresh" content="2;url=/help/admin/system.php">
    ';
  } else {
    echo $knopka_error;
    goto end_load;
  }
  goto end_load;
}

if (isset($_POST['edit_bid_timeout'])) {
  if (isset($_POST["bid_timeout"])) {$bid_timeout = clean($_POST['bid_timeout']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

  $knopka_error = '
  <div class="alert alert-danger text-center" role="alert">Что то пошло не так. Просьба обратиться к разработчику.</div></br>
  <div style="text-align:center;">
    <a href="/help/admin/"><button type="submit" class="btn btn-warning">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
      </svg>
      Вернуться назад
      </button>
    </a>
  </div>';

  $result=mysqli_query($link,"UPDATE bid_system SET status_int='$bid_timeout' WHERE id=16 LIMIT 1");
  if ($result=='true') {
    echo '<div class="alert alert-success text-center" role="alert">Timeout обновлён.</div></br>
    <div style="text-align:center;">
      <a href="/help/admin/system.php">
        <button type="submit" class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
          </svg>
            Вернуться назад
        </button>
      </a>
    </div>
    <meta http-equiv="refresh" content="2;url=/help/admin/system.php">
    ';
  } else {
    echo $knopka_error;
    goto end_load;
  }
  goto end_load;
}

if (isset($_POST['edit_system_domain'])) {
  if (isset($_POST["system_domain"])) {$system_domain = clean($_POST['system_domain']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

  $knopka_error = '
  <div class="alert alert-danger text-center" role="alert">Что то пошло не так. Просьба обратиться к разработчику.</div></br>
  <div style="text-align:center;">
    <a href="/help/admin/"><button type="submit" class="btn btn-warning">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
      </svg>
      Вернуться назад
      </button>
    </a>
  </div>';

  $result=mysqli_query($link,"UPDATE bid_system SET status_text='$system_domain' WHERE id=15 LIMIT 1");
  if ($result=='true') {
    echo '<div class="alert alert-success text-center" role="alert">Доменное имя изменено.</div></br>
    <div style="text-align:center;">
      <a href="/help/admin/system.php">
        <button type="submit" class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
          </svg>
            Вернуться назад
        </button>
      </a>
    </div>
    <meta http-equiv="refresh" content="2;url=/help/admin/system.php">
    ';
  } else {
    echo $knopka_error;
    goto end_load;
  }
  goto end_load;
}

$query_system = mysqli_query($link,"SELECT id,status_int,status_text FROM bid_system");
while ($data_system = mysqli_fetch_assoc($query_system)) {
  if ($data_system['id']==2) {
    $ldap_server = $data_system['status_text'];
  } else if ($data_system['id']==3) {
    $ldap_port = $data_system['status_text'];
  } else if ($data_system['id']==4) {
    $ldap_domain = $data_system['status_text'];
  } else if ($data_system['id']==5) {
    $ldap_dn = $data_system['status_text'];
  } else if ($data_system['id']==6) {
    $ldap_group_member = $data_system['status_text'];
  } else if ($data_system['id']==7) {
    $ldap_active = $data_system['status_int'];
  } else if ($data_system['id']==8) {
    $smtp_server = $data_system['status_text'];
  } else if ($data_system['id']==9) {
    $smtp_port = $data_system['status_text'];
  } else if ($data_system['id']==11) {
    $smtp_login = $data_system['status_text'];
  } else if ($data_system['id']==12) {
    $smtp_password = $data_system['status_text'];
  } else if ($data_system['id']==13) {
    $smtp_from = $data_system['status_text'];
  } else if ($data_system['id']==14) {
    $smtp_active = $data_system['status_int'];
  } else if ($data_system['id']==15) {
    $system_domain = $data_system['status_text'];
  } else if ($data_system['id']==16) {
    $bid_timeout = $data_system['status_int'];
  } 
}


?>
<div class="container">
  <h4 style="text-align:center;">Настройки системы</h3></br>
  <div class="row justify-content-start">
    <div class="col col-md-4 py-3 border border-primary bg-light rounded-2">
      <form action="" method="post">
        <h5 style="text-align:center;">Режим обслуживания</h3>
        </br></br>
        <div class="row justify-content-center">
          <div class="col col-md-5">
            <div class="mb-3">
                <select name="tek_status" class="form-select" aria-label="Default select example">
                  <?php 
                    $query = mysqli_query($link, "SELECT status_int FROM bid_system WHERE id=1 LIMIT 1");
                    $data = mysqli_fetch_assoc($query);
                    if ($data['status_int'] == 1) {
                      $actual_status = '
                      <option value="1" selected>Включен</option>
                      <option value="0">Выключен</option>';
                    }
                    else {
                      $actual_status = '
                      <option value="1">Включен</option>
                      <option value="0" selected>Выключен</option>';
                    }
                    echo $actual_status;
                  ?>
                </select>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col col-md-12 text-center">
            <button type="submit" name="edit_actual_status_system" class="btn btn-outline-primary">Изменить</button>
          </div>
        </div>
      </form>
    </div>
    <div class="col col-md-4 py-3 border border-primary bg-light rounded-2">
      <form action="" method="post">
        <h5 style="text-align:center;">Timeout закрытия заявок</h3>
        <p style="text-align:center;">Время, после которого заявки в статусе "Завершённые" перейдут в статус "Закрытые"</p>
        <div class="row justify-content-center">
          <div class="col col-md-5">
            <div class="mb-3">
                <input type="text" name="bid_timeout" class="form-control" <?php echo 'value="'.$bid_timeout.'"'; ?>>
                <small id="passwordHelpBlock" class="form-text text-muted">
                Время в часах
                </small>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col col-md-12 text-center">
            <button type="submit" name="edit_bid_timeout" class="btn btn-outline-primary">Изменить</button>
          </div>
        </div>
      </form>
    </div>
    <div class="col col-md-4 py-3 border border-primary bg-light rounded-2">
      <form action="" method="post">
        <h5 style="text-align:center;">Domain name</h3>
        <div class="row justify-content-center">
          <div class="col col-md-10">
            <div class="mb-3">
                <input type="text" name="system_domain" class="form-control" <?php echo 'value="'.$system_domain.'"'; ?>>
                <small id="passwordHelpBlock" class="form-text text-muted">
                  Например: helpdesk.test.local
                </small>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col col-md-12 text-center">
            <button type="submit" name="edit_system_domain" class="btn btn-outline-primary">Изменить</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="row justify-content-start">
    <div class="col col-md-6 py-3 border border-primary bg-light rounded-2">
      <h5 style="text-align:center;">Подключение к Active Directory</h3>
      <p style="text-align:center;">Интеграция через LDAP</p>
      <div class="row justify-content-start">
        <div class="col col-md-8 text-center">
          <form action="" method="post">
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Сервер</label>
                <input type="text" name="ldap_server" class="form-control" id="exampleFormControlInput1" <?php echo 'value="'.$ldap_server.'"'; ?>>
                <small id="passwordHelpBlock" class="form-text text-muted">
                Ip или имя сервера
                </small>
              </div>
              </div>
              <div class="col col-md-4 text-center">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Порт</label>
                  <input type="text" name="ldap_port" class="form-control" id="exampleFormControlInput1" <?php echo 'value="'.$ldap_port.'"'; ?>>
                  <small id="passwordHelpBlock" class="form-text text-muted">
                  Номер порта
                  </small>
                </div>
              </div>
            </div>
            <div class="row justify-content-start">
              <div class="col col-md-5 text-center">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Домен</label>
                  <input type="text" name="ldap_domain" class="form-control" id="exampleFormControlInput1" <?php echo 'value="'.$ldap_domain.'"'; ?>>
                  <small id="passwordHelpBlock" class="form-text text-muted">
                  domain.local
                  </small>
                </div>
              </div>
              <div class="col col-md-7 text-center">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Директория для поиска</label>
                  <input type="text" name="ldap_directory" class="form-control" id="exampleFormControlInput1" <?php echo 'value="'.$ldap_dn.'"'; ?>>
                  <small id="passwordHelpBlock" class="form-text text-muted">
                  DC=domain,DC=local
                  </small>
                </div>
              </div>
            </div>
            <div class="row justify-content-start">
              <div class="col col-md-12 text-center">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Группа доступа в домене</label>
                  <input type="text" name="ldap_group" class="form-control" id="exampleFormControlInput1" <?php echo 'value="'.$ldap_group_member.'"'; ?>>
                  <small id="passwordHelpBlock" class="form-text text-muted">
                    Группа для доступа в домене
                  </small>
                </div>
              </div>
            </div>
            <div class="row justify-content-center">
              <div class="col col-md-6 text-center">
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Статсуc</label>
                  <select name="ldap_active" class="form-select" aria-label="Default select example">
                    <?php
                    if ($ldap_active==1) {
                      echo '<option value="1" selected>Включен</option>
                      <option value="0">Выключен</option>';
                    } else {
                      echo '<option value="1">Включен</option>
                      <option value="0" selected>Выключен</option>';
                    }
                    ?>
                  </select>
                </div>
            </div>
            <div class="row justify-content-center">
              <div class="col col-md-12 text-center">
                <button type="submit" name="ldap_update" class="btn btn-outline-primary">Изменить</button>
              </div>
            </div>
          </form>
      </div>
    </div>
    <div class="col col-md-6 py-3 border border-primary bg-light rounded-2">
      <h5 style="text-align:center;">Настройка отправки почты</h3>
      <p style="text-align:center;">Интеграция через SMTP</a>
      <form action="" method="post">
        <div class="row justify-content-start">
          <div class="col col-md-8 text-center">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Адрес</label>
              <input type="text" name="smtp_address" class="form-control" id="exampleFormControlInput1" placeholder="ip адрес или имя сервера" <?php echo 'value="'.$smtp_server.'"'; ?>>
            </div>
          </div>
          <div class="col col-md-4 text-center">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Порт</label>
              <input type="text" name="smtp_port" class="form-control" id="exampleFormControlInput1" placeholder="номер порта" <?php echo 'value="'.$smtp_port.'"'; ?>>
            </div>
          </div>
        </div>
        <div class="row justify-content-start">
          <div class="col col-md-6 text-center">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Логин</label>
              <input type="text" name="smtp_login" class="form-control" id="exampleFormControlInput1" placeholder="Логин" <?php echo 'value="'.$smtp_login.'"'; ?>>
            </div>
          </div>
          <div class="col col-md-6 text-center">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Пароль</label>
              <input type="password" name="smtp_password" class="form-control" id="exampleFormControlInput1" placeholder="Пароль" <?php echo 'value="'.$smtp_password.'"'; ?>>
            </div>
          </div>
        </div>
        <div class="row justify-content-start">
          <div class="col col-md-12 text-center">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">e-mail отправителя</label>
              <input type="text" name="smtp_from" class="form-control" id="exampleFormControlInput1" placeholder="Например, no_replay@test.local" <?php echo 'value="'.$smtp_from.'"'; ?>>
            </div>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col col-md-6 text-center">
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label">Статсуc</label>
              <select class="form-select" name="smtp_active" aria-label="Default select example">
                <?php
                  if ($smtp_active==1) {
                    echo '<option value="1" selected>Включен</option>
                    <option value="0">Выключен</option>';
                  } else {
                    echo '<option value="1">Включен</option>
                    <option value="0" selected>Выключен</option>';
                  }
                  ?>
              </select>
            </div>
          </div>
        </div>
        </br>
        <div class="row justify-content-center">
          <div class="col col-md-12 text-center">
            <button type="submit" name="smtp_update" class="btn btn-outline-primary">Изменить</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  
  </br>
  <div class="row justify-content-md-center">
      <div class="col col-lg-12 py-3 border border-primary bg-light rounded-2">
        <h4 style="text-align:center;"><strong>Инструкция</strong></h4>
        </br>
        <h5 style="text-align: center;">Подключение к Active Directory<br /><br /></h5>
        <div style="text-align: center;">При входе пользователя под доменной учётной записью, будут прочитаны следующие атрибуты:&nbsp;</div>
        <p style="text-align: center;">"givenname","sn"."mail","mobile","department","title"</p>
        <p style="text-align: center;">Заполните их корректно и данные о пользователе будут обновляться автоматически.&nbsp;</p>
        <h5 style="text-align: center;">Настройка SMTP</h5>
        <p style="text-align: center;">SMTP используется для отправки уведомлений пользователям. Работает как отдельная служба (простой скрипт) для Windows или Linux, написанный на Python.</p>
  </div>
</div>
</br>

<?php
  end_load:
  require("../../data/footer.php");
?>
