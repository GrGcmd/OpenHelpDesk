<?php
require("../../data/header.php");
require("../../data/db.php");
require("../../data/session.php");
require("../../menu.php");
require("../../data/clean.php");

//Проверка на роль
if ($data['role']!='operator' and $data['role']!='admin' and $data['role']!='superadmin') {
  echo '<meta http-equiv="Refresh" content="0; url=/" />';
  exit();
}

if ((isset($_GET['search_individual'])) or (isset($_GET['bid_requests_for_attention_all'])) or (isset($_GET['bid_requests_for_assigned_to_me'])) or (isset($_GET['bid_search_status'])) or (isset($_GET['bid_search_filials'])) or (isset($_GET['bid_requests_for_assigned_to_me_all'])) or (isset($_GET['bid_search_contractor']))) {
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

  $type_search = '';

  if (isset($_GET['search_individual'])) {
    if (isset($_GET["text_search"])) {$text_search = clean($_GET['text_search']);} else {echo $knopka_error; goto end_load;}
    if (($text_search=='') or ($text_search==Null)) {
      echo '<div class="alert alert-warning text-center" role="alert">А что ищем? :-)</div></br>
      <div style="text-align:center;">
        <a href="/help/admin/"><button type="submit" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Вернуться назад
          </button>
        </a>
      </div>';
      goto end_load;
    }
    $result_page_count = mysqli_query($link,"SELECT COUNT(*) FROM bid WHERE id LIKE '%".$text_search."%' or theme LIKE '%".$text_search."%' or message LIKE '%".$text_search."%'");
    $type_search = '&text_search='.$text_search.'&search_individual';
  } 

  if (isset($_GET['bid_requests_for_attention_all'])) {
    $text_search='Требующие внимания';
    $result_page_count = mysqli_query($link,"SELECT COUNT(*) FROM bid WHERE status in (1,10)");
    $type_search = '&bid_requests_for_attention_all';
  } 

  if (isset($_GET['bid_requests_for_assigned_to_me'])) {
    $text_search='Назначенные мне';
    $query = mysqli_query($link, "SELECT id FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
    if(mysqli_num_rows($query) > 0){
      $user_id=$data['id'];
      $result_page_count = mysqli_query($link,"SELECT COUNT(*) FROM bid WHERE contractor='".$user_id."' and status not in (5)");
      $type_search = '&bid_requests_for_assigned_to_me';
    } else {
      $text_search='Нет назначенных заявок.';
      goto end_load;
    }
  }

  if (isset($_GET['bid_requests_for_assigned_to_me_all'])) {
    $text_search='Все мои заявки';
    $query = mysqli_query($link, "SELECT id FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
    if(mysqli_num_rows($query) > 0){
      $user_id=$data['id'];
      $result_page_count = mysqli_query($link,"SELECT COUNT(*) FROM bid WHERE contractor='".$user_id."'");
      $type_search = '&bid_requests_for_assigned_to_me_all';
    } else {
      $text_search='Нет моих заявок.';
      goto end_load;
    }

  }
  
  if (isset($_GET['bid_search_status'])) {
   if (isset($_GET["bid_search_status_name"])) {$bid_search_status_name = clean($_GET['bid_search_status_name']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
     $result_page_count = mysqli_query($link,"SELECT COUNT(*) FROM bid WHERE status='".$bid_search_status_name."'");
     $type_search = '&bid_search_status_name='.$bid_search_status_name.'&bid_search_status';
    } 

  if (isset($_GET['bid_search_filials'])) {
    if (isset($_GET["bid_search_filial_name"])) {$bid_search_filial_name = clean($_GET['bid_search_filial_name']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
      $result_page_count = mysqli_query($link,"SELECT COUNT(*) FROM bid WHERE filial='".$bid_search_filial_name."'");
      $type_search = '&bid_search_filial_name='.$bid_search_filial_name.'&bid_search_filials';
    } 

  if (isset($_GET['bid_search_contractor'])) {
    if (isset($_GET["bid_search_contractor_name"])) {$bid_search_contractor_name = clean($_GET['bid_search_contractor_name']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
      $result_page_count = mysqli_query($link,"SELECT COUNT(*) FROM bid WHERE contractor='".$bid_search_contractor_name."'");
      $type_search = '&bid_search_contractor_name='.$bid_search_contractor_name.'&bid_search_contractor';
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
  //$result_page_count = mysqli_query($link,"SELECT COUNT(*) FROM bid");
  $posts = mysqli_fetch_assoc($result_page_count);
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
  if ($page != 1) {$pervpage = '<li class="page-item"><a class="page-link" href= ./?page=1'.$type_search.'><<</a></li>
    <li class="page-item"><a class="page-link" href= ./?page='. ($page - 1) .$type_search.'><</a></li>';}
  else
  {$pervpage = '<li class="page-item disabled"><a class="page-link" href= ./?page=1'.$type_search.'><<</a></li>
                <li class="page-item disabled"><a class="page-link" href= ./?page='. ($page - 1) .$type_search.'><</a></li>';}
  // Проверяем нужны ли стрелки вперед
  if ($page != $total) {$nextpage = ' <li class="page-item"><a class="page-link" href= ./?page='. ($page + 1) .$type_search.'>></a></li>
                  <li class="page-item"><a class="page-link" href= ./?page=' .$total.$type_search.'>>></a></li>';}
  else
  {$nextpage = ' <li class="page-item disabled"><a class="page-link" href= ./?page='. ($page + 1) .$type_search.'>></a></li>
                 <li class="page-item disabled"><a class="page-link" href= ./?page=' .$total. $type_search.'>>></a></li>';}
  $page2left = "";
  $page1left = "";
  $page2right = "";
  $page1right = "";
  // Находим две ближайшие страницы с обоих краев, если они есть
  if($page - 2 > 0) $page2left = '<li class="page-item"><a class="page-link" href= ./?page='. ($page - 2) .$type_search.'>'. ($page - 2) .'</a></li>';
  if($page - 1 > 0) $page1left = '<li class="page-item"><a class="page-link" href= ./?page='. ($page - 1) .$type_search.'>'. ($page - 1) .'</a></li>';
  if($page + 2 <= $total) $page2right = '<li class="page-item"><a class="page-link" href= ./?page='. ($page + 2) .$type_search.'>'. ($page + 2) .'</a></li>';
  if($page + 1 <= $total) $page1right = '<li class="page-item"><a class="page-link" href= ./?page='. ($page + 1) .$type_search.'>'. ($page + 1) .'</a></li>';
  //Вывод меню
  $hands = '&nbsp;'.$pervpage.$page2left.$page1left.'<li class="page-item active "><a class="page-link" href="#">'.$page.'</a></li>'.$page1right.$page2right.$nextpage;


  if (isset($_GET['search_individual'])) {
    if (isset($_GET["text_search"])) {$text_search = clean($_GET['text_search']);} else {echo $knopka_error; goto end_load;}
    if (($text_search=='') or ($text_search==Null)) {
      echo '<div class="alert alert-warning text-center" role="alert">А что ищем? :-)</div></br>
      <center>
        <a href="/help/admin/"><button type="submit" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Вернуться назад
          </button>
        </a>
      </center>';
      goto end_load;
    }
    $query = mysqli_query($link,"SELECT id,priority,filial,theme,date,status FROM bid WHERE id LIKE '%".$text_search."%' or theme LIKE '%".$text_search."%' or message LIKE '%".$text_search."%' ORDER BY id DESC LIMIT ".$start.", ".$num."");
  } 

  if (isset($_GET['bid_requests_for_attention_all'])) {
    $text_search='Требующие внимания';
    $query = mysqli_query($link,"SELECT id,priority,filial,theme,date,status FROM bid WHERE status in (1,10) ORDER BY id DESC LIMIT ".$start.", ".$num."");
  } 

  if (isset($_GET['bid_requests_for_assigned_to_me'])) {
    $text_search='Назначенные мне';

    $query = mysqli_query($link, "SELECT id FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
    if(mysqli_num_rows($query) > 0){
      $user_id=$data['id'];
      $query = mysqli_query($link,"SELECT id,priority,priority,filial,theme,date,status FROM bid WHERE contractor='".$user_id."' and status not in (5) ORDER BY id DESC LIMIT ".$start.", ".$num."");
    } else {
      $text_search='Нет назначенных заявок.';
      goto end_load;
    }
  }

  if (isset($_GET['bid_requests_for_assigned_to_me_all'])) {
    $text_search='Все мои заявки';

    $query = mysqli_query($link, "SELECT id FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
    if(mysqli_num_rows($query) > 0){
      $user_id=$data['id'];
      $query = mysqli_query($link,"SELECT id,priority,priority,filial,theme,date,status FROM bid WHERE contractor='".$user_id."' ORDER BY id DESC LIMIT ".$start.", ".$num."");
     } else {
      $text_search='Нет моих заявок.';
      goto end_load;
    }

  }
  
  if (isset($_GET['bid_search_status'])) {
   if (isset($_GET["bid_search_status_name"])) {$bid_search_status_name = clean($_GET['bid_search_status_name']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    
    $text_search='По статусу заявки';
    
    $query = mysqli_query($link,"SELECT id,priority,filial,theme,date,status FROM bid WHERE status='".$bid_search_status_name."' ORDER BY id DESC LIMIT ".$start.", ".$num."");  
  } 

  if (isset($_GET['bid_search_filials'])) {
    if (isset($_GET["bid_search_filial_name"])) {$bid_search_filial_name = clean($_GET['bid_search_filial_name']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
     
     $text_search='По филиалу';
     
     $query = mysqli_query($link,"SELECT id,priority,filial,theme,date,status FROM bid WHERE filial='".$bid_search_filial_name."' ORDER BY id DESC LIMIT ".$start.", ".$num."");  
    } 

  if (isset($_GET['bid_search_contractor'])) {
    if (isset($_GET["bid_search_contractor_name"])) {$bid_search_contractor_name = clean($_GET['bid_search_contractor_name']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    
    $text_search='По исполнителю';
    
    $query = mysqli_query($link,"SELECT id,priority,filial,theme,date,status FROM bid WHERE contractor='".$bid_search_contractor_name."' ORDER BY id DESC LIMIT ".$start.", ".$num."");  
  } 

    echo '
    <div class="container">
      <center><h4>Результат по запросу: '.$text_search.'</h3></center>
      <center>
        <a href="/help/admin/"><button type="submit" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Вернуться назад
          </button>
        </a>
      </center></br>
      <div class="row justify-content-start">
        <div class="col col-md-4">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              '.$hands.'
            </ul>
          </nav>
        </div>
      </div>
      <div class="row justify-content-start">
        <div class="col col-md-12">
          <table class="table text-center table-hover table-bordered table-light bg-light" border="1">
            <thead>
              <tr class="table-active">
                <th scope="col">ID заявки</th>
                <th scope="col">Филиал</th>
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

                //Вытащим филиал
                $query_filial = mysqli_query($link,"SELECT name FROM bid_filials WHERE id='".$data['filial']."'");
                $data_filial = mysqli_fetch_assoc($query_filial);

                
                 Printf('
                 <tr>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%s</td>
                  <td>%s</td>
                  <td><a href="/help/admin/status.php?page=%s&bid=%s&details&search" target="_blank"><button type="button" name="details" class="btn btn-outline-primary">Подробнее</button></a></td>
                 </tr>
                 ',$data['id'],$data_filial['name'],mb_strimwidth($data['theme'], 0, 60, "..."),$bid_date,$priority,$status_color,1,$data['id']);
                 $i++;
               }
          echo '
          </tbody>
        </table>
      </div>
      <div class="row justify-content-start">
        <div class="col col-md-4">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              '.$hands.'
            </ul>
          </nav>
        </div>
      </div>
    </div>';
    goto end_load;
}

//Посчитаем заявки
$query = mysqli_query($link, "SELECT id,status FROM bid");
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
 //Вытащим филиалы
 $filials = '';
 $query_filials = mysqli_query($link,"SELECT id,name FROM bid_filials ORDER BY name ASC");
 while ($data_filials = mysqli_fetch_assoc($query_filials)) {
   if ($data_filials['id']!='0') {
    $filials = $filials.'<option value="'.$data_filials['id'].'">'.$data_filials['name'].'</option>';
   }
   
 }
 //Вытащим статусы
 $status = '';
 $query_status = mysqli_query($link,"SELECT id,status FROM bid_status ORDER BY status ASC");
 while ($data_status = mysqli_fetch_assoc($query_status)) {
   $status = $status.'<option value="'.$data_status['id'].'">'.$data_status['status'].'</option>';
 }
 //Вытащим всех исполнителей
 $contractor = '';
 $query_contractor = mysqli_query($link,"SELECT id,f_name,s_name FROM users WHERE role in ('operator','admin','superadmin') and active=1 ORDER BY s_name ASC");
 while ($data_contractor = mysqli_fetch_assoc($query_contractor)) {
   $contractor = $contractor.'<option value="'.$data_contractor['id'].'">'.$data_contractor['f_name'].' '.$data_contractor['s_name'].'</option>';
 }
?>
<div class="container">
  <h4 style="text-align:center;">Главная панель оператора</h4>
  <div class="row justify-content-md-center">
    <div class="col col-lg-6 py-3 border border-success bg-light rounded-2">
      <div class="row justify-content-md-center">
        <div class="col col-lg-10">
          <div class="card-body text-center">
            <h5 style="text-align:center;">Быстрый поиск</h5>
            <form class="form-inline row g-3" action="" method="get">
                <input type="text" class="form-control" name="text_search" placeholder="Введите запрос" required>
                <small id="passwordHelpBlock" class="form-text text-muted">Поиск осуществляется по уникальному ID, теме или телу заявки.</small>
                <input type="hidden" name="page" value="1">
                <div class="d-grid gap-2 col-6 mx-auto"><button type="submit" name="search_individual" class="btn btn-success mb-2" >Найти</button></div>
            </form>
          </div>
        </div>
      </div>
      </br>
      <div class="row justify-content-md-center">
        <div class="col col-lg-4">
          <div class="card-body text-center">
            <h5 style="text-align:center;">По филиалу</h5>
            <form class="form-inline row g-3" action="" method="get">
                <select name="bid_search_filial_name" class="form-select" aria-label="Default select example">
                  <?php echo  $filials; ?>
                </select>
                <input type="hidden" name="page" value="1">
                <div class="d-grid gap-2 col-6 mx-auto"><button type="submit" name="bid_search_filials" class="btn btn-outline-success mb-2" >Найти</button></div>
            </form>
          </div>
        </div>
        <div class="col col-lg-4">
          <div class="card-body text-center">
            <h5 style="text-align:center;">По статусу</h5>
            <form class="form-inline row g-3" action="" method="get">
                <select name="bid_search_status_name" class="form-select" aria-label="Default select example">
                  <?php echo  $status; ?>
                </select>
                <input type="hidden" name="page" value="1">
                <div class="d-grid gap-2 col-6 mx-auto"><button type="submit" name="bid_search_status" class="btn btn-outline-success mb-2" >Найти</button></div>
            </form>
          </div>
        </div>
        <div class="col col-lg-4">
          <div class="card-body text-center">
            <h5 style="text-align:center;">По исполнителю</h5>
            <form class="form-inline row g-3" action="" method="get">
                <select name="bid_search_contractor_name" class="form-select" aria-label="Default select example">
                  <?php echo  $contractor; ?>
                </select>
                <input type="hidden" name="page" value="1">
                <div class="d-grid gap-2 col-6 mx-auto"><button type="submit" name="bid_search_contractor" class="btn btn-outline-success mb-2" >Найти</button></div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="col col-lg-3 py-3 border border-success bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Заявки, требующие внимания или распределения</h5>
        <?php printf('</br><h4>Всего: %s</h4></br></br>',$bid_1+$bid_10); ?>
        <form class="form-inline row g-3" action="" method="get">
          <input type="hidden" name="page" value="1">
          <div class="d-grid gap-2 col-6 mx-auto"><button type="submit" name="bid_requests_for_attention_all" class="btn btn-warning">Подробнее</button></div>
        </form>
      </div>
    </div>
    <div class="col col-lg-3 py-3 border border-success bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Статистика</h5>
        <?php printf('</br>Всего заявок: %s</br> Новых: %s</br> Назначены: %s</br> В работе: %s</br> Ожидает подтверждения: %s</br> Запрос информации: %s</br> Приостановлена: %s</br> На согласовании: %s</br> В закупке: %s</br> Возвращены в работу: %s</br> Закрыты: %s</br>',$i,$bid_1,$bid_2,$bid_3,$bid_4,$bid_6,$bid_7,$bid_8,$bid_9,$bid_10,$bid_5,); ?>
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
    <div class="col col-lg-2 py-1 border border-warning bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Запрос информации</h5>
        <?php printf('</br><h4>%s</h4></br></br>',$bid_6); ?>
      </div>
    </div>
    <div class="col col-lg-2 py-1 border border-secondary bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Ожидает подтверждения</h5>
        <?php printf('</br><h4>%s</h4></br></br>',$bid_4); ?>
      </div>
    </div>
    <div class="col col-lg-2 py-1 border border-success bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">На согласовании</h5>
        <?php printf('</br><h4>%s</h4></br></br>',$bid_8); ?>
      </div>
    </div>
    <div class="col col-lg-2 py-1 border border-black bg-light rounded-2">
      <div class="card-body text-center">
        <h5 style="text-align:center;">Приостановлены</h5>
        <?php printf('</br><h4>%s</h4></br></br>',$bid_7); ?>
      </div>
    </div>
  </div>
</div>
</br>

<?php
end_load:
require("../../data/footer.php");
?>
