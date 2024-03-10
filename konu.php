<?php
session_start();
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';

$link = $_GET["link"];

$data = $db -> prepare("SELECT * FROM konular WHERE
  konu_link=?

");
$data -> execute([
  $link
]);
$data = $db -> prepare("SELECT * FROM konular WHERE
        konu_link=?
    ");
    $data -> execute([
        $link
    ]);
    $_data = $data -> fetch(PDO::FETCH_ASSOC);
?>
<center>

<?php include 'ust_bilgi.php'; ?>

<br> <br>



<h2><?php echo $_data["konu_ad"]; ?></h2>

<strong> Konu Sahibi: </strong><a href= "profil.php?kadi=<?= uye_ID_to_kadi($_data["konu_uye_id"])?>"><?=uye_ID_to_kadi($_data["konu_uye_id"])?></a>

<p>
<?php echo $_data["konu_mesaj"]; ?>
</p>

<p>
    <small><?=$_data["konu_tarih"]?></small>
    <a href="sikayet.php"><br><br><strong>Konuyu Şikayet Et</strong></a>
</p>
<hr>
<p>Yorumlar: </p>
<?php

$dataList = $db -> prepare("SELECT * FROM yorumlar WHERE y_konu_id=? ORDER BY y_id DESC");
$dataList -> execute([
  $_data["konu_id"]
]);
$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);

foreach($dataList as $row){
    echo '<a href="profil.php?kadi='.uye_ID_to_kadi($row["y_uye_id"]).'" id=yorum"id'.$row["y_id"].'><strong>'
    .uye_ID_to_kadi($row["y_uye_id"]).'</strong></a><br>
    <p>
    '.$row["y_yorum"].'
    </p>
    <small><strong>Tarih: </strong>'.$row["y_tarih"].'</small>
    <a href="sikayet.php"><br><br><strong>Yorumu Şikayet Et</strong></a>
    <hr>';

}
?>
<?php
if (@$_SESSION["uye_id"]) {

  if ($_POST)
  {
    $yorum = $_POST["yorum"];

    $dataAdd = $db -> prepare("INSERT INTO yorumlar SET
    y_uye_id=?,
    y_konu_id=?,
    y_yorum=?
");
$dataAdd -> execute([
    $_SESSION["uye_id"],
    $_data["konu_id"],
    $yorum
]);

if ( $dataAdd ) {

  $yorumcek = $db -> prepare("SELECT * FROM yorumlar WHERE
  y_uye_id=?
  &&
  y_konu_id=?
");
$yorumcek -> execute([
  $_SESSION["uye_id"],
    $_data["konu_id"]
]);
$yorum_cek = $yorumcek -> fetch(PDO::FETCH_ASSOC);

    echo '<p class="alert alert-success">Yorum eklendi.</p>';
    
    // Yönlendirme yapar.
    header("REFRESH:1;URL=konu.php?link=" . $link . "#yorum" . $yorum_cek["y_id"]);
} else {
    echo '<p class="alert alert-danger">Yorumunuz eklenirken hata oluştu.</p>';
    
    header("REFRESH:1;URL=konu.php?link=" . $link . "#yorumyap");
}
  }
  echo '<h4 id="yorumyap">Konuya Yorum Yap: </h4>
  <form action="" method="post">
  <textarea name="yorum" cols="30" rows="15"> </textarea>
  <br>
  <br>
  <input type="submit" value="Yorumu Yayınla">
  </form>';
} else {
  echo 'Konuya yorum yapabilmek için lütfen <a href="uyelik.php"> GİRİŞ YAPIN</a> veya <a href="uyelik.php?p=kayit">KAYIT OLUN </a>';
}
?>
</center>