<?php
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';
<?php
$data = $db -> prepare("SELECT count(y_yorum) as yorumsayisi FROM yorumlar WHERE y_uye_id=2");
$data -> execute();
$_data = $data -> fetch(PDO::FETCH_ASSOC);
var_dump($_data["yorumsayisi"]);
?>