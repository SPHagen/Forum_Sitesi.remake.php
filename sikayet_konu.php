<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>..:: Konuyu Şikayet Et! ::..</title>
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

        $Konu_link = $_GET['link'];
        $Konu = $db -> prepare("SELECT * FROM konular WHERE konu_link=?");
        $Konu -> execute([$Konu_link]);
        $_Konu = $Konu -> fetch(PDO::FETCH_ASSOC);

        $KullaniciAdi = $db -> prepare("SELECT * FROM uyeler WHERE uye_id=?");
        $KullaniciAdi -> execute([$_SESSION["uye_id"]]);
        $_KullaniciAdi = $KullaniciAdi -> fetch(PDO::FETCH_ASSOC);

        ?>
        <div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <Kategori_ismi_konu_ac><strong>Şikayet Etmek İstediğiniz Konu</strong></Kategori_ismi_konu_ac>
            <br>
            <isimli_kategoride_konu_aciniz><strong><?= $_Konu['konu_ad']; ?></strong></isimli_kategoride_konu_aciniz>
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
            $Uye_ID = $_SESSION["uye_id"];
            $Konu_ID = $_Konu["konu_id"];
            $Konu_Adi = '<a href = "konu.php?link='.$_Konu['konu_link'].'">'.$_Konu['konu_ad'].'</a>';
            $Konu_Sahibi = '<a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id']).' </a>';
            echo "<div id = 'Şikayet_Bilgileri' ><br>Üye ID'niz: $Uye_ID<br><br>Konunun ID'si: $Konu_ID<br><br>
            Konunun Adı<br>$Konu_Adi<br><br>Konu Sahibi: $Konu_Sahibi</div><br><br>"; }
          else {
            echo 'Konuyu Şikayet Edebilmek İçin <a href="uyelik.php"><strong>Giriş Yapın</strong></a> veya <a href="uyelik.php?p=kayit"><strong>Kayıt Olun</strong></a>'; }
          ?>
          <form Action = "" Method = "Post" >
          <input Type = "Submit" id = "Şikayeti_Gönder" Value = "Şikayeti Gönder" >
          <input Type = "Reset" id = "Şikayeti_Temizle" Value = "Sayfayı Temizle" >
          <br><br>
          <textarea id = "Sikayet_Mesaji" name = "mesaj"
          onfocus = "if (this.value == 'Lütfen Şikayet Detaylarınızı Yazınız!') { this.value = ''; }" >Lütfen Şikayet Detaylarınızı Yazınız!</textarea> 
        </form>
        <?php
        if (!(empty($_POST['mesaj'])) && $_POST['mesaj'] !== 'Lütfen Şikayet Detaylarınızı Yazınız!' && $_POST) {
          $sikayet = $_POST["mesaj"];
          $sikayet_link = $_GET['link'];
          
          $dataAdd = $db -> prepare("INSERT INTO sikayetk SET
          sikayetk_konu_id=?,
          sikayetk_uye_id=?,
          sikayetk_gonderen=?,
          sikayetk_mesaj=?,
          sikayetk_konulink=?
          ");
          
          $dataAdd -> execute([
          $_Konu["konu_id"],
          $_SESSION["uye_id"],
          $_KullaniciAdi["uye_kadi"],
          $sikayet,
          $sikayet_link
          ]);

          if ($dataAdd) {
          echo '<Sikayetiniz_Gonderildi><h2>Şikayetiniz Gönderildi!</h2></Sikayetiniz_Gonderildi>';
          header("REFRESH:1;URL=konu.php?link=".$Konu_link); }

          else {
          echo 'Şikayetiniz gönderilirken bir hata oluştu.</p>';
          header("REFRESH:1;URL=konu.php?link=".$Konu_link); }
        }
        ob_end_flush(); ?>
        </center>
      </div>
    </body>
    </html>