<?php
require("../data/header.php");
require("../data/db.php");
require("../data/session.php");
require("../data/clean.php");

echo '</br></br></br></br></br><div style="text-align:center;">Ведутся технические работы. Пожалуйста ожидайте.<div>';

echo '</br><div style="text-align:center;"><a href="/help/"><button type="button" class="btn btn-secondary">Обновить статус</button></a></div>';

echo '</br></br></br><div style="text-align:center;"><a href="/help/admin/"><button type="button" class="btn btn-outline-secondary">Вход для администратора</button></a></div>';

require("../data/footer.php");
?>
