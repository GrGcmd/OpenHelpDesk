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

$knopka_error = '
  <div class="alert alert-danger text-center" role="alert">Что то пошло не так. Просьба обратиться к разработчику.</div></br>
  <div style="text-align:center;">
    <a href="/help/admin/filials.php"><button type="submit" class="btn btn-warning">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
      <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4z"></path>
      </svg>
      Вернуться назад
      </button>
    </a>
  </div>';

if (isset($_POST['submit_add_filial'])) {
  if (isset($_POST["new_filial"])) {$new_filial = clean($_POST['new_filial']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}

  $query = mysqli_query($link, "SELECT count(id) AS max_count_filial FROM bid_filials LIMIT 1");
  $data = mysqli_fetch_assoc($query);
  $id_filial = $data['max_count_filial'] + 1;

  $result=mysqli_query($link,"INSERT INTO bid_filials (id,name,active)
  VALUES ('$id_filial','$new_filial',1)");
  if ($result=='true') {
    echo '<div class="alert alert-success text-center" role="alert">Новый филиал создан успешно.</br></div></br>
    <div style="text-align:center;">
      <a href="/help/admin/filials.php">
        <button type="submit" class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
          </svg>
            Вернуться назад
        </button>
      </a>
    </div>
    <meta http-equiv="refresh" content="5;url=/help/admin/filials.php">';
    goto end_load;
  } else {
    echo $knopka_error;
    goto end_load;
  }
  goto end_load;
}

if (isset($_POST['submit_update_filial'])) {
  if (isset($_POST["tek_filial"])) {$tek_filial = clean($_POST['tek_filial']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  if (isset($_POST["new_filial"])) {$new_filial = clean($_POST['new_filial']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  $result=mysqli_query($link,"UPDATE bid_filials SET name='".$new_filial."' WHERE id='".$tek_filial."' LIMIT 1");
  if ($result=='true') {
    echo '<div class="alert alert-success text-center" role="alert">Название обновлено успешно.</div></br>
    <div style="text-align:center;">
      <a href="/help/admin/filials.php">
        <button type="submit" class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
          </svg>
            Вернуться назад
        </button>
      </a>
    </div>
    <meta http-equiv="refresh" content="5;url=/help/admin/filials.php">';
    goto end_load;
  } else {
    echo $knopka_error;
    goto end_load;
  }

}

if (isset($_POST['submit_delete_filial'])) {
  if (isset($_POST["tek_filial"])) {$tek_filial = clean($_POST['tek_filial']);} else {echo '<div style="text-align:center;">O_o</div>'; exit();}
  
  $result=mysqli_query($link,"UPDATE bid_filials SET active=0 WHERE id='".$tek_filial."' LIMIT 1");
  if ($result=='true') {
    echo '<div class="alert alert-success text-center" role="alert">Название удалено успешно.</div></br>
    <div style="text-align:center;">
      <a href="/help/admin/filials.php">
        <button type="submit" class="btn btn-success">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-90deg-up" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.854 1.146a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L4 2.707V12.5A2.5 2.5 0 0 0 6.5 15h8a.5.5 0 0 0 0-1h-8A1.5 1.5 0 0 1 5 12.5V2.707l3.146 3.147a.5.5 0 1 0 .708-.708l-4-4z"></path>
          </svg>
            Вернуться назад
        </button>
      </a>
    </div>
    <meta http-equiv="refresh" content="5;url=/help/admin/filials.php">';
    goto end_load;
  } else {
    echo $knopka_error;
    goto end_load;
  }

}

?>
<div class="row justify-content-center">
  <div class="col col-12 text-center">
    <h4>Управление филиалами</h4>
  </div>
</div>
</br>
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-md-4 py-3 border border-info bg-light rounded-lg rounded">
      <div style="text-align:center;"><h4>Добавление</h4></div>
      </br>
      <form method="POST">
        <div class="row justify-content-md-center">
					<div class="col col-md-12">
	          <label>Имя нового филиала</label>
	          <input type="text" name="new_filial" class="form-control" required>
	          <small id="passwordHelpBlock" class="form-text text-muted">
	            Например название города или краткий адрес
	          </small>
					</div>
        </div>
			</br>
        <div style="text-align:center;"><button name="submit_add_filial" type="submit" class="btn btn-primary">Добавить запись</button></div>
      </form>
    </div>
    <div class="col col-md-4 py-3 border border-warning table-bordered bg-light rounded-lg rounded">
      <div style="text-align:center;"><h4>Обновление</h4></div>
      </br>
      <form action="" method="post">
        <div class="row justify-content-md-center">
					<div class="col col-md-12">
	          <label>Текущее имя</label>
              <select name="tek_filial" class="form-select" aria-label="Default select example">
                  <?php 
                    $actual_filial = '<option value="0"></option>';
                    $query = mysqli_query($link, "SELECT id,name FROM bid_filials WHERE active=1");
                    while ($data = mysqli_fetch_assoc($query)) {
                      $actual_filial = $actual_filial.'<option value="'.$data['id'].'">'.$data['name'].'</option>';
                    }
                    echo $actual_filial;
                  ?>
              </select>
              <small id="passwordHelpBlock" class="form-text text-muted">
                Выберите одно из текущих значений
              </small>
					</div>
					<div class="col col-md-12">
	          <label for="exampleInputEmail1">Обновлённое имя</label>
	          <input type="text" name="new_filial" class="form-control" required>
	          <small id="passwordHelpBlock" class="form-text text-muted">
	            Например название города или краткий адрес
	          </small>
	        </div>
        </div>
			</br>
        <div style="text-align:center;"><button name="submit_update_filial" type="submit" class="btn btn-secondary">Обновить</button></div>
      </form>
    </div>
	<div class="col col-md-4 py-3 border border-danger bg-light rounded-lg rounded">
      <div style="text-align:center;"><h4>Удаление</h4></div>
      </br>
      <form action="" method="post">
        <div class="row justify-content-md-center">
					<div class="col col-md-12">
	          <label>Филиал</label>
	          <select name="tek_filial" class="form-select" aria-label="Default select example">
                <?php 
                  $actual_filial = '<option value="0"></option>';
                  $query = mysqli_query($link, "SELECT id,name FROM bid_filials WHERE active=1");
                  while ($data = mysqli_fetch_assoc($query)) {
                    $actual_filial = $actual_filial.'<option value="'.$data['id'].'">'.$data['name'].'</option>';
                  }
                  echo $actual_filial;
                ?>
            </select>
            <small id="passwordHelpBlock" class="form-text text-muted">
              Выберите одно из текущих значений. Удаление знаения из системы.
            </small>
					</div>
        </div>
				</br>
        <div style="text-align:center;"><button name="submit_delete_filial" type="submit" class="btn btn-danger">Удалить</button></div>
      </form>
    </div>
  </div>
</br>

<?php
  end_load:
  require("../../data/footer.php");
?>
