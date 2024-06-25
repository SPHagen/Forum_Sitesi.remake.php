<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>..:: Yorumu Şikayet Et! ::..</title>
  <link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
</head>
<body>
<div id = "Yapıcı" >
        <?php

        session_start();
        ob_start();
        include 'ayar.php';
        include 'ukas.php';
        include 'fonksiyon.php';

        $yid = $_GET['yid'];
        $YorumCek = $db -> prepare("SELECT * FROM yorumlar WHERE y_id=?");
        $YorumCek -> execute([$yid]);
        $_YorumCek = $YorumCek -> fetch(PDO::FETCH_ASSOC);
        
        $KonuCek = $db -> prepare("SELECT * FROM konular WHERE konu_id=?");
        $KonuCek -> execute([$_YorumCek["y_konu_id"]]);
        $_KonuCek = $KonuCek -> fetch(PDO::FETCH_ASSOC);

        ?>
        <div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <Kategori_ismi_konu_ac><strong>Şikayet Etmek İstediğiniz Yorum</strong></Kategori_ismi_konu_ac>
            <br>
            <isimli_kategoride_konu_aciniz><strong><?= $_YorumCek['y_yorum']; ?></strong></isimli_kategoride_konu_aciniz>
            <?php
            if (@$_SESSION['uye_id']) {
                echo '<Profilime_git><a href = "profil.php?kadi='.@$_SESSION["uye_kadi"].'" ><font color = "Beige" >Profilime Git</font></a></Profilime_git>
                <Cikis_yap><a href = "uyelik.php?p=cikis" ><font color = "Beige" >Çıkış Yap</font></a></Cikis_yap>'; }
            else {
                echo '<Giris_yap><a href = "uyelik.php" ><font color = "Beige" >Giriş Yap</font></a></Giris_yap>
                <Kayit_ol><a href = "uyelik.php?p=kayit" class = "KayitOl" ><font color = "Beige" >Kayıt Ol</font></a></Kayit_ol>'; }
            ?>
        </div>
        <div id = "Merkez" >
        <center>
          <br><br>
          <?php
          if (@$_SESSION["uye_id"]) {
            $Yorum_Adi = $_YorumCek["y_yorum"];
            $Yorum_ID = $_YorumCek["y_id"];
            $Konu_Adi = '<a href = "konu.php?link='.$_KonuCek['konu_link'].'">'.$_KonuCek['konu_ad'].'</a>';
            $Konu_ID = $_KonuCek['konu_id'];
            echo "<div id = 'Şikayet_Bilgileri' ><br>$Yorum_Adi<br><br>
            Yorumun ID'si: $Yorum_ID<br><br>Konu Adı<br>$Konu_Adi<br><br>Konunun ID'si: $Konu_ID</div><br><br>"; }
          else {
            echo 'Konuyu Şikayet Edebilmek İçin <a href="uyelik.php"><strong>Giriş Yapın</strong></a> veya <a href="uyelik.php?p=kayit"><strong>Kayıt Olun</strong></a>'; }
          ?>
          <form Action = "" Method = "Post" >
          <input Type = "Submit" name = "yorum_sikayet" id = "Şikayeti_Gönder" Value = "Şikayeti Gönder" >
          <input Type = "Reset" id = "Şikayeti_Temizle" Value = "Sayfayı Temizle" >
          <br><br>
          <textarea id = "Sikayet_Mesaji" name = "sikayet_y"
          onfocus = "if (this.value == 'Lütfen Şikayet Detaylarınızı Yazınız!') { this.value = ''; }" >Lütfen Şikayet Detaylarınızı Yazınız!</textarea> 
        </form>
        <?php
        if (!(empty($_POST['sikayet_y'])) && $_POST['sikayet_y'] !== 'Lütfen Şikayet Detaylarınızı Yazınız!' && isset($_POST["yorum_sikayet"])) {

          $sikayet_y = $_POST["sikayet_y"];
          $sikayet_y_konulink = $_KonuCek["konu_link"];
          $SikayetGonder = $db -> prepare("INSERT INTO sikayety SET
          sikayety_yorum_id=?,
          sikayety_konu_id=?,
          sikayety_gonderen=?,
          sikayety_mesaj=?,
          sikayety_konu_link=?");
          
          $SikayetGonder -> execute([
          $_YorumCek["y_id"],
          $_KonuCek["konu_id"],
          $_SESSION["uye_kadi"],
          $sikayet_y,
          $sikayet_y_konulink]);
        
        if ($SikayetGonder) {
          echo '<Sikayetiniz_Gonderildi><h2>Şikayetiniz Gönderildi!</h2></Sikayetiniz_Gonderildi>';
        header("REFRESH:1;URL=konu.php?link=".$sikayet_y_konulink); }

        else {
          echo '<Sikayetiniz_Gonderildi><h2>Şikayetiniz Gönderilirken Sıkıntı Oldu!</h2></Sikayetiniz_Gonderildi>';
        header("REFRESH:1;URL=konu.php?link=".$sikayet_y_konulink); } }

        ob_end_flush(); ?>
        </center>
      </div>
    </body>
    </html>