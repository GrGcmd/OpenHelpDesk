<?php
require("data/header.php");
require("data/db.php");
require("data/session.php");
require("data/clean.php");
require("menu.php");

?>
<meta http-equiv="Refresh" content="0; url=/help/" />
<div class="container">
  <div class="row justify-content-md-center">
      <div class="col col-lg-12 py-3 border border-primary bg-light rounded-2">
        <center><h4>Главная страница</h3></center>
          <p class="text-center"><?php echo date("d.m.Y h:i:s A",time()) ?></p>
      </div>
  </div>
</div>
</br>

<?php include("data/footer.php"); ?>
