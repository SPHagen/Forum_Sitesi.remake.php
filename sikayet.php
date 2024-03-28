<center>
<?php
session_start();
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';

$UyeID = $_SESSION["uye_id"];
$YorumID = $_GET['yid'];

$data = $db -> prepare("SELECT * FROM konular");
$data -> execute();
$_data = $data -> fetch(PDO::FETCH_ASSOC);

// Yorum Satırı

echo "$UyeID, numaralı üyesiniz! $YorumID, numaralı yorumu şikayet ediyorsunuz!<br><br>";

$VeriListeleme = $db -> prepare("SELECT * FROM yorumlar WHERE y_id=?");
$VeriListeleme -> execute([
  $_GET['yid']
  ]);
$VeriListeleme = $VeriListeleme -> fetchALL(PDO::FETCH_ASSOC);

foreach($VeriListeleme as $Yin) {
  echo $Yin["y_yorum"];
  echo '<br><br>Şikayet Etmek İstediğiniz Yorumun Konusu: '.$Yin[''];
}
?>
</center>