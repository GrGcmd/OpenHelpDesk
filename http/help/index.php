<?php
require("../data/header.php");
require("../data/db.php");
require("../data/session.php");

//Статсу обслуживания
$query = mysqli_query($link, "SELECT status_int FROM bid_system WHERE id=1 LIMIT 1");
$data = mysqli_fetch_assoc($query);

if ($data['status_int']==1) {echo '<meta http-equiv="Refresh" content="0; url=/help/maintenance.php" />';}
require("../menu.php");
require("../data/clean.php");

   
if ((isset($_POST['search_individual'])) or (isset($_POST['bid_info_otvet']))) {
  $knopka_error = '
  <div class="alert alert-danger text-center" role="alert">Что то пошло не так. Просьба обратиться к разработчику.</div></br>
  <div style="text-align:center;">
    <a href="/help/"><button type="submit" class="btn btn-warning">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
      </svg>
      Вернуться назад
      </button>
    </a>
  </div>';
  $query = mysqli_query($link, "SELECT id FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
     if(mysqli_num_rows($query) > 0){
       $data = mysqli_fetch_assoc($query);
       $user_name=$data['id'];
     } else {echo $knopka_error; goto end_load;}

  if (isset($_POST['search_individual'])) {
    if (isset($_POST["text_search"])) {$text_search = clean($_POST['text_search']);} else {echo $knopka_error; goto end_load;}
      if (($text_search=='') or ($text_search==Null)) {
        echo '<div class="alert alert-warning text-center" role="alert">А что ищем? :-)</div></br>
        <div style="text-align:center;">
          <a href="/help/"><button type="submit" class="btn btn-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
            </svg>
            Вернуться назад
            </button>
          </a>
        </div>';
        goto end_load;
      } else {
        $query = mysqli_query($link,"SELECT id,priority,theme,date,status FROM bid WHERE owner='".$user_name."' and (id LIKE '%".$text_search."%' or theme LIKE '%".$text_search."%' or message LIKE '%".$text_search."%') ORDER BY date DESC LIMIT 200");
      }
  } else if (isset($_POST['bid_info_otvet'])) {
    $text_search='Требующие внимания';
    $query = mysqli_query($link,"SELECT id,priority,theme,date,status FROM bid WHERE owner='".$user_name."' and status in (4,6) ORDER BY id DESC LIMIT 200");
  } else {
    echo $knopka_error; goto end_load;
  }
    echo '
    <div class="container">
      <h4 style="text-align:center;">Результат по запросу: '.$text_search.'</h3>
      <div style="text-align:center;">
        <a href="/help/"><button type="submit" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Вернуться назад
          </button>
        </a>
      </div></br>
      <div class="row justify-content-start">
        <div class="col col-md-12">
          <table class="table text-center table-hover table-bordered table-light bg-light" border="1">
            <thead>
              <tr class="table-active">
                <th scope="col">ID заявки</th>
                <th scope="col">Тема</th>
                <th scope="col">Дата</th>
                <th scope="col">Приоритет</th>
                <th scope="col">Статус</th>
            	  <th scope="col">Функции</th>
              </tr>
            </thead>
            <tbody>';
              $i = 1;
              while ($data = mysqli_fetch_assoc($query))
               {
                 //Поработаем с приоритетом
                  if ($data['priority']==1) {
                    $priority = '<img src="/file/images/priority-1.png" width="30px" title="Чрезвычайный приоритет">';
                  } else if ($data['priority']==2) {
                    $priority = '<img src="/file/images/priority-2.png" width="30px" title="Высокий приоритет">';
                  } else if ($data['priority']==3) {
                    $priority = '<img src="/file/images/priority-3.png" width="30px" title="Средний приоритет">';
                  } else if ($data['priority']==4) {
                    $priority = '<img src="/file/images/priority-4.png" width="30px" title="Низкий приоритет">';
                  } else {
                    $priority = '';
                  }
                 //Поработаем с датой
                 $bid_date = date('d-m-Y', $data['date']);
                 //Обрежим текст
                 $theme=mb_strimwidth($data['theme'], 0, 30, "...");
                 //Работаем со статусом заявки
                 $status_color='';
                 if ($data['status']==1) {
                  $status_color='<div class="alert p-1 mb-1 custom-color-blue" role="alert">Новая заявка</div>';
                  $status_text='Новая заявка';
                  $status_edit_menu='
                    <option value="5" selected>Отменить</option>
                  ';
                } else if ($data['status']==2){
                  $status_color='<div class="alert p-1 mb-1 custom-color-white-blue" role="alert">Назначена</div>';
                  $status_text='Назначена';
                  $status_edit_menu='
                    <option value="5" selected>Отменить</option>
                  ';
                } else if ($data['status']==3){
                  $status_color='<div class="alert p-1 mb-1 custom-color-green text-black" role="alert">В работе</div>';
                  $status_text='В работе';
                  $status_edit_menu='
                    <option value="5" selected>Отменить</option>
                  ';
                } else if ($data['status']==4){
                  $status_color='<div class="alert p-1 mb-1 custom-color-green-teal text-white" role="alert">Ожидает подтверждения</div>';
                  $status_text='Ожидает подтверждения';
                  $status_edit_menu='
                    <option value="2">Вернуть в работу</option>
                    <option value="5" selected>Подтвердить</option>
                  ';
                } else if ($data['status']==5){
                  $status_color='<div class="alert p-1 mb-1 custom-color-white-gray text-black" role="alert">Закрыта</div>';
                  $status_text='Закрыта';
                  $status_edit_menu='
                  <option value="2" selected>Вернуть в работу</option>
                ';
                } else if ($data['status']==6){
                  $status_color='<div class="alert p-1 mb-1 bg-warning text-black" role="alert">Запрос информации</div>';
                  $status_text='Запрос информации';
                  $status_edit_menu='
                    <option value="2" selected>Вернуть в работу</option>
                    <option value="5">Отменить</option>
                  ';
                } else if ($data['status']==7){
                  $status_color='<div class="alert p-1 mb-1  custom-color-gray text-black" role="alert">Приостановлена</div>';
                  $status_text='Приостановлена';
                  $status_edit_menu='
                  <option value="5" selected>Отменить</option>
                  <option value="2">Вернуть в работу</option>
                  ';
                } else if ($data['status']==8){
                  $status_color='<div class="alert p-1 mb-1  custom-color-pink text-white" role="alert">На согласовании</div>';
                  $status_text='На согласовании';
                  $status_edit_menu='
                    <option value="5" selected>Отменить</option>
                  ';
                } else if ($data['status']==9){
                  $status_color='<div class="alert p-1 mb-1  custom-color-orange-700 text-white" role="alert">В закупке</div>';
                  $status_text='В закупке';
                  $status_edit_menu='
                    <option value="5" selected>Отменить</option>
                  ';
                } else if ($data['status']==10){
                  $status_color='<div class="alert p-1 mb-1  custom-color-red-700 text-white" role="alert">Возвращена</div>';
                  $status_text='Возвращена';
                  $status_edit_menu='
                    <option value="5" selected>Отменить</option>
                  ';
                }
                 Printf('
                 <tr>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%s</td>
                  <td><a href="/help/status.php?page=%s&bid=%s&details&search" target="_blank"><button type="button" name="details" class="btn btn-outline-primary">Подробнее</button></a></td>
                 </tr>
                 ',$data['id'],mb_strimwidth($data['theme'], 0, 60, "..."),$bid_date,$priority,$status_color,1,$data['id']);
                 $i++;
               }
          echo '
          </tbody>
        </table>
      </div>
    </div>';
    goto end_load;
}



//Посчитаем заявки
$query = mysqli_query($link, "SELECT id,status FROM bid WHERE owner in (SELECT id from users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."')");
$i = 0;
$i = 0;
$bid_1=0;
$bid_2=0;
$bid_3=0;
$bid_4=0;
$bid_5=0;
$bid_6=0;
$bid_7=0;
$bid_8=0;
$bid_9=0;
$bid_10=0;
while ($data = mysqli_fetch_assoc($query)) {
  if ($data['status']==1) {
    $bid_1+=1;
  } else if ($data['status']==2) {
    $bid_2+=1;
  } else if ($data['status']==3){
    $bid_3+=1;
  } else if ($data['status']==4) {
    $bid_4+=1;
  } else if ($data['status']==5) {
    $bid_5+=1;
  } else if ($data['status']==6) {
    $bid_6+=1;
  } else if ($data['status']==7) {
    $bid_7+=1;
  } else if ($data['status']==8) {
    $bid_8+=1;
  } else if ($data['status']==9) {
    $bid_9+=1;
  } else if ($data['status']==10) {
    $bid_10+=1;
  } 
  $i+=1;
 }
?>
<div class="container">
  <h4 style="text-align:center;">Главная мои заявки</h4>
  <div class="row justify-content-md-center">
    <div class="col col-lg-6 py-3 border border-success bg-light rounded-2">
      <div class="row justify-content-md-center">
        <div class="col col-lg-10">
          <div class="card-body text-center">
            <h5 style="text-align:center;">Быстрый поиск</h5>
            </br>
            <form class="form-inline row g-3" action="" method="post">
                <input type="text" class="form-control" name="text_search" placeholder="Введите запрос" required>
                <small id="passwordHelpBlock" class="form-text text-muted">Поиск осуществляется по уникальному ID, теме или телу заявки.</small>
                <div class="d-grid gap-2 col-6 mx-auto"><button type="submit" name="search_individual" class="btn btn-success mb-2" >Найти</button></div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col col-lg-3 py-3 border border-success bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Заявки, требующие вашего внимания</h5>
        <?php printf('</br><h4>Всего: %s</h4></br></br>',$bid_4+$bid_6); ?>
        <form class="form-inline row g-3" action="" method="post">
          <div class="d-grid gap-2 col-6 mx-auto"><button type="submit" name="bid_info_otvet" class="btn btn-warning">Подробнее</button></div>
        </form>
      </div>
    </div>
    <div class="col col-lg-3 py-3 border border-success bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Моя статистика</h5>
        <?php printf('</br>Всего заявок: %s</br> Новых: %s</br> Назначены: %s</br> В работе: %s</br> Ожидает подтверждения: %s</br> Запрос информации: %s</br> Приостановлена: %s</br> На согласовании: %s</br> В закупке: %s</br> Возвращены в работу: %s</br> Закрыты: %s</br>',$i,$bid_1,$bid_2,$bid_3,$bid_4,$bid_6,$bid_7,$bid_8,$bid_9,$bid_10,$bid_5); ?>
      </div>
    </div>
  </div>
  </br>
  <div class="row justify-content-md-center">
    <div class="col col-lg-2 py-1 border border-info bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Новые заявки</h5>
        <?php printf('</br><h4>%s</h4></br></br>',$bid_1); ?>
      </div>
    </div>
    <div class="col col-lg-2 py-1 border border-success bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">В работе</h5>
        <?php printf('</br><h4>%s</h4></br></br>',$bid_2+$bid_3); ?>
      </div>
    </div>
    <div class="col col-lg-3 py-1 border border-warning bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Запрос информации</h5>
        <?php printf('</br><h4>%s</h4></br></br>',$bid_6); ?>
      </div>
    </div>
    <div class="col col-lg-3 py-1 border border-success bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Ожидающие подтверждения</h5>
        <?php printf('</br><h4>%s</h4></br></br>',$bid_4); ?>
      </div>
    </div>
    <div class="col col-lg-2 py-1 border border-success bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Закрыты</h5>
        <?php printf('</br><h4>%s</h4></br></br>',$bid_5); ?>
      </div>
    </div>
  </div>
  </br>
  <div class="row justify-content-md-center">
      <div class="col col-lg-12 py-3 border border-primary bg-light rounded-2">
        <h5 style="text-align:center;">Инструкция</h5>
        </br>
        <p class="text-center">Для создания новой заявки нажмите на пункт меню «Создать заявку». Далее выберите шаблон заявки и заполните предложенные поля. После окончания процедуры заполнения нажмите на кнопку «Продолжить». Заявка будет создана с уникальным номером. После создания заявки, информационное уведомление поступает исполнителю автоматически. </p>
        <p class="text-center">Отслеживать исполнение созданных заявок можно через пункт меню «Мои созданные заявки» или на главной странице.</p>
      </div>
  </div>
</div>
</br>

<?php
end_load:
require("../data/footer.php");
?>
