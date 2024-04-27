<?php
require("../data/header.php");
require("../data/db.php");

//Статсу обслуживания
$query = mysqli_query($link, "SELECT status_int FROM bid_system WHERE id=1 LIMIT 1");
$data = mysqli_fetch_assoc($query);

if ($data['status_int']==1) {echo '<meta http-equiv="Refresh" content="0; url=/help/maintenance.php" />';}


require("../data/session.php");
require("../menu.php");
require("../data/clean.php");


if(isset($_POST['edit_bid_status'])){
  //Проверим данные из формы
  if (isset($_POST["page"])) {$page = clean($_POST['page']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["bid"])) {$bid = clean($_POST['bid']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["bid_status_edit"])) {$bid_status_edit = clean($_POST['bid_status_edit']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

  $result=mysqli_query($link,"UPDATE bid SET status='.$bid_status_edit.',alarm_update=1 WHERE id='".$bid."' LIMIT 1");
  if ($result=='true') {
    if (isset($_POST['search'])) {
      echo '<div class="alert alert-success text-center" role="alert">Статус заявки обновлён.</div></br>
      <div style="text-align:center;">
        <a href="/help/status.php?page='.$page.'&bid='.$bid.'&details&search">
          <button type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
            </svg>
              Вернуться к заявке
          </button>
        </a>
      </div> 
      <meta http-equiv="refresh" content="3;url=/help/status.php?page='.$page.'&bid='.$bid.'&details&search">';
    } else {
      echo '<div class="alert alert-success text-center" role="alert">Статус заявки обновлён.</div></br>
      <div style="text-align:center;">
        <a href="/help/status.php?page='.$page.'&bid='.$bid.'&details">
          <button type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
            </svg>
              Вернуться к заявке
          </button>
        </a>
      </div> 
      <meta http-equiv="refresh" content="3;url=/help/status.php?page='.$page.'&bid='.$bid.'&details">';
    }
    
    $query = mysqli_query($link, "SELECT count(id) AS max_count FROM bid_history LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    $id_history = $data['max_count'] + 1;

    $query = mysqli_query($link, "SELECT id FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    $id_user=$data['id'];
    
    $result=mysqli_query($link,"INSERT INTO bid_history (id,id_bid,status,date,user) 
    VALUES ('$id_history','$bid','$bid_status_edit','$tek_date',$id_user)");
    goto end_load;
  }
  else {
    echo '<div class="alert alert-danger text-center" role="alert">Ошибка обновления статуса заявки. Что то пошло не так.</div></br>
    <div style="text-align:center;">
      <a href="/help/status.php?page='.$page.'&bid='.$bid.'&details">
        <button type="submit" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Вернуться назад
        </button>
      </a>
    </div>';
    goto end_load;
  }
}


if(isset($_POST['send_message'])){
  $knopka_error = '
  <div class="alert alert-danger text-center" role="alert">Что то пошло не так. Просьба обратиться к разработчику.</div></br>
  <div style="text-align:center;">
    <a href="/help/status.php?page=1"><button type="submit" class="btn btn-warning">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
      </svg>
      Вернуться назад
      </button>
    </a>
  </div>';
  //Проверим данные из формы
  if (isset($_POST["page"])) {$page = clean($_POST['page']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["bid"])) {$bid = clean($_POST['bid']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["text_message"])) { if ($_POST['text_message'] == '' or $_POST['text_message'] == Null) {$text_message = ' ';} else {$text_message = clean($_POST['text_message']);$text_message=mb_strimwidth($text_message, 0, 8000, "...");}} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

  
  //Если есть файл, тогда обработаем его.
  //print_r($_FILES);
  if ($_FILES["userfile"]['size']>0){
    //Проверка размера загруженного файла
    if ($_FILES['userfile']['size']>$file_size_max) {
      echo '<div class="alert alert-warning text-center" role="alert">Сообщение не создано. Превышен размер файла. <b>Максимальный размер 10 мегабайт.</b></div>
      <div style="text-align:center;">
        <a href="/help/status.php?page='.$page.'&bid='.$bid.'&details"><button type="submit" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Назад
          </button>
        </a>
      </div>';
      goto end_load;
    }
    //Проверка расширения файла
    //<input name="userfile" class="form-control" type="file" id="formFile" accept=".jpg,.jpeg,.png,.bmp">
    // $extension = substr($_FILES["userfile"]["name"], strrpos($_FILES["userfile"]["name"], '.') + 1);
    // if(in_array($extension, array("jpg","JPG","jpeg","JPEG", "png","PNG", "bmp", "BMP")) == False){
    //   echo '<div class="alert alert-warning text-center" role="alert">Сообщение не создано. Прикреплять можно только изображения.</b></div>
    //   <div style="text-align:center;">
    //     <a href="/help/status.php?page='.$page.'&bid='.$bid.'&details"><button type="submit" class="btn btn-warning">
    //       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
    //       <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
    //       </svg>
    //       Назад
    //       </button>
    //     </a>
    //   </div>';
    //   goto end_load;
    //}

    $file_type = $_FILES['userfile']['type'];
    $file_description = clean($_FILES['userfile']['name']);
    $extension = clean(substr($_FILES["userfile"]["name"], strrpos($_FILES["userfile"]["name"], '.') + 1));
    //Генерация случайного имени файла
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $file_name = 'file_'.substr(str_shuffle($permitted_chars), 0, 50).'.'.$extension;

    //Работа с файлом
    $uploadfile = $uploaddir_bid_files.$file_name;
    move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);

    $flagfile = TRUE;
  } else {
    $flagfile = FALSE;
  }
  
  $query = mysqli_query($link, "SELECT count(id) AS max_count_bid_message FROM bid_messages LIMIT 1");
  $data = mysqli_fetch_assoc($query);
  $id_bid_message = $data['max_count_bid_message'] + 1;

  if ($flagfile == TRUE) {
    $file_exist = 1;
    $query = mysqli_query($link, "SELECT count(id) AS max_count_bid_file FROM bid_files LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    $id_bid_file = $data['max_count_bid_file'] + 1;
  } else {
    $file_exist = 0;
  }

  $query = mysqli_query($link, "SELECT login FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
  if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
    $user_name=$data['login'];
  } else {$user_name = '***';}
  
  $result=mysqli_query($link,"INSERT INTO bid_messages (id,date,bid_number,user,file,message)
  VALUES ('$id_bid_message','$tek_date','$bid','$user_name','$file_exist','$text_message')");
  if ($result=='true') {
    echo '<div class="alert alert-success text-center" role="alert">Сообщение добавлено</div></br>
    <div style="text-align:center;">
      <a href="/help/status.php?page='.$page.'&bid='.$bid.'&details">
        <button type="submit" class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
          </svg>
            Вернуться к заявке
        </button>
      </a>
    </div>
    <meta http-equiv="refresh" content="2;url=/help/status.php?page='.$page.'&bid='.$bid.'&details">';
    if ($flagfile == TRUE) {
      $result=mysqli_query($link,"INSERT INTO bid_files (id,id_bid,date,message,logo,type,name,description)
      VALUES ('$id_bid_file','$bid','$tek_date','$id_bid_message',0,'$file_type','$file_name','$file_description')");
    }
    goto end_load;
  }
  else {
    echo '<div class="alert alert-danger text-center" role="alert">Сообщение не добавлено. Что то пошло не так.</div></br>
    <div style="text-align:center;">
      <a href="/help/status.php?page='.$page.'&bid='.$bid.'&details">
        <button type="submit" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Вернуться назад
        </button>
      </a>
    </div>';
    goto end_load;
  }
}

if(isset($_GET['details'])){
  $knopka_error = '
  <div class="alert alert-danger text-center" role="alert">Что то пошло не так. Возможно у вас нет доступа к просмотру.</div></br>
  <div style="text-align:center;">
    <a href="/help/"><button type="submit" class="btn btn-warning">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
      </svg>
      Вернуться назад
      </button>
    </a>
  </div>';
  //Проверим данные из формы
  if (isset($_GET["page"])) {$page = clean($_GET['page']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_GET["bid"])) {$bid = clean($_GET['bid']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

  $query = mysqli_query($link, "SELECT id,f_name,s_name,unit,position,phone,email FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
  if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
    $user_id=$data['id'];
    $f_name=$data['f_name'];
    $s_name=$data['s_name'];
    $unit=$data['unit'];
    $position=$data['position'];
    $phone=$data['phone'];
    $email=$data['email'];
  } else {echo $knopka_error; goto end_load;}

  $query = mysqli_query($link,"SELECT id,priority,template,filial,theme,status,owner,contractor,date,message FROM bid WHERE id='".$bid."' and owner='".$user_id."' LIMIT 1");
  if (mysqli_num_rows($query) > 0) {
      $data = mysqli_fetch_assoc($query);

      //Найдём исполнителья
      $query_contractor = mysqli_query($link,"SELECT f_name,s_name,position FROM users WHERE id='".$data['contractor']."' LIMIT 1");
      $data_contractor = mysqli_fetch_assoc($query_contractor);
      if ($data_contractor>1){
        $contractor_info = $data_contractor['f_name'].' '.$data_contractor['s_name'];
      } else {
        $contractor_info = 'Не назначен';
      }
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
      //Подформатируем дату
      $bid_date = date('d-m-Y  h:i A', $data['date']);
      $status_color='';
      $status_text='';
      $status_edit_menu ='';
      //Работаем со статусом заявки
      if ($data['status']==1) {
        $status_color='<div class="alert p-1 mb-1 custom-color-white-blue" role="alert">Новая заявка</div>';
        $status_text='Новая заявка';
        $status_edit_menu='
          <option value="5" selected>Отменить</option>
        ';
      } else if ($data['status']==2){
        $status_color='<div class="alert p-1 mb-1 custom-color-blue" role="alert">Назначена</div>';
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
          <option value="5" selected>Закрыть</option>
        ';
      } else if ($data['status']==5){
        $status_color='<div class="alert p-1 mb-1 custom-color-white-gray text-black" role="alert">Закрыта</div>';
        $status_text='Закрыта';
        $status_edit_menu='
        <option value="10" selected>Вернуть в работу</option>
      ';
      } else if ($data['status']==6){
        $status_color='<div class="alert p-1 mb-1 bg-warning text-black" role="alert">Запрос информации</div>';
        $status_text='Запрос информации';
        $status_edit_menu='
          <option value="3" selected>Вернуть в работу</option>
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

    } else {
      echo $knopka_error; goto end_load;
    }
    //Поработаем с шаблоном заявки
    $query = mysqli_query($link, "SELECT name FROM bid_tempates WHERE id='".mysqli_real_escape_string($link, $data['template'])."' LIMIT 1");
    if(mysqli_num_rows($query) > 0){
      $data_bid_template = mysqli_fetch_assoc($query);
      $template = $data_bid_template['name'];
    } else {echo $knopka_error; goto end_load;}
    
    //Поработаем с файлом лого, если есть.
    $query = mysqli_query($link,"SELECT id,type,name,description FROM bid_files WHERE id_bid='".$bid."' and logo=1 LIMIT 1");
    if (mysqli_num_rows($query) > 0) {
      $data_file = mysqli_fetch_assoc($query);
      $type = substr($data_file['type'], strrpos($data_file['type'], '/') + 1);
      if ($type == 'png' or $type == 'jpeg' or $type == 'jpg') {
        $file='<div class="text-center py-3 image__wrapper"><img src="/file/uploads/bids/'.$data_file['name'].'" class="rounded minimized" style="width: 50%; height: auto;" alt="Изображение"></div>';
      } else {
        $file='<div style="text-align:center;" class="py-3"><a href="'.$uploaddir_bid_files_short.$data_file['name'].'" download="'.$data_file['description'].'">'.$data_file['description'].'</a></div>';
      }
    } else {
      $file="";
    }
    //Вытащим сообщения и файлы к ним
    $query = mysqli_query($link,"SELECT id,date,user,file,message FROM bid_messages WHERE bid_number='".$bid."' ORDER BY id ASC");
    $j=0;
    $message='
    <div class="card border-light mb-3">
      <div class="card-header">Комментарии:</div>
      <div class="card-body">
      ';
    while ($data_messages = mysqli_fetch_assoc($query)){
      $message_date = date('в h:i A от d-m-Y', $data_messages['date']);
      if ($data_messages['file'] == 1) {
        $query_file = mysqli_query($link,"SELECT id,type,name,description FROM bid_files WHERE id_bid='".$bid."' and message='".$data_messages['id']."' and logo=0 LIMIT 1");
        if (mysqli_num_rows($query_file) > 0) {
          $data_file = mysqli_fetch_assoc($query_file);
          $type = substr($data_file['type'], strrpos($data_file['type'], '/') + 1);
          if ($type == 'png' or $type == 'jpeg' or $type == 'jpg') {
            $file_message='<div style="text-align:center;" class="py-3 image__wrapper"><img src="/file/uploads/bids/'.$data_file['name'].'" class="rounded minimized" style="width: 30%; height: auto;" alt="Изображение"></div>';
          } else {
            $file_message='<div style="text-align:left;" class="py-3"><a href="'.$uploaddir_bid_files_short.$data_file['name'].'" download="'.$data_file['description'].'">'.$data_file['description'].'</a></div>';
          }
        } else {
          $file_message="";
        }
        $message_text = $data_messages['message'].$file_message;
      } else {
        $message_text = $data_messages['message'];
      }
      if ($data_messages['user']==$user_name) {
        $message=$message.'<div class="alert alert-primary text-start trix-content" role="alert"><div class="text-muted">'.$data_messages['user'].' написал(а) '.$message_date.'</div> '.$message_text.'</div>';
      } else {
        $message=$message.'<div class="alert alert-success text-start trix-content" role="alert"><div class="text-muted">'.$data_messages['user'].' от '.$message_date.'</div> '.$message_text.'</div>';
      }
      $j++;
    }
    $message=$message.'
      </div>
    </div>';
    //Проверим, есть ли вообще сообщения?
    if ($j==0) {$message='';}
    
    $query_filial = mysqli_query($link, "SELECT name FROM bid_filials WHERE id='".mysqli_real_escape_string($link, $data['filial'])."' LIMIT 1");
    $data_filial = mysqli_fetch_assoc($query_filial);

    $query_tempates = mysqli_query($link, "SELECT name FROM bid_tempates WHERE id='".mysqli_real_escape_string($link, $data['template'])."' LIMIT 1");
    $data_tempates = mysqli_fetch_assoc($query_tempates);

    //Вытащим историю
    $query_history = mysqli_query($link,"SELECT status,date,user FROM bid_history WHERE id_bid='".$bid."' ORDER BY date ASC");
    $j=0;
    $history='';

    while ($data_history = mysqli_fetch_assoc($query_history)){
      $history_date = date('d-m-Y в h:i A', $data_history['date']);

      $query_status = mysqli_query($link,"SELECT status FROM bid_status WHERE id='".$data_history['status']."'");
      $data_status = mysqli_fetch_assoc($query_status);

      $query_user = mysqli_query($link,"SELECT f_name,s_name FROM users WHERE id='".$data_history['user']."'");
      $data_user = mysqli_fetch_assoc($query_user);

      $history = $history.'
      <tr>
        <td>'.$history_date.'</td>
        <td>'.$data_status['status'].'</td>
        <td>'.$data_user['f_name'].' '.$data_user['s_name'].'</td>
      </tr>
      ';
    }

    if (isset($_GET['search'])) {
      $button_return = '
      <div style="text-align:center;">
        <button type="submit" class="btn btn-warning" onclick="window.open(\'\', \'_self\', \'\'); window.close();">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Вернуться назад
        </button>
      </div>';
      $search_status = '<input type="hidden" name="search">';
    } else {
      $button_return = '
      <div style="text-align:center;">
        <button type="submit" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Вернуться назад
        </button>
      </div>';
      $search_status = '';
    }

    Printf('
    <div class="container">
      <center>
        <a href="/help/status.php?page=%s">
          %s
        </a>
      </center>
      </br>
      <div class="row justify-content-center">
        <div class="col col-md-8 py-3 border border-primary bg-light rounded-2">
          <div class="text-start">
            <div class="card border-light mb-3">
              <div class="card-header text-center">Заявка № <b>%s</b> от %s</div>
              <div class="card-body">
                <h5 class="card-title text-center">%s</h5>
                </br>
                <p class="card-text text-center">%s</p>
              </div>
              %s
            </div>
            %s
          </div>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="row justify-content-md-center">
              <div class="col col-md-10">
                <input id="trix_text" type="hidden" name="text_message">
                <trix-editor input="trix_text" class="form-control" placeholder="Напишите текст здесь." required></trix-editor>
                <br>
                <input type="hidden" name="page" value="%s">
                <input type="hidden" name="bid" value="%s">
              </div>  
            </div>
            <div class="row justify-content-md-center">
              <div class="col col-md-5 align-self-center">
                <input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
                <input name="userfile" class="form-control" type="file" id="formFile">
              </div>
              <div class="col col-md-4 align-self-center">
                <button name="send_message" class="btn btn-primary">Отправить сообщение</button>
              </div>
            </div>
          </form>
        </div>
        <div class="col col-md-4 py-3 border border-primary bg-light rounded-2">
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">О заявке</h5>
              <p class="card-text text-start">Филиал: %s</br>Шаблон: %s</br>Приоритет: %s</br>Исполнитель: %s</p>
              <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#staticBackdrop_history">
                История изменений
              </button>
              <!-- Modal history-->
              <div class="modal fade" id="staticBackdrop_history" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdrop_history_label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">История изменений статуса заявки</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <table class="table text-center table-hover table-bordered table-light bg-light" border="1">
                        <thead>
                          <tr class="table-active">
                            <th scope="col">Дата</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Пользователь</th>
                          </tr>
                        </thead>
                        <tbody> 
                            %s
                        </tbody>
                      </table>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </br>  
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Автор</h5>
              <p class="card-text text-start">Имя: %s</br>Подразделение: %s</br>Должность: %s</br>Телефон: %s</br>E-mail: %s</p>
            </div>
          </div>
          </br>
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Статус заявки</h5></br>%s
            </div>
          </div>
          </br>
          <div class="card text-center">
            <div class="card-body">
              <h5 class="card-title">Управление статусом</h5></br>
              <form action="" method="post">
              <div class="mb-3">
                <select name="bid_status_edit" class="form-select" aria-label="Default select example">
                  %s
                </select>
                </br>
                <input type="hidden" name="page" value="%s">
                <input type="hidden" name="bid" value="%s">
                %s
                <button name="edit_bid_status" class="btn btn-primary">Изменить статус</button>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    ',$page,$button_return,$bid,$bid_date,$data['theme'],$data['message'],$file,$message,$page,$bid,$data_filial['name'],$data_tempates['name'],$priority,$contractor_info,$history,$f_name.' '.$s_name,$unit,$position,$phone,$email,$status_color,$status_edit_menu,$page,$bid,$search_status);
    goto end_load;
  }

  $query = mysqli_query($link, "SELECT id FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
  if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
    $user_id=$data['id'];
  } else {echo $knopka_error; goto end_load;}
// Переменная хранит число сообщений выводимых на станице
$num = 50;
// Извлекаем из URL текущую страницу
$page = $_GET['page'];
// Определяем общее число сообщений в базе данных
$result = mysqli_query($link,"SELECT COUNT(*) FROM bid WHERE owner='".$user_id."'");
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
?>
<div class="container">
  <h4 style="text-align:center;">Просмотр моих заявок</h3>
  <div class="row justify-content-start">
    <div class="col col-md-4">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <?php
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
          echo '&nbsp;'.$pervpage.$page2left.$page1left.'<li class="page-item active "><a class="page-link" href="#">'.$page.'</a></li>'.$page1right.$page2right.$nextpage;
          ?>
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
            <th scope="col">Тема</th>
            <th scope="col">Дата</th>
            <th scope="col">Приоритет</th>
            <th scope="col">Статус</th>
        	  <th scope="col">Функции</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $query = mysqli_query($link,"SELECT id,priority,theme,date,status FROM bid WHERE owner='".$user_id."' ORDER BY id DESC LIMIT $start, $num");
            $i = $start + 1;
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
               //$text_problem=mb_strimwidth($data['text_problem'], 0, 30, "...");
               //Работаем со статусом заявки
               $status_color='';
               if ($data['status']==1) {
                $status_color='<div class="alert p-1 mb-1 custom-color-white-blue" role="alert">Новая заявка</div>';
                $status_text='Новая заявка';
                $status_edit_menu='
                  <option value="5" selected>Отменить</option>
                ';
              } else if ($data['status']==2){
                $status_color='<div class="alert p-1 mb-1 custom-color-blue" role="alert">Назначена</div>';
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
                <td><a href="/help/status.php?page=%s&bid=%s&details"><button type="button" name="details" class="btn btn-outline-primary">Подробнее</button></a></td>
               </tr>
               ',$data['id'],mb_strimwidth($data['theme'], 0, 60, "..."),$bid_date,$priority,$status_color,$page,$data['id']);
               $i++;
             }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="row justify-content-start">
    <div class="col col-md-4">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <?php
           echo '&nbsp;'.$pervpage.$page2left.$page1left.'<li class="page-item active "><a class="page-link" href="#">'.$page.'</a></li>'.$page1right.$page2right.$nextpage;
          ?>
        </ul>
      </nav>
    </div>
  </div>
</div>
</br>

<?php
  end_load:
  require("../data/footer.php");
?>
