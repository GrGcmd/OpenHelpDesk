<?php
require("data/header.php");
require("data/db.php");
require("data/clean.php");

$alarms = Null;
if(isset($_POST['submit_login']))
{
  if (clean(md5($_POST['norobot'])) != $_SESSION['randomnr1']) { 
    $alarms = $alarms.'<div class="alert alert-warning text-center" role="alert">Ошибка ввода капчи. Попробуйте ещё раз.</div>';
  } else {
    if (isset($_POST["login"])) {$login = clean($_POST["login"]);} else {exit();}
    if (isset($_POST["password"])) {$password_local = md5(md5(trim(clean($_POST['password'])))); $password_domain=$_POST['password'];} else {exit();}
    $id_user = substr(md5($login.'it_help'), 0, 20);
    try {
      $query = mysqli_query($link,"SELECT password,f_name,s_name FROM users WHERE login='".mysqli_real_escape_string($link,$login)."' and active=1 and local=1 and domain=0 LIMIT 1");
      $data = mysqli_fetch_assoc($query);
      if (isset($data['password']))
      {
          if ($password_local == $data['password']) {
          unset($_COOKIE['id_user']);
          unset($_COOKIE['u_name']);
          setcookie ('id_user',$id_user,time()+604800);
          setcookie ('u_name',base64_encode($data['f_name']." ".$data['s_name']),time()+604800);
          $result=mysqli_query($link,"UPDATE users SET id_user='".$id_user."' WHERE login='".$login."' LIMIT 1");
            if ($result=='true'){
              echo '<meta http-equiv="Refresh" content="0; url=/help/" />';
            }
            else {
             $alarms = $alarms.'<div class="alert alert-danger text-center" role="alert"><b>Ошибка #1 обновления данных о пользователе</b></div><br>';
            }
          } else {
            $alarms = $alarms.'<div class="alert alert-warning text-center" role="alert">Ошибка авторизации. Логин или пароль не верны</div><br>';
          }
      } else {
        require("data/ldap.php");
      }
    } catch (Error $ex) {
      $alarms = $alarms.'<div class="alert alert-warning text-center" role="alert">Что то пошло не так.</div>';
    }
  }
}
if(isset($_POST['submit_helpme']))
{
  if (clean(md5($_POST['norobot'])) != $_SESSION['randomnr2']) { 
    $alarms = $alarms.'<div class="alert alert-warning text-center" role="alert">Ошибка ввода капчи. Попробуйте ещё раз.</div>';
  } else {
    if (isset($_POST["login"])) {$login = clean($_POST["login"]);} else {exit();}
    if (isset($_POST["phone"])) {$phone = clean($_POST["phone"]);} else {exit();}
    
    $query = mysqli_query($link, "SELECT count(id) AS max_count_bid FROM bid LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    $id_bid = $data['max_count_bid'] + 1;

    $text_problem = 'Прошу восстановить доступ на портал HelpDesk для логина: '.$login.'.</br>Контакный номер: '.$phone;

    $result=mysqli_query($link,"INSERT INTO bid (id,priority,template,filial,theme,status,owner,contractor,date,message,alarm_create,alarm_update)
    VALUES ('$id_bid','4','0','0','Восстановление доступа к порталу','1','0','0','$tek_date','$text_problem',1,0)");
    if ($result=='true') {
      $alarms = $alarms.'<div class="alert alert-warning text-center" role="alert">Заявка успешно создана.</br>Уникальный ID вашей заявки: <b>'.$id_bid.'</b></br>Ожидайте. С вами свяжутся.</div></br></br>';
    }
    $query = mysqli_query($link, "SELECT count(id) AS max_count FROM bid_history LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    $id_history = $data['max_count'] + 1;
    $result=mysqli_query($link,"INSERT INTO bid_history (id,id_bid,status,date,user) 
    VALUES ('$id_history','$id_bid','1','$tek_date','0')");
}
}
?>
<link rel="stylesheet" href="css/authorization.css">
<div class="container">
  <div class="row justify-content-center">
    <div class="row justify-content-center">
      <div class="col col-lg-6 py-5 rounded-2">
        <h2 style="text-align:center;">IT help desk</h2>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col col-lg-10 py-3 rounded-2">
        <form class="form-signin w-100 m-auto" role="form" method="post">
          <div style="text-align:center;">
            <a href="/"><img class="mb-3" src="file/images/authorization.png" width="100" height="100" class="d-inline-block align-top" alt="IMG"></a>
            <h3 class="form-signin-heading mb-3">Авторизация</h3>
          </div>
          <input  name="login" type="text" class="form-control" placeholder="Логин" required autofocus></br>
          <input name="password" type="password" class="form-control" placeholder="Пароль" required></br>
          <div class="mb-3 row">
            <img src="data/captcha.php" class="col-sm-6 col-form-label">
            <div class="col-sm-6 align-self-center">
              <input name="norobot" type="text" class="form-control" placeholder="Капча" required>
            </div>
          </div>
          <button name="submit_login" class="w-100 btn btn-lg btn-success" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
              <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"></path>
              <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
            </svg>
            Войти</button>
        </form>
      </div>
    </div>
  </div>
  <div class="row justify-content-center py-3">
    <div class="col col-lg-5">
      <?php echo $alarms;?>
    </div>
  </div>
  <div class="row justify-content-center py-3">
    <div class="col col-lg-5" style="text-align:center;">
      <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#Help_me">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-universal-access" viewBox="0 0 16 16">
          <path d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6 5.5l-4.535-.442A.531.531 0 0 1 1.531 4H14.47a.531.531 0 0 1 .066 1.058L10 5.5V9l.452 6.42a.535.535 0 0 1-1.053.174L8.243 9.97c-.064-.252-.422-.252-.486 0l-1.156 5.624a.535.535 0 0 1-1.053-.174L6 9z"/>
        </svg> Не могу войти
      </button>
      <!-- Modal -->
      <div class="modal fade" id="Help_me" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Не могу войти...</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="text-center">Заполните форму ниже и мы оперативно восстановим доступ.</p>
              <label for="inputPassword5" class="form-label">Логин в домене:</label>
              <form class="form-signin w-100 m-auto" role="form" method="post">
                <input  name="login" type="text" class="form-control" placeholder="i.pupkin" required autofocus></br>
                <label for="inputPassword5" class="form-label">Контактный телефон для связи:</label>
                <input  name="phone" type="tel" class="form-control" placeholder="+7(000)000-00-00" required autofocus></br>
                <div class="mb-3 row">
                  <img src="data/captcha2.php" class="col-sm-6 col-form-label">
                  <div class="col-sm-6 align-self-center">
                    <input name="norobot" type="text" class="form-control" placeholder="Капча" required>
                  </div>
                </div>
                <button name="submit_helpme" type="submit" class="btn btn-warning">Отправить</button>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Назад</button>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col col-lg-10 py-3 rounded-2">
      <p class="text-center">Рекомендуется использовать браузер на основе Chromium.</p>
    </div>
  </div>
</div>
<?php include("data/footer.php");?>
