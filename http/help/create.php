<?php
require("../data/header.php");
require("../data/db.php");

//Статсу обслуживания
$query = mysqli_query($link, "SELECT status_int FROM bid_system WHERE id=1 LIMIT 1");
$data = mysqli_fetch_assoc($query);

if ($data['status_int']==1) {echo '<meta http-equiv="Refresh" content="0; url=/help/maintenance.php" />';}

require("../data/session.php");
require("../data/clean.php");
require("../menu.php");

?>
<div class="container">
  <div class="row justify-content-md-center">
      <div class="col col-12 text-center">
        <h4 style="text-align:center;">Создание заявки на обслуживание</h4>
      </div>
  </div>
  </br>
  <?php
  //print_r($_FILES);
  if(isset($_POST['submit_req'])){
    //Если есть файл, тогда обработаем его.
    if ($_FILES["userfile"]['size']>0){
      //Проверка размера загруженного файла
      if ($_FILES['userfile']['size']>$file_size_max) {
        echo '<div class="alert alert-warning text-center" role="alert">Заявка не создана. Превышен размер файла. <b>Максимальный размер 20 Мегабайт.</b></div>
        <div style="text-align:center;">
          <a href="/help/create.php"><button type="submit" class="btn btn-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
            </svg>
            Назад
            </button>
          </a>
        </div>';
        goto end_load;
      }
      // Проверка расширения файла
      // <input name="userfile" class="form-control" type="file" id="formFile" accept=".jpg,.jpeg,.png,.bmp">
      // $extension = substr($_FILES["userfile"]["name"], strrpos($_FILES["userfile"]["name"], '.') + 1);
      // if(in_array($extension, array("jpg","JPG","jpeg","JPEG", "png","PNG", "bmp", "BMP")) == False){
      //   echo '<div class="alert alert-warning text-center" role="alert">Заявка не создана. Прикреплять можно только изображения.</b></div>
      //   <div style="text-align:center;">
      //     <a href="/help/create.php"><button type="submit" class="btn btn-warning">
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
      $file_description = $_FILES['userfile']['name'];
      $extension = substr($_FILES["userfile"]["name"], strrpos($_FILES["userfile"]["name"], '.') + 1);
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

    //Проверим данные из формы
    if (isset($_POST["theme"])) {$theme = clean($_POST['theme']); $theme=mb_strimwidth($theme, 0, 300, "...");} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST['priority'])) {$priority=clean($_POST['priority']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST['filial'])) {$filial=clean($_POST['filial']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST['template'])) {$template=clean($_POST['template']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST["text_problem"])) {$text_problem = clean($_POST['text_problem']); $text_problem=mb_strimwidth($text_problem, 0, 8000, "...");} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    
    //Создадим заявку
    //Генерация случайного номера заявки
    // $permitted_chars = '0123456789';
    // $id_bid = 'Req_'.substr(str_shuffle($permitted_chars), 0, 8);
    $query = mysqli_query($link, "SELECT count(id) AS max_count_bid FROM bid LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    $id_bid = $data['max_count_bid'] + 1;

    if ($flagfile == TRUE) {
      $query = mysqli_query($link, "SELECT count(id) AS max_count_bid_file FROM bid_files LIMIT 1");
      $data = mysqli_fetch_assoc($query);
      $id_bid_file = $data['max_count_bid_file'] + 1;
    }
   
     //Подтянем Имя пользователя если есть
     $query = mysqli_query($link, "SELECT id,f_name,s_name,email,phone FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
     $data = mysqli_fetch_assoc($query);
     $u_name=$data['f_name'].' '.$data['s_name'];
     $email=$data['email'];
     $phone=$data['phone'];
     $user_id=$data['id'];

    $result=mysqli_query($link,"INSERT INTO bid (id,priority,template,filial,theme,status,owner,contractor,date,message,alarm_create,alarm_update)
    VALUES ('$id_bid','$priority','$template','$filial','$theme',1,'$user_id','0','$tek_date','$text_problem',1,0)");
    if ($result=='true') {
      echo '<div class="alert alert-success text-center" role="alert">Заявка успешно создана.</br></br>Уникальный ID вашей заявки: <b>'.$id_bid.'</b><br>Запомните данный ID для быстрого поиска по заявкам.</div></br>
      <div style="text-align:center;">
        <a href="/help/">
          <button type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
            </svg>
              Вернуться на главную
          </button>
        </a>
      </div>';

      $query = mysqli_query($link, "SELECT count(id) AS max_count FROM bid_history LIMIT 1");
      $data = mysqli_fetch_assoc($query);
      $id_history = $data['max_count'] + 1;

      $result=mysqli_query($link,"INSERT INTO bid_history (id,id_bid,status,date,user) 
      VALUES ('$id_history','$id_bid',1,'$tek_date',$user_id)");

      if ($flagfile == TRUE) {
        $result=mysqli_query($link,"INSERT INTO bid_files (id,id_bid,date,message,logo,type,name,description)
        VALUES ('$id_bid_file','$id_bid','$tek_date',0,1,'$file_type','$file_name','$file_description')");
      }
    }
    else {
      echo '<div class="alert alert-danger text-center" role="alert">Что то пошло не так. Просьба обратиться к разработчику.</div></br>
      <div style="text-align:center;">
        <a href="/help/"><button type="submit" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
          </svg>
          Вернуться назад
          </button>
        </a>
      </div>';
    }

    goto end_load;
  }

  if(isset($_POST['start_create_bid'])){
    if (isset($_POST['template'])) {$template=clean($_POST['template']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
    if (isset($_POST['filial'])) {$filial=clean($_POST['filial']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

    //Подтянем Имя пользователя если есть
    $query = mysqli_query($link, "SELECT f_name,s_name,email,phone FROM users WHERE id_user='".mysqli_real_escape_string($link, $_COOKIE['id_user'])."' LIMIT 1");
    if(mysqli_num_rows($query) > 0){
      $data = mysqli_fetch_assoc($query);
      $u_name=$data['f_name'].' '.$data['s_name'];
      $email=$data['email'];
      $phone=$data['phone'];
    } else {
      $u_name='';
      $email='';
      $phone='';
    }

    //Вытащим приоритет
    $priority = '';
    $query = mysqli_query($link, "SELECT id,name FROM bid_priority WHERE active=1 ORDER BY id DESC");
    while ($data = mysqli_fetch_assoc($query)) {
      $priority = $priority.'<option value="'.$data['id'].'">'.$data['name'].'</option>';
    }
    printf ('
    </br>
    <div class="row justify-content-center">
      <div class="col col-lg-10 py-3 border border-primary bg-light rounded-2">
        <h5 style="text-align:center;">Заполните необходимые данные по новой задаче</h5>
        </br>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="row justify-content-md-center">
            <div class="col col-md-10">
              <label for="exampleFormControl1" class="form-label">Тема:</label>
              <input type="text" name="theme" class="form-control" id="exampleFormControlInput1" placeholder="Тема заявки" required></div>
          </div>
          </br>
          <div class="row justify-content-md-center">
            <div class="col col-md-10">
              <label for="exampleFormControl1" class="form-label">Опишите Вашу задачу:</label>
              <input id="trix_text" type="hidden" name="text_problem">
              <trix-editor input="trix_text" class="form-control" placeholder="Напишите любую полезную инфомрацию, которая поможет быстро решить задачу" required></trix-editor>
            </div>
          </div>
          </br>
          <div class="row justify-content-md-center">
            <div class="col col-md-7">
              <label>Прикрепить файл:</label>
              <input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
              <input name="userfile" class="form-control" type="file" id="formFile">
              <small id="passwordHelpBlock" class="form-text text-muted">Размер файла до 20 Мбайт. Не обязательно заполнять.</small>
            </div>
            <div class="col col-md-3">
              <label>Приоритет:</label>
              <select name="priority" class="form-select" name="template" required>
              %s
            </select>
            </div>
          </div>
          </br>
          <input type="hidden" name="filial" value="%s">
          <input type="hidden" name="template" value="%s">
          <div style="text-align:center;">
            <button name="submit_req" type="submit" class="btn btn-success">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8zm-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"></path>
              </svg>
              Продолжить
            </button>
          </div>
        </form>
        </br>
        <div style="text-align:center;">
          <a href="/help/create.php"><button type="submit" class="btn btn-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
            </svg>
            Назад
            </button>
          </a>
        </div>
      </div>
    </div>
    ',$priority,$filial,$template);
    goto end_load;
    }

  ?>
  </br>
  <div class="row justify-content-center">
    <div class="col col-lg-8 py-3 border border-primary bg-light rounded-2">
      <h5 style="text-align:center;">Выберите параметры для создания заявки</h5>
      </br>
      <form action="" method="post">
        <div class="row justify-content-md-center">
        <div class="col col-md-3">
            <label for="exampleFormControlInput1" class="form-label">Филиал:</label>
            <select class="form-select" name="filial" required>
              <?php
                $query = mysqli_query($link, "SELECT id,name FROM bid_filials WHERE active=1 ORDER BY name");
                while ($data = mysqli_fetch_assoc($query)) {
                  printf('<option value="%s">%s</option>',$data['id'],$data['name']);
                }
              ?>
            </select>
					</div>
					<div class="col col-md-6">
            <label for="exampleFormControlInput1" class="form-label">Шаблон заявки:</label>
            <select class="form-select" name="template" required>
              <?php
                $query = mysqli_query($link, "SELECT id,name FROM bid_tempates WHERE active=1 ORDER BY name");
                while ($data = mysqli_fetch_assoc($query)) {
                  printf('<option value="%s">%s</option>',$data['id'],$data['name']);
                }
              ?>
            </select>
					</div>
        </div>
				</br>
        <div style="text-align:center;">
          <button name="start_create_bid" type="submit" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8zm-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"></path>
            </svg>
            Продолжить
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
end_load:
include("../data/footer.php"); ?>
