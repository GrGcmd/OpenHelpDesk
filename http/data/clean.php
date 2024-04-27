<?php
  function clean($value = "") {
    $value = trim($value);
    $value = stripslashes($value);
    //$value = strip_tags($value);
    //$value = htmlspecialchars($value);

    return $value;
}
  function ldap_password_clean($value = "") {
    $value = trim($value);
    return $value;
}
?>
