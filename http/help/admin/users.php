<?php
include("../../data/header.php");
include("../../data/db.php");
include("../../data/session.php");
include("../../menu.php");
include("../../data/clean.php");


//Проверка на роль
if ($data['role']!='superadmin') {
  echo '<meta http-equiv="Refresh" content="0; url=/help/" />';
  exit();
}



if(isset($_POST['submit_register']))
{
  
  if (isset($_POST["login"])) {$login = clean($_POST["login"]);} else {echo '<div style="text-align:center;">O_o 1</div>'; exit();}
  if (isset($_POST["f_name"])) {$f_name = clean($_POST["f_name"]);} else {echo '<div style="text-align:center;">O_o 2</div>'; exit();}
  if (isset($_POST["s_name"])) {$s_name = clean($_POST["s_name"]);} else {echo '<div style="text-align:center;">O_o 3</div>'; exit();}
  if (isset($_POST["email"])) {$email = clean($_POST["email"]);} else {echo '<div style="text-align:center;">O_o 4</div>'; exit();}
  if (isset($_POST["phone"])) {$phone = clean($_POST["phone"]);} else {echo '<div style="text-align:center;">O_o 5</div>'; exit();}
  if (isset($_POST["unit"])) {$unit = clean($_POST["unit"]);} else {echo '<div style="text-align:center;">O_o 6</div>'; exit();}
  if (isset($_POST["position"])) {$position = clean($_POST["position"]);} else {echo '<div style="text-align:center;">O_o 6</div>'; exit();}
  if (isset($_POST["user_role"])) {$user_role = clean($_POST["user_role"]);} else {echo '<div style="text-align:center;">O_o 7</div>'; exit();}
  if (isset($_POST["password"])) {if ($_POST["password"] != NULL and $_POST["password"] != '') {$password = md5(md5(trim(clean($_POST['password']))));} else {$password = 'not_change';} } else {exit();}

    $err = [];
    // проверям логин
    if(!preg_match("/^[a-zA-Z0-9]+$/",$login))
    {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    if(strlen($login) < 3 or strlen($login) > 30)
    {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($link, "SELECT id FROM users WHERE login='".mysqli_real_escape_string($link, $login)."'");
    if(mysqli_num_rows($query) > 0)
    {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {
        $query = mysqli_query($link, "SELECT count(id) AS max_count_user FROM users LIMIT 1");
        $data = mysqli_fetch_assoc($query);
        $id_user = $data['max_count_user'] + 1;  

        $user_id=md5($login);
        $result=mysqli_query($link,"INSERT INTO users (id,id_user,active,login,password,local,domain,role,unit,position,f_name,s_name,phone,email,telegram_id,last_action_1,last_action_2)
        VALUES ('$id_user','$user_id','1','$login','$password','1','0','$user_role','$unit','$position','$f_name','$s_name','$phone','$email','***','*','*')");
        if ($result=='true') {echo '<div class="alert alert-success text-center" role="alert"><b>Успешно зарегистрирован!</b></div>';}
        else {echo '<div style=\"text-align:center;\">Ошибка записи!</div>'; exit();}

    }
    else
    {
        echo '<div style="text-align:center;"><b>При регистрации произошли следующие ошибки:</b></div><br>';
        foreach($err AS $error)
        {
            echo '<div style="text-align:center;">'.$error.'</div></br>';
        }
    }
}


if(isset($_POST['submit_update']))
{
  
  if (isset($_POST["login"])) {$login = clean($_POST["login"]);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["user_role"])) {$user_role = clean($_POST["user_role"]);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  
  $query = mysqli_query($link,"SELECT id FROM users WHERE login='".mysqli_real_escape_string($link,$login)."' and active=1 and local=1 and domain=0 LIMIT 1");
  $data = mysqli_fetch_assoc($query);
  if (isset($data['id'])){
    if (isset($_POST["f_name"])) {$f_name = clean($_POST["f_name"]);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST["s_name"])) {$s_name = clean($_POST["s_name"]);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST["email"])) {$email = clean($_POST["email"]);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST["phone"])) {$phone = clean($_POST["phone"]);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST["unit"])) {$unit = clean($_POST["unit"]);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST["position"])) {$position = clean($_POST["position"]);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST["user_role"])) {$user_role = clean($_POST["user_role"]);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

    if (isset($_POST["password"])) {if ($_POST["password"] != NULL and $_POST["password"] != '') {$password = md5(md5(trim(clean($_POST['password']))));} else {$password = 'not_change';} } else {exit();}

    if ($password == 'not_change') {
      $result=mysqli_query($link,"UPDATE users SET f_name='".$f_name."',s_name='".$s_name."',email='".$email."',phone='".$phone."',unit='".$unit."',position='".$position."',role='".$user_role."' WHERE login='".$login."' LIMIT 1");
      if ($result=='true') {
        echo '</br><div style="text-align:center;"><div class="alert alert-success text-center" role="alert">Данные обновлены!</div></div>';
      }
      else {
        echo '</br><div class="alert alert-warning text-center" role="alert">Ошибка обновления данных! Просьба обратиться к разработчику.</div>';
      }
    } else {
      $result=mysqli_query($link,"UPDATE users SET f_name='".$f_name."',s_name='".$s_name."',email='".$email."',phone='".$phone."',unit='".$unit."',position='".$position."',role='".$user_role."', password='".$password."' WHERE login='".$login."' LIMIT 1");
      if ($result=='true') {
        echo '</br><div style="text-align:center;"><div class="alert alert-success text-center" role="alert">Данные обновлены!</div></div>';
      }
      else {
        echo '</br><div class="alert alert-warning text-center" role="alert">Ошибка обновления данных! Просьба обратиться к разработчику.</div>'; 
      }
    }
  } else {
    $result=mysqli_query($link,"UPDATE users SET role='".$user_role."' WHERE login='".$login."' LIMIT 1");
    if ($result=='true') {
      echo '</br><div style="text-align:center;"><div class="alert alert-success text-center" role="alert">Данные обновлены!</div></div>';
    }
    else {
      echo '</br><div class="alert alert-warning text-center" role="alert">Ошибка обновления данных! Просьба обратиться к разработчику.</div>'; 
    }
  }


  
}

if(isset($_POST['submit_remove']))
{
    if (isset($_POST["login"])) {$login = clean($_POST["login"]);} else {exit();}
    if (isset($_POST["user_del_answer"])) {$user_del_answer = clean($_POST["user_del_answer"]);} else {exit();}


    if ($user_del_answer == 'yes') {
      $result=mysqli_query($link,"UPDATE USERS SET active='0',id_user='***',role='user',login='".$login.".' WHERE login='".$login."' LIMIT 1");
      if ($result=='true') {
        echo '</br><center><div class="alert alert-success text-center" role="alert">Пользователь '.$login.' удалён!</div>';
      }
      else {
        echo '</br><div class="alert alert-warning text-center" role="alert">Ошибка удаления данных! Просьба обратиться к разработчику.</div>';
      }
    } else {
      echo '</br><center><div class="alert alert-warning text-center" role="alert">Форма удаления для пользователя '.$login.' не подтверждена!</div>';
    }

}


// Переменная хранит число сообщений выводимых на станице
$num = 50;
// Извлекаем из URL текущую страницу
if(isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}
// Определяем общее число сообщений в базе данных
$result = mysqli_query($link,"SELECT COUNT(*) FROM users WHERE active=1");
$posts = mysqli_fetch_assoc($result);
//Присваиваем содержимое столбца COUNT(*) переменной
$posts = $posts['COUNT(*)'];
// Находим общее число страниц
$total = intval(($posts - 1) / $num) + 1;
// Определяем начало сообщений для текущей страницы
$page = intval($page);
// Если значение $page меньше единицы или отрицательно
// переходим на первую страницу
// А если слишком большое, то переходим на последнюю
if(empty($page) or $page < 0) $page = 1;
if($page > $total) $page = $total;
// Вычисляем начиная к какого номера
// следует выводить сообщения
$start = $page * $num - $num;


//А теперь делаем стрелки
//Проверяем нужны ли стрелки назад
if ($page != 1) {$pervpage = '<li class="page-item"><a class="page-link" href= ./status.php?page=1><<</a></li>
  <li class="page-item"><a class="page-link" href= ./status.php?page='. ($page - 1) .'><</a></li>';}
else
{$pervpage = '<li class="page-item disabled"><a class="page-link" href= ./status.php?page=1><<</a></li>
             <li class="page-item disabled"><a class="page-link" href= ./status.php?page='. ($page - 1) .'><</a></li>';}
// Проверяем нужны ли стрелки вперед
if ($page != $total) {$nextpage = ' <li class="page-item"><a class="page-link" href= ./status.php?page='. ($page + 1) .'>></a></li>
                <li class="page-item"><a class="page-link" href= ./status.php?page=' .$total. '>>></a></li>';}
else
{$nextpage = ' <li class="page-item disabled"><a class="page-link" href= ./status.php?page='. ($page + 1) .'>></a></li>
                  <li class="page-item disabled"><a class="page-link" href= ./status.php?page=' .$total. '>>></a></li>';}
$page2left = "";
$page1left = "";
$page2right = "";
$page1right = "";
// Находим две ближайшие страницы с обоих краев, если они есть
if($page - 2 > 0) $page2left = '<li class="page-item"><a class="page-link" href= ./status.php?page='. ($page - 2) .'>'. ($page - 2) .'</a></li>';
if($page - 1 > 0) $page1left = '<li class="page-item"><a class="page-link" href= ./status.php?page='. ($page - 1) .'>'. ($page - 1) .'</a></li>';
if($page + 2 <= $total) $page2right = '<li class="page-item"><a class="page-link" href= ./status.php?page='. ($page + 2) .'>'. ($page + 2) .'</a></li>';
if($page + 1 <= $total) $page1right = '<li class="page-item"><a class="page-link" href= ./status.php?page='. ($page + 1) .'>'. ($page + 1) .'</a></li>';
//Вывод меню
$hands = '&nbsp;'.$pervpage.$page2left.$page1left.'<li class="page-item active "><a class="page-link" href="#">'.$page.'</a></li>'.$page1right.$page2right.$nextpage;
?>

<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-md-6 py-3 text-center">
      <!-- Modal user add -->
      <div class="modal fade" id="Modal_user_add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Добавление нового локального пользователя</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="row justify-content-md-center">
                <div class="col col-md-5">
                  <label for="exampleInputPassword1">Логин</label>
                  <input type="login" name="login" class="form-control" required>
                </div>
                <div class="col col-md-5">
                  <label for="exampleInputPassword1">Пароль</label>
                  <input type="password" name="password" class="form-control" required>
                </div>
              </div>
              </br>
              <div class="row justify-content-md-center">
                <div class="col col-md-5">
                    <label for="exampleInputPassword1">Имя</label>
                    <input type="text" name="f_name" value="" class="form-control" required>
                </div>
                <div class="col col-md-5">
                    <label for="exampleInputPassword1">Фамилия</label>
                    <input type="text" name="s_name" value="" class="form-control" required>
                </div>
              </div>
              </br>
              <div class="row justify-content-md-center">
                <div class="col col-md-5">
                    <label for="exampleInputPassword1">E-mail</label>
                    <input type="email" name="email" value="" class="form-control" required>
                </div>
                <div class="col col-md-5">
                    <label for="exampleInputPassword1">Телефон</label>
                    <input type="text" name="phone" value="" class="form-control" required>
                </div>
              </div>
              </br>
              <div class="row justify-content-md-center">
              <div class="col col-md-5">
                    <label for="exampleInputPassword1">Подразделение</label>
                    <input type="text" name="unit" value="" class="form-control" required>
                </div>
                <div class="col col-md-5">
                    <label for="exampleInputPassword1">Должность</label>
                    <input type="text" name="position" value="" class="form-control" required>
                </div>
              </div>
              </br>
              <div class="row justify-content-md-center">
                <div class="col col-md-5">
                  <label>Роль</label>
                  <select class="form-select" name="user_role" required>
                    <option value="admin">Администратор</option>
                    <option value="superadmin">Суперадминистратор</option>
                    <option value="user" selected>Пользователь</option>
                    <option value="operator">Оператор</option>
                  </select>
                </div>
              </div>
              </br>
              <div style="text-align:center;">
                <div style="text-align:center;"><button name="submit_register" type="submit" class="btn btn-primary">Добавить</button></div>
              </div>
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Назад</button>
          </div>
        </div>
      </div>
      </div>
      <!-- Modal user add end -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal_user_add">Добавить локального пользователя</button>
    </div>  
  </div>
  </br>
	<div class="row justify-content-center">
	  <div class="col col-12 text-center">
	    <h4>Текущие пользователи</h4>
	  </div>
	</div>
  <div class="row justify-content-start">
    <div class="col col-md-4">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <?php echo $hands; ?>
        </ul>
      </nav>
    </div>
  </div>
	<div class="row justify-content-md-center">
		<div class="col col-md-12 py-3 border border-warning bg-light rounded-3">
			<table class="table table-hover table-bordered">
				<thead>
					<tr class="table-active text-center">
					<th scope="col">№</th>
					<th scope="col">Логин</th>
					<th scope="col">ФИО</th>
					<th scope="col">Роль</th>
          <th scope="col">Тип</th>
          <th scope="col">Управление</th>
					</tr>
				</thead>
					<?php
					$query = mysqli_query($link,"SELECT login,f_name,s_name,role,unit,position,email,phone,local,domain FROM users WHERE active=1 ORDER BY login ASC");
					$i = 1;
					while ($data = mysqli_fetch_assoc($query))
						{
              if ($data['role'] == 'admin') {
                $tek_role = 'Администратор';
                $cur_role = '
                <option value="admin" selected>'.$tek_role.'</option>
                <option value="superadmin">Суперадминистратор</option>
                <option value="user">Пользователь</option>
                <option value="operator">Оператор</option>
                ';
              } else if ($data['role'] == 'superadmin') {
                $tek_role = 'Суперадминистратор';
                $cur_role = '
                <option value="superadmin" selected>'.$tek_role.'</option>
                <option value="admin">Администратор</option>
                <option value="user">Пользователь</option>
                <option value="operator">Оператор</option>
                ';
              } else if ($data['role'] == 'user') {
                $tek_role = 'Пользователь';
                $cur_role = '
                <option value="user" selected>'.$tek_role.'</option>
                <option value="admin">Администратор</option>
                <option value="superadmin">Суперадминистратор</option>
                <option value="operator">Оператор</option>
                ';
              } else if ($data['role'] == 'operator') {
                $tek_role = 'Оператор';
                $cur_role = '
                <option value="operator" selected>'.$tek_role.'</option>
                <option value="superadmin">Суперадминистратор</option>
                <option value="admin">Администратор</option>
                <option value="user">Пользователь</option>
                ';
              } 

              if ($data['local'] == '1') { 
                $type = '<img src="/file/images/user.png" class="rounded" alt="local" style="width:25px;">';
                $changed = '';
              } else if ($data['domain'] == '1') { 
                $type = '<img src="/file/images/ldap.png" class="rounded" alt="domain" style="width:25px;">';
                $changed = 'disabled';
              } else {
                $type = '<img src="/file/images/questionmark.png" class="rounded" alt="none" style="width:25px;">';
                $changed = 'disabled';
              }

							$button_edit = '
                <!-- Modal user edit -->
                <div class="modal fade" id="Modal_edit_'.$i.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-centered modal-lg">
                   <div class="modal-content">
                     <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel">Редактирование пользователя '.$data['f_name'].' '.$data['s_name'].'</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                       <form action="" method="post">
                        <div class="row justify-content-md-center">
                          <div class="col col-md-5">
                              <label for="exampleInputPassword1">Имя</label>
                              <input type="text" name="f_name" value="'.$data['f_name'].'" class="form-control" required '.$changed.'>
                          </div>
                          <div class="col col-md-5">
                              <label for="exampleInputPassword1">Фамилия</label>
                              <input type="text" name="s_name" value="'.$data['s_name'].'" class="form-control" required '.$changed.'>
                          </div>
                        </div>
                        </br>
                        <div class="row justify-content-md-center">
                          <div class="col col-md-5">
                              <label for="exampleInputPassword1">E-mail</label>
                              <input type="email" name="email" value="'.$data['email'].'" class="form-control" required '.$changed.'>
                          </div>
                          <div class="col col-md-5">
                              <label for="exampleInputPassword1">Телефон</label>
                              <input type="text" name="phone" value="'.$data['phone'].'" class="form-control" required '.$changed.'>
                          </div>
                        </div>
                        </br>
                        <div class="row justify-content-md-center">
                          <div class="col col-md-5">
                              <label for="exampleInputPassword1">Подразделение</label>
                              <input type="text" name="unit" value="'.$data['unit'].'" class="form-control" required '.$changed.'>
                          </div>
                          <div class="col col-md-5">
                              <label for="exampleInputPassword1">Должность</label>
                              <input type="text" name="position" value="'.$data['position'].'" class="form-control" required '.$changed.'>
                          </div>
                        </div>
                        </br>
                        <div class="row justify-content-md-center">
                           <div class="col col-md-5">
                             <label>Роль</label>
                             <select class="form-select" name="user_role" required>
                               '.$cur_role .'
                             </select>
                           </div>
                           <div class="col col-md-5">
                             <label for="exampleInputPassword1">Пароль</label>
                             <input type="password" name="password" class="form-control" '.$changed.'>
                             <small id="passwordHelpBlock" class="form-text text-muted">
                              Оставить пустым при отсутствии изменений
                             </small>
                          </div>
                        </div>
                        </br>
                         <input type="hidden" name="login" value="'.$data['login'].'">
                         <div style="text-align:center;"><button name="submit_update" type="submit" class="btn btn-primary">Обновить информацию</button></div>
                       </form>
                     </div>
                     <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Назад</button>
                     </div>
                   </div>
                 </div>
                </div>
                <!-- Modal user edit end -->
                <!-- Modal user remove -->
                <div class="modal fade" id="Modal_remove_'.$i.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-centered modal-lg">
                   <div class="modal-content">
                     <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel">Удаление пользователя '.$data['login'].'</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                       <form action="" method="post">
                            <div class="row justify-content-md-center">
                                  <p class="text-center">Вы уверены?</p>
                            </div>
                            <div class="row justify-content-md-center">
                                  <div class="col col-md-2">
                                       <select name="user_del_answer" class="form-select" aria-label="Default select example">
                                         <option value="yes">Да</option>
                                         <option value="no" selected>Нет</option>
                                       </select>
                                  </div>
                            </div>
                         </br>
                         </br>
                         </br>
                         <input type="hidden" name="login" value="'.$data['login'].'">
                         <div style="text-align:center;"><button type="submit" name="submit_remove" class="btn btn-warning mb-2">Удалить</button></div>
                       </form>
                     </div>
                     <div class="modal-footer">
                       <button type="button" name="submit_delete" class="btn btn-secondary" data-bs-dismiss="modal">Назад</button>
                     </div>
                   </div>
                 </div>
                </div>
                <!-- Modal user remove end -->
							<div class="btn-group" role="group" aria-label="Basic mixed styles example">
             		<button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#Modal_edit_'.$i.'">Редактировать</button>
             	  <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#Modal_remove_'.$i.'">Удалить</button>
             	</div>';
							echo '
							<tr class="table-light text-center">
							 <td><div style="text-align:center;">'.$i.'</div></td>
							 <td>'.$data['login'].'</td>
							 <td>'.$data['f_name'].' '.$data['s_name'].'</td>
							 <td>'.$tek_role.'</td>
               <td>'.$type.'</td>
               <td>'.$button_edit.'</td>
							</tr>';
							$i++;
						}
					?>
			</table>
		</div>
	</div>
  <div class="row justify-content-start">
    <div class="col col-md-4">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <?php echo $hands; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>
<?php include("../../data/footer.php"); ?>
