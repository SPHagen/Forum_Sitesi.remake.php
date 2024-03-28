<?php
session_start();
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';

$data = $db -> prepare("SELECT * FROM konular");
$data -> execute();
$_data = $data -> fetch(PDO::FETCH_ASSOC);

$dataa = $db -> prepare("SELECT * FROM yorumlar");
$dataa -> execute();
$_dataa = $dataa -> fetch(PDO::FETCH_ASSOC);
?>

<center>
<?php
$UyeID = @$_SESSION["uye_id"];
$konuid = $_GET['kid'];
$dataList = $db -> prepare("SELECT konu_ad FROM konular WHERE konu_id=? && konu_ad=? ORDER BY konu_id ASC");
$dataList -> execute([
  $_GET['kid'],
  $_data['konu_ad']
]);
$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
echo "$UyeID no'lu üyesiniz, $konuid no'lu konuyu şikayet ediyorsunuz!".'<br><br>';
echo 'Şikayet etmek istediğiniz konu:<br><br>'.$_data["konu_ad"].'<br><br>';
?>
</center>