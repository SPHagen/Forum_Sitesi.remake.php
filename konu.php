<?php

ob_start();
session_start();
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';

// ====================================================================================================

  $link = $_GET["link"];
  $Konu = $db -> prepare("SELECT * FROM konular WHERE konu_link=?");
  $Konu -> execute([$link]);
  $_Konu = $Konu -> fetch(PDO::FETCH_ASSOC);

  // Veri tabanından link üzerine göre açılan konular çekildi.

  // ====================================================================================================

  // ====================================================================================================

   $KonuSahibiYorumSayisi = $db -> prepare("SELECT count(y_yorum) as konusahibi_yorumsayisi FROM yorumlar WHERE y_uye_id=?");
   $KonuSahibiYorumSayisi -> execute([$_Konu["konu_uye_id"]]);
   $_KonuSahibiYorumSayisi = $KonuSahibiYorumSayisi -> fetch(PDO::FETCH_ASSOC);

   // Konuyu açan kullanıcının yorum sayısı çekildi.

  // ====================================================================================================

  // ====================================================================================================

  $ks_AcilanKonuSayisiCekme = $db -> prepare("SELECT count(konu_ad) as konusahibi_konusayisi FROM konular WHERE konu_uye_id=?");
  $ks_AcilanKonuSayisiCekme -> execute([$_Konu["konu_uye_id"]]);
  $_ks_AcilanKonuSayisiCekme = $ks_AcilanKonuSayisiCekme -> fetch(PDO::FETCH_ASSOC);

  // Konuyu açan kullanıcının açtığı konu sayısı çekildi.

  // ====================================================================================================

  ?>
  
  <center>
  
  <?php include 'ust_bilgi.php'; ?>

  <br> <br>

  <h2><?php echo $_Konu["konu_ad"]; ?></h2>
  
  Konu Sahibi<br><br><a href= "profil.php?kadi=<?=uye_ID_to_kadi($_Konu["konu_uye_id"])?>"><?='<strong>'.uye_ID_to_kadi($_Konu["konu_uye_id"]).'</strong>'?></a>
  
  <p>

  <?php echo $_Konu["konu_mesaj"]; ?>

  </p>
  
  <p>

  <small><?php echo $_Konu["konu_tarih"].'<br><br>' ?></small>

  <?php

  // ====================================================================================================

  echo 'Konu Sahibinin Attığı Yorum Sayısı < '.($_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"].' ><br><br>');
  echo 'Konu Sahibinin Açtığı Konu Sayısı < '.$_ks_AcilanKonuSayisiCekme["konusahibi_konusayisi"].' ><br><br>';
  if (uye_ID_to_onay($_Konu["konu_uye_id"]) !== 1 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] >= 0 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] < 10) echo '<font color="MediumVioletRed" >Acemi Üye</font>';
  else if (uye_ID_to_onay($_Konu["konu_uye_id"]) == 1 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] >= 0 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] < 10) echo '<font color="Green" >Onaylı </font><font color="MediumVioletRed" >Acemi Üye</font>';
  else if (uye_ID_to_onay($_Konu["konu_uye_id"]) !== 1 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] >= 10 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] < 25) echo '<font color="Coral" >Tecrübeli Üye</font>';
  else if (uye_ID_to_onay($_Konu["konu_uye_id"]) == 1 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] >= 10 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] < 25) echo '<font color="Green" >Onaylı </font><font color="Coral" >Tecrübeli Üye</font>';
  else if (uye_ID_to_onay($_Konu["konu_uye_id"]) !== 1 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] >= 25 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] < 50) echo '<font color="DarkOrange" >Başarılı Üye</font>';
  else if (uye_ID_to_onay($_Konu["konu_uye_id"]) == 1 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] >= 25 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] < 50) echo '<font color="Green" >Onaylı </font><font color="DarkOrange" >Başarılı Üye</font>';
  else if (uye_ID_to_onay($_Konu["konu_uye_id"]) !== 1 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] >= 50 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] < 100) echo '<font color="Crimson" >Emektar Üye</font>';
  else if (uye_ID_to_onay($_Konu["konu_uye_id"]) == 1 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] >= 50 && $_KonuSahibiYorumSayisi["konusahibi_yorumsayisi"] < 100) echo '<font color="Green" >Onaylı </font><font color="Crimson" >Emektar Üye</font>';

  // Konu sahibinin attığı yorum sayısı ve seviyesi ekrana yazdırıldı.

  // ====================================================================================================

  echo '<a href="sikayet.php?konu-id='.$_Konu['konu_ad'].'"><br><br><strong>Konuyu Şikayet Et</strong></a><br><br>';

  ?>

  </p>

  <hr>

  <p>YORUMLAR</p>

  <?php

  $KonuCekme = $db -> prepare("SELECT * FROM yorumlar WHERE y_konu_id=? ORDER BY y_id DESC");
  $KonuCekme -> execute([$_Konu["konu_id"]]);
  $KonuCekme__ = $KonuCekme -> fetchALL(PDO::FETCH_ASSOC);

  foreach($KonuCekme__ as $_KonuCekme) {

  // ====================================================================================================

  $UyeYorumSayisiCekme = $db -> prepare("SELECT COUNT(y_yorum) as yorum_sayisi FROM yorumlar WHERE y_uye_id=?");
  $UyeYorumSayisiCekme -> execute([$_KonuCekme["y_uye_id"]]);
  $_UyeYorumSayisiCekme = $UyeYorumSayisiCekme -> fetch(PDO::FETCH_ASSOC);

  // Yorumu atan üye kullanıcının yorum sayısı çekildi.

  // ====================================================================================================

  // ====================================================================================================

  $AcilanKonuSayisiCekme = $db -> prepare("SELECT count(konu_mesaj) as konu_sayisi FROM konular WHERE konu_uye_id=?");
  $AcilanKonuSayisiCekme -> execute([$_KonuCekme["y_uye_id"]]);
  $_AcilanKonuSayisiCekme = $AcilanKonuSayisiCekme -> fetch(PDO::FETCH_ASSOC);

  // Yorumu atan kullanıcının açtığı konu sayısı çekildi.

  // ====================================================================================================

  echo '<a href="profil.php?kadi='.uye_ID_to_kadi($_KonuCekme["y_uye_id"]).'" id=yorum"id'.$_KonuCekme["y_id"].'><strong>'
  .uye_ID_to_kadi($_KonuCekme["y_uye_id"]).'</strong></a><br>
  <p>
  '.$_KonuCekme["y_yorum"].'
  </p>
  <small>'.$_KonuCekme["y_tarih"].'</small><br><br>';


  // ====================================================================================================

  echo 'Attığı Yorum Sayısı < '.($_UyeYorumSayisiCekme["yorum_sayisi"].' ><br><br>');
  echo 'Açtığı Konu Sayısı < '.$_AcilanKonuSayisiCekme["konu_sayisi"].' ><br><br>';
  if (uye_ID_to_onay($_KonuCekme["y_uye_id"]) !== 1 && $_UyeYorumSayisiCekme["yorum_sayisi"] >= 0 && $_UyeYorumSayisiCekme["yorum_sayisi"] < 10) echo '<font color="MediumVioletRed" >Acemi Üye</font>';
  else if (uye_ID_to_onay($_KonuCekme["y_uye_id"]) == 1 && $_UyeYorumSayisiCekme["yorum_sayisi"] >= 0 && $_UyeYorumSayisiCekme["yorum_sayisi"] < 10) echo '<font color="Green" >Onaylı </font><font color="MediumVioletRed" >Acemi Üye</font>';
  else if (uye_ID_to_onay($_KonuCekme["y_uye_id"]) !== 1 && $_UyeYorumSayisiCekme["yorum_sayisi"] >= 10 && $_UyeYorumSayisiCekme["yorum_sayisi"] < 25) echo '<font color="Coral" >Tecrübeli Üye</font>';
  else if (uye_ID_to_onay($_KonuCekme["y_uye_id"]) == 1 && $_UyeYorumSayisiCekme["yorum_sayisi"] >= 10 && $_UyeYorumSayisiCekme["yorum_sayisi"] < 25) echo '<font color="Green" >Onaylı </font><font color="Coral" >Tecrübeli Üye</font>';
  else if (uye_ID_to_onay($_KonuCekme["y_uye_id"]) !== 1 && $_UyeYorumSayisiCekme["yorum_sayisi"] >= 25 && $_UyeYorumSayisiCekme["yorum_sayisi"] < 50) echo '<font color="DarkOrange" >Başarılı Üye</font>';
  else if (uye_ID_to_onay($_KonuCekme["y_uye_id"]) == 1 && $_UyeYorumSayisiCekme["yorum_sayisi"] >= 25 && $_UyeYorumSayisiCekme["yorum_sayisi"] < 50) echo '<font color="Green" >Onaylı </font><font color="DarkOrange" >Başarılı Üye</font>';
  else if (uye_ID_to_onay($_KonuCekme["y_uye_id"]) !== 1 && $_UyeYorumSayisiCekme["yorum_sayisi"] >= 50 && $_UyeYorumSayisiCekme["yorum_sayisi"] < 100) echo '<font color="Crimson" >Emektar Üye</font>';
  else if (uye_ID_to_onay($_KonuCekme["y_uye_id"]) == 1 && $_UyeYorumSayisiCekme["yorum_sayisi"] >= 50 && $_UyeYorumSayisiCekme["yorum_sayisi"] < 100) echo '<font color="Green" >Onaylı </font><font color="Crimson" >Emektar Üye</font>';

  // Üyenin attığı yorum sayısı ve seviyesi ekrana yazdırıldı.

  // ====================================================================================================
  echo '<a href="sikayet.php?yid='.$_KonuCekme['y_id'].'"><br><br><strong>Yorumu Şikayet Et</strong></a><br><br>';
  echo '<hr>';

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
    y_yorum=?"
    );
$dataAdd -> execute([
    $_SESSION["uye_id"],
    $_Konu["konu_id"],
    $yorum
]);

if ($dataAdd) {

  $yorumcek = $db -> prepare("SELECT * FROM yorumlar WHERE
  y_uye_id=?
  &&
  y_konu_id=?
");
$yorumcek -> execute([
  $_SESSION["uye_id"],
    $_Konu["konu_id"]
]);
$yorum_cek = $yorumcek -> fetch(PDO::FETCH_ASSOC);

    echo '<p class="alert alert-success">Yorum eklendi.</p>';
    
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
ob_end_flush();
?>
</center>