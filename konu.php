<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    session_start();
    ob_start();
    include 'ayar.php';
    include 'ukas.php';
    include 'fonksiyon.php';

    $link = $_GET['link'];
    $Konu = $db -> prepare("SELECT * FROM konular WHERE konu_link=?");
    $Konu -> execute([$link]);
    $_Konu = $Konu -> fetch(PDO::FETCH_ASSOC);

    ?>
    <title>..:: <?=$_Konu['konu_ad']; ?> ::..</title>
    <link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
</head>
<body>
    <div id = "Yapıcı" >
        <?php
        ?>
        <div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <br>
            <Kategoriler><strong><?=$_Konu['konu_ad']; ?></strong></Kategoriler>
            <?php
            if (@$_SESSION['uye_id']) {
                echo '<Profilime_git><a href = "profil.php?kadi='.@$_SESSION["uye_kadi"].'" ><font Color = "Beige" >Profilime Git</font></a></Profilime_git>
                <Cikis_yap><a href = "uyelik.php?p=cikis" ><font Color = "Beige" >Çıkış Yap</font></a></Cikis_yap>'; }
            else {
                echo '<Giris_yap><a href = "uyelik.php" ><font Color = "Beige" >Giriş Yap</font></a></Giris_yap>
                <Kayit_ol><a href = "uyelik.php?p=kayit" class = "KayitOl" ><font Color = "Beige" >Kayıt Ol</font></a></Kayit_ol>'; }
            ?>
        </div>
        <div id = "Merkez" >
          <?php

          $ks_Rep_Puani = $db -> prepare("SELECT *, uye_rep_puani as Rep_Puani FROM uyeler WHERE uye_id = ?");
          $ks_Rep_Puani -> execute([$_Konu['konu_uye_id']]);
          $_ks_Rep_Puani = $ks_Rep_Puani -> fetch(PDO::FETCH_ASSOC);

          $ks_Konu_Sayisi = $db -> prepare("SELECT count(konu_ad) as ks_Konu_Sayisi FROM konular WHERE konu_durum = 1 AND konu_uye_id=?");
          $ks_Konu_Sayisi -> execute([$_Konu["konu_uye_id"]]);
          $_ks_Konu_Sayisi = $ks_Konu_Sayisi -> fetch(PDO::FETCH_ASSOC);

          $KonuSahibiYorumSayisi = $db -> prepare("SELECT *, count(y_yorum) as konusahibi_yorumsayisi FROM yorumlar WHERE yorum_durum = 1 AND y_uye_id=?");
          $KonuSahibiYorumSayisi -> execute([$_Konu["konu_uye_id"]]);
          $_KonuSahibiYorumSayisi = $KonuSahibiYorumSayisi -> fetch(PDO::FETCH_ASSOC);

          if(@$_SESSION["uye_onay"] == 0 && $_Konu["konu_durum"] == 0) {
            echo '<center><h1><br>..:: '.$_Konu['konu_ad'].' ::..<br><br>İsimli Konu<br><br>Bir ŞİKAYET Üzerine Kapatılmıştır!</h1></center>';
            exit; }
          ?>
          <br><br>
          <center><div id = "Konunun_Tarihi_Div" ><?='<Konunun_Tarihi>'.$_Konu['konu_tarih'].'</Konunun_Tarihi>';
          ?></div></center>
          <center><div id = "ks_Konu_Açtı" ></center>
            <?php
            if ($_Konu['konu_durum'] == 1) {
            if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 0 && $_ks_Rep_Puani['Rep_Puani'] <= 9) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Yeni_Üye><Onaylı>Onaylı</Onaylı> Yeni Üye</Yeni_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] > 0 && $_ks_Rep_Puani['Rep_Puani'] <= 9) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Yeni_Üye>Yeni Üye</Yeni_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 10 && $_ks_Rep_Puani['Rep_Puani'] <= 19) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Deneyimli_Üye><Onaylı>Onaylı</Onaylı> Deneyimli Üye</Deneyimli_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 10 && $_ks_Rep_Puani['Rep_Puani'] <= 19) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Deneyimli_Üye>Deneyimli Üye</Deneyimli_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 20 && $_ks_Rep_Puani['Rep_Puani'] <= 29) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Caliskan_Üye><Onaylı>Onaylı</Onaylı> Çalışkan Üye</Caliskan_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 20 && $_ks_Rep_Puani['Rep_Puani'] <= 29) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Caliskan_Üye>Çalışkan Üye</Caliskan_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 30 && $_ks_Rep_Puani['Rep_Puani'] <= 39) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Basarili_Üye><Onaylı>Onaylı</Onaylı> Başarılı Üye</Basarili_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 30 && $_ks_Rep_Puani['Rep_Puani'] <= 39) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Basarili_Üye>Başarılı Üye</Basarili_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 40 && $_ks_Rep_Puani['Rep_Puani'] <= 49) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Emektar_Üye><Onaylı>Onaylı</Onaylı> Emektar Üye</Emektar_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 40 && $_ks_Rep_Puani['Rep_Puani'] <= 49) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Emektar_Üye>Emektar Üye</Emektar_Üye></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 50) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Efsane_Üye><Onaylı>Onaylı</Onaylı> Efsane Üye</Efsane_Üye></Arka_Plan></ks_Adı>'; }
            
            else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 50) {
              echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
              .' </a><Efsane_Üye>Efsane Üye</Efsane_Üye></ks_Adı>'; }

            echo '<div id = "ks_Konu_Mesajı" >'.$_Konu['konu_mesaj'].'</div>';
            echo '<ks_Hakkında><table id = "ks_Hakkında_Tablo" ><tr id = "ks_Hakkında_Tablo" ><td id = "ks_Hakkında_Tablo" ><center><br>Yorumları: '.$_KonuSahibiYorumSayisi['konusahibi_yorumsayisi']
            .'<br><br>Konu Sayısı: '.$_ks_Konu_Sayisi['ks_Konu_Sayisi']
            .'<br><br>'.$_ks_Rep_Puani['uyelik_tarihi']
            .'<br><br>Rep Puanı: '.$_ks_Rep_Puani['Rep_Puani'].'</tr></td></table></ks_Hakkında>';
            echo '<center><br><div id = "ksden_Sonrası" >';

            if (@$_SESSION['uye_id'] && @$_SESSION['uye_id'] !== $_Konu['konu_uye_id']) echo '<a id = "Konuyu_Şikayet_Et" href = "sikayet_konu.php?link='.$_Konu['konu_link'].'">Konuyu Şikayet Et!</a>';

            else if (@$_SESSION['uye_id'] && @$_SESSION['uye_id'] == $_Konu['konu_uye_id'] && $_Konu['k_cozulmus'] == 0 && $_Konu['k_cozulmemis'] == 1)
            echo '<a href="konuyu_kapat.php?kid='.$_Konu['konu_id'].'"><Konuyu_Kapat>Konuyu Kapat!</Konuyu_Kapat></a>';

            else if (@$_SESSION['uye_id'] && @$_SESSION['uye_id'] == $_Konu['konu_uye_id'] && $_Konu['k_cozulmus'] == 1 && $_Konu['k_cozulmemis'] == 0)
            echo '<Konu_Kapatildi>Konu Kapatıldı!</Konu_Kapatildi>';

            else if (!(@$_SESSION['uye_id'])) echo '<Giriş_Yapınız_veya_Kayıt_Olunuz><a href = "uyelik.php" id = "Link_Rengi_Değiştirme" >Giriş Yapınız</a> veya <a href = "uyelik.php?p=kayit" id = "Link_Rengi_Değiştirme" >Kayıt Olunuz</a></Giriş_Yapınız_veya_Kayıt_Olunuz>';

            echo '</div></center>';

            $Yorumlar_Cek = $db -> prepare("SELECT * FROM yorumlar WHERE y_konu_id=? ORDER BY y_id DESC");
            $Yorumlar_Cek -> execute([$_Konu["konu_id"]]);
            $_Yorumlar_Cek = $Yorumlar_Cek -> fetchALL(PDO::FETCH_ASSOC);
            
            ?>
            <?php
            foreach($_Yorumlar_Cek as $__Yorumlar_Cek) {

              $Uye_Yorum_Sayisi_Cek = $db -> prepare("SELECT COUNT(y_yorum) as yorum_sayisi FROM yorumlar WHERE yorum_durum = 1 AND y_uye_id=?");
              $Uye_Yorum_Sayisi_Cek -> execute([$__Yorumlar_Cek["y_uye_id"]]);
              $_Uye_Yorum_Sayisi_Cek = $Uye_Yorum_Sayisi_Cek -> fetch(PDO::FETCH_ASSOC);

              $Uye_Konu_Sayisi_Cek = $db -> prepare("SELECT count(konu_mesaj) as konu_sayisi FROM konular WHERE konu_durum = 1 AND konu_uye_id=?");
              $Uye_Konu_Sayisi_Cek -> execute([$__Yorumlar_Cek["y_uye_id"]]);
              $_Uye_Konu_Sayisi_Cek = $Uye_Konu_Sayisi_Cek -> fetch(PDO::FETCH_ASSOC);

              $Uye_Bilgileri_Cek = $db -> prepare("SELECT * FROM uyeler WHERE uye_id = ?");
              $Uye_Bilgileri_Cek -> execute([$__Yorumlar_Cek["y_uye_id"]]);
              $_Uye_Bilgileri_Cek = $Uye_Bilgileri_Cek -> fetch(PDO::FETCH_ASSOC);

              if ($__Yorumlar_Cek['yorum_durum'] == 1) {
                echo '<center><div id = "uye_Yorumun_Tarihi_Div" ><uye_Yorumun_Tarihi>
                '.$__Yorumlar_Cek['y_tarih'].'</uye_Yorumun_Tarihi></div></center>';

                if ($__Yorumlar_Cek['y_id'] % 2 == 1) {
                  echo '<div id = "Üye_Yorum_Yazdı" >';
                  echo '<Konu_Yorumları><table id = "Yorum_Hakkında_Tablo" ><tr id = "Yorum_Hakkında_Tablo" ><td id = "Yorum_Hakkında_Tablo" ><center><br>Yorumları: '.$_Uye_Yorum_Sayisi_Cek['yorum_sayisi']
                  .'<br><br>Konu Sayısı: '.$_Uye_Konu_Sayisi_Cek['konu_sayisi']
                  .'<br><br>'.$_Uye_Bilgileri_Cek['uyelik_tarihi']
                  .'<br><br>Rep Puanı: '.$_Uye_Bilgileri_Cek['uye_rep_puani'].'</tr></td></table></Konu_Yorumları>'; }

                else {
                  echo '<div id = "Üye_Yorum_Yazdı_Çift" >';
                  echo '<Konu_Yorumları><table id = "Yorum_Hakkında_Tablo_Çift" ><tr id = "Yorum_Hakkında_Tablo_Çift" ><td id = "Yorum_Hakkında_Tablo_Çift" ><center><br>Yorumları: '.$_Uye_Yorum_Sayisi_Cek['yorum_sayisi']
                  .'<br><br>Konu Sayısı: '.$_Uye_Konu_Sayisi_Cek['konu_sayisi']
                  .'<br><br>'.$_Uye_Bilgileri_Cek['uyelik_tarihi']
                  .'<br><br>Rep Puanı: '.$_Uye_Bilgileri_Cek['uye_rep_puani'].'</tr></td></table></Konu_Yorumları>';
                }

                if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 0 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 9) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Yeni_Üye><Onaylı>Onaylı</Onaylı> Yeni Üye</Yeni_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 0 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 9) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Yeni_Üye>Yeni Üye</Yeni_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 10 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 19) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Deneyimli_Üye><Onaylı>Onaylı</Onaylı> Deneyimli Üye</Deneyimli_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 10 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 19) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Deneyimli_Üye>Deneyimli Üye</Deneyimli_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 20 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 29) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Caliskan_Üye><Onaylı>Onaylı</Onaylı> Çalışkan Üye</Caliskan_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 20 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 29) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Caliskan_Üye>Çalışkan Üye<Caliskan_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 30 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 39) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Basarili_Üye><Onaylı>Onaylı</Onaylı> Başarılı Üye</Basarili_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 30 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 39) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Basarili_Üye>Başarılı Üye</Basarili_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 40 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 49) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Emektar_Üye><Onaylı>Onaylı</Onaylı> Emektar Üye</Emektar_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 40 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 49) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Emektar_Üye>Emektar Üye</Emektar_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 50) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Efsane_Üye><Onaylı>Onaylı</Onaylı> Efsane Üye</Efsane_Üye></y_uye>'; }
                
                else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 50) {
                  echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                  .' </a><Efsane_Üye>Efsane Üye</Efsane_Üye></y_uye>'; }
                  echo '<div id = "Yorum_Divi" >'.$__Yorumlar_Cek['y_yorum'].'</div>';
                  echo '</div>';
                if (@$_SESSION['uye_id'])
                echo '<center><div id = "uye_yden_Sonrası_Çift" ><a id = "Yorumu_Şikayet_Et" href = "sikayet.php?yid='.$__Yorumlar_Cek['y_id'].'">
                Yorumu Şikayet Et!</a></div></center><br><br>';
                
                else if (!(@$_SESSION['uye_id']))
                echo '<center><div id = "uye_yden_Sonrası_Çift" ><Giriş_Yapınız_veya_Kayıt_Olunuz_Yorum><a href = "uyelik.php" id = "Link_Rengi_Değiştirme" >Giriş Yapınız</a> veya <a href = "uyelik.php?p=kayit" id = "Link_Rengi_Değiştirme" >Kayıt Olunuz</a></Giriş_Yapınız_veya_Kayıt_Olunuz_Yorum></div></center><br><br>'; }

                else if ($__Yorumlar_Cek['yorum_durum'] == 0) {
                    echo '<center><div id = "uye_Yorumun_Tarihi_Div" ><uye_Yorumun_Tarihi>
                    '.$__Yorumlar_Cek['y_tarih'].'</uye_Yorumun_Tarihi></div></center>';
  
                    if ($__Yorumlar_Cek['y_id'] % 2 == 1) {
                      echo '<div id = "Üye_Yorum_Yazdı" >';
                      echo '<Konu_Yorumları><table id = "Yorum_Hakkında_Tablo" ><tr id = "Yorum_Hakkında_Tablo" ><td id = "Yorum_Hakkında_Tablo" ><center><br>Yorumları: '.$_Uye_Yorum_Sayisi_Cek['yorum_sayisi']
                      .'<br><br>Konu Sayısı: '.$_Uye_Konu_Sayisi_Cek['konu_sayisi']
                      .'<br><br>'.$_Uye_Bilgileri_Cek['uyelik_tarihi']
                      .'<br><br>Rep Puanı: '.$_Uye_Bilgileri_Cek['uye_rep_puani'].'</tr></td></table></Konu_Yorumları>'; }
    
                    else {
                      echo '<div id = "Üye_Yorum_Yazdı_Çift" >';
                      echo '<Konu_Yorumları><table id = "Yorum_Hakkında_Tablo_Çift" ><tr id = "Yorum_Hakkında_Tablo_Çift" ><td id = "Yorum_Hakkında_Tablo_Çift" ><center><br>Yorumları: '.$_Uye_Yorum_Sayisi_Cek['yorum_sayisi']
                      .'<br><br>Konu Sayısı: '.$_Uye_Konu_Sayisi_Cek['konu_sayisi']
                      .'<br><br>'.$_Uye_Bilgileri_Cek['uyelik_tarihi']
                      .'<br><br>Rep Puanı: '.$_Uye_Bilgileri_Cek['uye_rep_puani'].'</tr></td></table></Konu_Yorumları>';
                    }

                    if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 0 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 9) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Yeni_Üye><Onaylı>Onaylı</Onaylı> Yeni Üye</Yeni_Üye></y_uye>'; }
                    
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 0 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 9) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Yeni_Üye>Yeni Üye</Yeni_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 10 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 19) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Deneyimli_Üye><Onaylı>Onaylı</Onaylı> Deneyimli Üye</Deneyimli_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 10 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 19) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Deneyimli_Üye>Deneyimli Üye</Deneyimli_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 20 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 29) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Caliskan_Üye><Onaylı>Onaylı</Onaylı> Çalışkan Üye</Caliskan_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 20 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 29) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Caliskan_Üye>Çalışkan Üye<Caliskan_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 30 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 39) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Basarili_Üye><Onaylı>Onaylı</Onaylı> Başarılı Üye</Basarili_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 30 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 39) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Basarili_Üye>Başarılı Üye</Basarili_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 40 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 49) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Emektar_Üye><Onaylı>Onaylı</Onaylı> Emektar Üye</Emektar_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 40 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 49) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Emektar_Üye>Emektar Üye</Emektar_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 50) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Efsane_Üye><Onaylı>Onaylı</Onaylı> Efsane Üye</Efsane_Üye></y_uye>'; }
  
                    else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 50) {
                      echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                      .' </a><Efsane_Üye>Efsane Üye</Efsane_Üye></y_uye>'; }
                      if (@$_SESSION['uye_onay'] == 1) echo '<div id = "Yorum_Divi" ><font Color = "Green" ><strong>Onayınız Var! Kaldırılan Yorumu Görebilirsiniz!</strong></font><br><br>'.$__Yorumlar_Cek['y_yorum'].'</div>';
                      else echo '<div id = "Yorum_Divi" ><font Color = "Red" ><strong>Bu Yorum, Bir Şikayet Sebebiyle Kaldırılmıştır!</strong></font></div>';
                      echo '</div>';
                    echo '<center><div id = "uye_yden_Sonrası_Çift" ></div></center><br><br>'; } }
                if (@$_SESSION["uye_id"]) {
                  if ($_POST && !empty($_POST['Yorum'])) {
                    $yorum = $_POST['Yorum'];
                    $dataAdd = $db -> prepare("INSERT INTO yorumlar SET
                    y_uye_id=?,
                    y_konu_id=?,
                    y_yorum=?");
                    $dataAdd -> execute([
                      $_SESSION["uye_id"],
                      $_Konu["konu_id"],
                      $yorum]);
                      if ($dataAdd) {
                        $yorumcek = $db -> prepare("SELECT * FROM yorumlar WHERE y_uye_id=? && y_konu_id=?");
                        $yorumcek -> execute([
                          $_SESSION["uye_id"],
                          $_Konu["konu_id"]]);
                          $yorum_cek = $yorumcek -> fetch(PDO::FETCH_ASSOC);
                          header("REFRESH:1;URL=konu.php?link=" . $link); 
                        }
                      else {
                        header("REFRESH:1;URL=konu.php?link=" . $link); } }
                  
                if ($_Konu['k_cozulmemis'] == 1 && $_Konu['k_cozulmus'] == 0) {
                  echo '<form Action = "" Method = "Post" >
                  <div id = "_Yorum" >
                  <textarea name = "Yorum" id = "Yorumu_Yayınla" ></textarea>
                  <input type  = "Submit" id = "Yorumu_Yayınla_Buton" Value = "Yorumu Yayınla" />
                  <input type  = "Reset" id = "Yorumu_Temizle_Buton" Value = "Yorumu Temizle" />
                  </div></form>'; }
                else if ($_Konu['k_cozulmemis'] == 0 && $_Konu['k_cozulmus'] == 1) {
                echo '<div id = "Yorum_Yapamiyorsunuz">';
                echo '<center>Çözülmüş Konuya Yorum Yapamazsınız!</div></center>'; }
                
                else {
                  echo '<div id = "Yorum_Yapamiyorsunuz" >
                  <center>Konuya Yorum Yapabilmek için <a href="uyelik.php">Giriş Yapınız</a> 
                  veya
                  <a href="uyelik.php?p=kayit">Kayıt Olunuz</a></center></div>'; } } }
                
                  else if ($_Konu['konu_durum'] == 0 && @$_SESSION['uye_onay'] == 1) {

                    if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 0 && $_ks_Rep_Puani['Rep_Puani'] <= 9) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Yeni_Üye><Onaylı>Onaylı</Onaylı> Yeni Üye</Yeni_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] > 0 && $_ks_Rep_Puani['Rep_Puani'] <= 9) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Yeni_Üye>Yeni Üye</Yeni_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 10 && $_ks_Rep_Puani['Rep_Puani'] <= 19) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Deneyimli_Üye><Onaylı>Onaylı</Onaylı> Deneyimli Üye</Deneyimli_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 10 && $_ks_Rep_Puani['Rep_Puani'] <= 19) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Deneyimli_Üye>Deneyimli Üye</Deneyimli_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 20 && $_ks_Rep_Puani['Rep_Puani'] <= 29) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Caliskan_Üye><Onaylı>Onaylı</Onaylı> Çalışkan Üye</Caliskan_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 20 && $_ks_Rep_Puani['Rep_Puani'] <= 29) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Caliskan_Üye>Çalışkan Üye</Caliskan_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 30 && $_ks_Rep_Puani['Rep_Puani'] <= 39) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Basarili_Üye><Onaylı>Onaylı</Onaylı> Başarılı Üye</Basarili_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 30 && $_ks_Rep_Puani['Rep_Puani'] <= 39) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Basarili_Üye>Başarılı Üye</Basarili_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 40 && $_ks_Rep_Puani['Rep_Puani'] <= 49) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Emektar_Üye><Onaylı>Onaylı</Onaylı> Emektar Üye</Emektar_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 40 && $_ks_Rep_Puani['Rep_Puani'] <= 49) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Emektar_Üye>Emektar Üye</Emektar_Üye></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) == 1 && $_ks_Rep_Puani['Rep_Puani'] >= 50) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Efsane_Üye><Onaylı>Onaylı</Onaylı> Efsane Üye</Efsane_Üye></Arka_Plan></ks_Adı>'; }
                    
                    else if (uye_ID_to_onay($_ks_Rep_Puani['uye_id']) !== 1 && $_ks_Rep_Puani['Rep_Puani'] >= 50) {
                      echo '<ks_Adı><a href = "profil.php?kadi='.uye_ID_to_kadi($_Konu['konu_uye_id']).'">'.uye_ID_to_kadi($_Konu['konu_uye_id'])
                      .' </a><Efsane_Üye>Efsane Üye</Efsane_Üye></ks_Adı>'; }
        
                    echo '<div id = "ks_Konu_Mesajı" >'.$_Konu['konu_mesaj'].'</div>';
                    echo '<ks_Hakkında><table id = "ks_Hakkında_Tablo" ><tr id = "ks_Hakkında_Tablo" ><td id = "ks_Hakkında_Tablo" ><center><br>Yorumları: '.$_KonuSahibiYorumSayisi['konusahibi_yorumsayisi']
                    .'<br><br>Konu Sayısı: '.$_ks_Konu_Sayisi['ks_Konu_Sayisi']
                    .'<br><br>'.$_ks_Rep_Puani['uyelik_tarihi']
                    .'<br><br>Rep Puanı: '.$_ks_Rep_Puani['Rep_Puani'].'</tr></td></table></ks_Hakkında>';
                    echo '<center><br><div id = "ksden_Sonrası" >';
                    if (@$_SESSION['uye_id'] && @$_SESSION['uye_id'] !== $_Konu['konu_uye_id']) echo '<a id = "Konuyu_Şikayet_Et" href = "sikayet_konu.php?link='.$_Konu['konu_link'].'">Konuyu Şikayet Et!</a>';
                    
                    else if (@$_SESSION['uye_id'] && @$_SESSION['uye_id'] == $_Konu['konu_uye_id'] && $_Konu['k_cozulmus'] == 0 && $_Konu['k_cozulmemis'] == 1)
                    echo '<a href="konuyu_kapat.php?kid='.$_Konu['konu_id'].'"><Konuyu_Kapat>Konuyu Kapat!</Konuyu_Kapat></a>';
                    
                    else if (@$_SESSION['uye_id'] && @$_SESSION['uye_id'] == $_Konu['konu_uye_id'] && $_Konu['k_cozulmus'] == 1 && $_Konu['k_cozulmemis'] == 0)
                    echo '<Konu_Kapatildi>Konu Kapatıldı!</Konu_Kapatildi>';
                    
                    else if (!(@$_SESSION['uye_id'])) echo '<Giriş_Yapınız_veya_Kayıt_Olunuz><a href = "uyelik.php" id = "Link_Rengi_Değiştirme" >Giriş Yapınız</a> veya <a href = "uyelik.php?p=kayit" id = "Link_Rengi_Değiştirme" >Kayıt Olunuz</a></Giriş_Yapınız_veya_Kayıt_Olunuz>';
        
                    echo '</div></center>';
                    
                    $Yorumlar_Cek = $db -> prepare("SELECT * FROM yorumlar WHERE y_konu_id=? ORDER BY y_id DESC");
                    $Yorumlar_Cek -> execute([$_Konu["konu_id"]]);
                    $_Yorumlar_Cek = $Yorumlar_Cek -> fetchALL(PDO::FETCH_ASSOC);
                    
                    ?>
                    <?php
                    foreach($_Yorumlar_Cek as $__Yorumlar_Cek) {
        
                      $Uye_Yorum_Sayisi_Cek = $db -> prepare("SELECT COUNT(y_yorum) as yorum_sayisi FROM yorumlar WHERE yorum_durum = 1 AND y_uye_id=?");
                      $Uye_Yorum_Sayisi_Cek -> execute([$__Yorumlar_Cek["y_uye_id"]]);
                      $_Uye_Yorum_Sayisi_Cek = $Uye_Yorum_Sayisi_Cek -> fetch(PDO::FETCH_ASSOC);
        
                      $Uye_Konu_Sayisi_Cek = $db -> prepare("SELECT count(konu_mesaj) as konu_sayisi FROM konular WHERE konu_durum = 1 AND konu_uye_id=?");
                      $Uye_Konu_Sayisi_Cek -> execute([$__Yorumlar_Cek["y_uye_id"]]);
                      $_Uye_Konu_Sayisi_Cek = $Uye_Konu_Sayisi_Cek -> fetch(PDO::FETCH_ASSOC);
        
                      $Uye_Bilgileri_Cek = $db -> prepare("SELECT * FROM uyeler WHERE uye_id=?");
                      $Uye_Bilgileri_Cek -> execute([$__Yorumlar_Cek["y_uye_id"]]);
                      $_Uye_Bilgileri_Cek = $Uye_Bilgileri_Cek -> fetch(PDO::FETCH_ASSOC);
        
                      if ($__Yorumlar_Cek['yorum_durum'] == 1) {
                        echo '<center><div id = "uye_Yorumun_Tarihi_Div" ><uye_Yorumun_Tarihi>
                        '.$__Yorumlar_Cek['y_tarih'].'</uye_Yorumun_Tarihi></div></center>';
        
                        if ($__Yorumlar_Cek['y_id'] % 2 == 1) {
                          echo '<div id = "Üye_Yorum_Yazdı" >';
                          echo '<Konu_Yorumları><table id = "Yorum_Hakkında_Tablo" ><tr id = "Yorum_Hakkında_Tablo" ><td id = "Yorum_Hakkında_Tablo" ><center><br>Yorumları: '.$_Uye_Yorum_Sayisi_Cek['yorum_sayisi']
                          .'<br><br>Konu Sayısı: '.$_Uye_Konu_Sayisi_Cek['konu_sayisi']
                          .'<br><br>'.$_Uye_Bilgileri_Cek['uyelik_tarihi']
                          .'<br><br>Rep Puanı: '.$_Uye_Bilgileri_Cek['uye_rep_puani'].'</tr></td></table></Konu_Yorumları>'; }
        
                        else {
                          echo '<div id = "Üye_Yorum_Yazdı_Çift" >';
                          echo '<Konu_Yorumları><table id = "Yorum_Hakkında_Tablo_Çift" ><tr id = "Yorum_Hakkında_Tablo_Çift" ><td id = "Yorum_Hakkında_Tablo_Çift" ><center><br>Yorumları: '.$_Uye_Yorum_Sayisi_Cek['yorum_sayisi']
                          .'<br><br>Konu Sayısı: '.$_Uye_Konu_Sayisi_Cek['konu_sayisi']
                          .'<br><br>'.$_Uye_Bilgileri_Cek['uyelik_tarihi']
                          .'<br><br>Rep Puanı: '.$_Uye_Bilgileri_Cek['uye_rep_puani'].'</tr></td></table></Konu_Yorumları>';
                        }
        
                        if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 0 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 9) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Yeni_Üye><Onaylı>Onaylı</Onaylı> Yeni Üye</Yeni_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 0 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 9) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Yeni_Üye>Yeni Üye</Yeni_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 10 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 19) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Deneyimli_Üye><Onaylı>Onaylı</Onaylı> Deneyimli Üye</Deneyimli_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 10 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 19) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Deneyimli_Üye>Deneyimli Üye</Deneyimli_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 20 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 29) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Caliskan_Üye><Onaylı>Onaylı</Onaylı> Çalışkan Üye</Caliskan_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 20 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 29) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Caliskan_Üye>Çalışkan Üye<Caliskan_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 30 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 39) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Basarili_Üye><Onaylı>Onaylı</Onaylı> Başarılı Üye</Basarili_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 30 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 39) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Basarili_Üye>Başarılı Üye</Basarili_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 40 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 49) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Emektar_Üye><Onaylı>Onaylı</Onaylı> Emektar Üye</Emektar_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 40 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 49) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Emektar_Üye>Emektar Üye</Emektar_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 50) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Efsane_Üye><Onaylı>Onaylı</Onaylı> Efsane Üye</Efsane_Üye></y_uye>'; }
                        
                        else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 50) {
                          echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                          .' </a><Efsane_Üye>Efsane Üye</Efsane_Üye></y_uye>'; }
                          echo '<div id = "Yorum_Divi" >'.$__Yorumlar_Cek['y_yorum'].'</div>';
                          echo '</div>';

                          if (@$_SESSION['uye_id'])
                          echo '<center><div id = "uye_yden_Sonrası_Çift" ><a id = "Yorumu_Şikayet_Et" href = "sikayet.php?yid='.$__Yorumlar_Cek['y_id'].'">
                          Yorumu Şikayet Et!</a></div></center><br><br>';
                          
                          else if (!(@$_SESSION['uye_id']))
                          echo '<center><div id = "uye_yden_Sonrası_Çift" ><Giriş_Yapınız_veya_Kayıt_Olunuz_Yorum><a href = "uyelik.php" id = "Link_Rengi_Değiştirme" >Giriş Yapınız</a> veya <a href = "uyelik.php?p=kayit" id = "Link_Rengi_Değiştirme" >Kayıt Olunuz</a></Giriş_Yapınız_veya_Kayıt_Olunuz_Yorum></div></center><br><br>'; }
        
                        else if ($__Yorumlar_Cek['yorum_durum'] == 0) {
                            echo '<center><div id = "uye_Yorumun_Tarihi_Div" ><uye_Yorumun_Tarihi>
                            '.$__Yorumlar_Cek['y_tarih'].'</uye_Yorumun_Tarihi></div></center>';
          
                            if ($__Yorumlar_Cek['y_id'] % 2 == 1) {
                              echo '<div id = "Üye_Yorum_Yazdı" >';
                              echo '<Konu_Yorumları><table id = "Yorum_Hakkında_Tablo" ><tr id = "Yorum_Hakkında_Tablo" ><td id = "Yorum_Hakkında_Tablo" ><center><br>Yorumları: '.$_Uye_Yorum_Sayisi_Cek['yorum_sayisi']
                              .'<br><br>Konu Sayısı: '.$_Uye_Konu_Sayisi_Cek['konu_sayisi']
                              .'<br><br>'.$_Uye_Bilgileri_Cek['uyelik_tarihi']
                              .'<br><br>Rep Puanı: '.$_Uye_Bilgileri_Cek['uye_rep_puani'].'</tr></td></table></Konu_Yorumları>'; }
            
                            else {
                              echo '<div id = "Üye_Yorum_Yazdı_Çift" >';
                              echo '<Konu_Yorumları><table id = "Yorum_Hakkında_Tablo_Çift" ><tr id = "Yorum_Hakkında_Tablo_Çift" ><td id = "Yorum_Hakkında_Tablo_Çift" ><center><br>Yorumları: '.$_Uye_Yorum_Sayisi_Cek['yorum_sayisi']
                              .'<br><br>Konu Sayısı: '.$_Uye_Konu_Sayisi_Cek['konu_sayisi']
                              .'<br><br>'.$_Uye_Bilgileri_Cek['uyelik_tarihi']
                              .'<br><br>Rep Puanı: '.$_Uye_Bilgileri_Cek['uye_rep_puani'].'</tr></td></table></Konu_Yorumları>';
                            }
        
                            if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 0 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 9) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Yeni_Üye><Onaylı>Onaylı</Onaylı> Yeni Üye</Yeni_Üye></y_uye>'; }
                            
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 0 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 9) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Yeni_Üye>Yeni Üye</Yeni_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 10 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 19) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Deneyimli_Üye><Onaylı>Onaylı</Onaylı> Deneyimli Üye</Deneyimli_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 10 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 19) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Deneyimli_Üye>Deneyimli Üye</Deneyimli_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 20 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 29) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Caliskan_Üye><Onaylı>Onaylı</Onaylı> Çalışkan Üye</Caliskan_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 20 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 29) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Caliskan_Üye>Çalışkan Üye<Caliskan_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 30 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 39) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Basarili_Üye><Onaylı>Onaylı</Onaylı> Başarılı Üye</Basarili_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 30 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 39) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Basarili_Üye>Başarılı Üye</Basarili_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 40 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 49) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Emektar_Üye><Onaylı>Onaylı</Onaylı> Emektar Üye</Emektar_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 40 && $_Uye_Bilgileri_Cek['uye_rep_puani'] <= 49) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Emektar_Üye>Emektar Üye</Emektar_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) == 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 50) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Efsane_Üye><Onaylı>Onaylı</Onaylı> Efsane Üye</Efsane_Üye></y_uye>'; }
          
                            else if (uye_ID_to_onay($__Yorumlar_Cek['y_uye_id']) !== 1 && $_Uye_Bilgileri_Cek['uye_rep_puani'] >= 50) {
                              echo '<y_uye><a href = "profil.php?kadi='.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id']).'">'.uye_ID_to_kadi($__Yorumlar_Cek['y_uye_id'])
                              .' </a><Efsane_Üye>Efsane Üye</Efsane_Üye></y_uye>'; }
                              if (@$_SESSION['uye_onay'] == 1) echo '<div id = "Yorum_Divi" ><font Color = "Green" ><strong>Onayınız Var! Kaldırılan Yorumu Görebilirsiniz!</strong></font><br><br>'.$__Yorumlar_Cek['y_yorum'].'</div>';
                              else echo '<div id = "Yorum_Divi" ><font Color = "Red" ><strong>Bu Yorum, Bir Şikayet Sebebiyle Kaldırılmıştır!</strong></font></div>';
                              echo '</div>';
                              echo '<center><div id = "uye_yden_Sonrası_Çift" ></div></center><br><br>'; } }
                        if (@$_SESSION["uye_id"]) {
                          if ($_POST && !empty($_POST['Yorum'])) {
                            $yorum = $_POST['Yorum'];
                            $dataAdd = $db -> prepare("INSERT INTO yorumlar SET
                            y_uye_id=?,
                            y_konu_id=?,
                            y_yorum=?");
                            $dataAdd -> execute([
                              $_SESSION["uye_id"],
                              $_Konu["konu_id"],
                              $yorum]);
                              if ($dataAdd) {
                                $yorumcek = $db -> prepare("SELECT * FROM yorumlar WHERE y_uye_id=? && y_konu_id=?");
                                $yorumcek -> execute([
                                  $_SESSION["uye_id"],
                                  $_Konu["konu_id"]]);
                                  $yorum_cek = $yorumcek -> fetch(PDO::FETCH_ASSOC);
                                  header("REFRESH:1;URL=konu.php?link=" . $link); 
                                }
                              else {
                               header("REFRESH:1;URL=konu.php?link=" . $link); } }
                          
                        if ($_Konu['k_cozulmemis'] == 1 && $_Konu['k_cozulmus'] == 0) {
                          echo '<form Action = "" Method = "Post" >
                          <div id = "_Yorum" >
                          <textarea name = "Yorum" id = "Yorumu_Yayınla" ></textarea>
                          <input type  = "Submit" id = "Yorumu_Yayınla_Buton" Value = "Yorumu Yayınla" />
                          <input type  = "Reset" id = "Yorumu_Temizle_Buton" Value = "Yorumu Temizle" />
                          </div></form>'; }
                        else if ($_Konu['k_cozulmemis'] == 0 && $_Konu['k_cozulmus'] == 1) {
                        echo '<div id = "Yorum_Yapamiyorsunuz">';
                        echo '<center>Çözülmüş Konuya Yorum Yapamazsınız!</div></center>'; }
                        else {
                          echo '<div id = "Yorum_Yapamiyorsunuz" >
                          <center>Konuya Yorum Yapabilmek için <a href="uyelik.php">Giriş Yapınız</a> 
                          veya
                          <a href="uyelik.php?p=kayit">Kayıt Olunuz</a></center></div>'; } } }
                  ob_end_flush(); ?>
                  </div>
                </div>
              </body>
              </html>