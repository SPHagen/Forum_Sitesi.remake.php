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
    $yid = $_GET['yid'];
    $SikayetCek = $db -> prepare("SELECT * FROM sikayety WHERE sikayety_id=?");
    $SikayetCek -> execute([$yid]);
    $_SikayetCek = $SikayetCek -> fetch(PDO::FETCH_ASSOC);
    
    $KonuCek = $db -> prepare("SELECT * FROM konular WHERE konu_id=?");
    $KonuCek -> execute([$_SikayetCek["sikayety_konu_id"]]);
    $_KonuCek = $KonuCek -> fetch(PDO::FETCH_ASSOC);
    
    $YorumCek = $db -> prepare("SELECT * FROM yorumlar WHERE y_id=?");
    $YorumCek -> execute([$_SikayetCek["sikayety_yorum_id"]]);
    $_YorumCek = $YorumCek -> fetch(PDO::FETCH_ASSOC);
    ?>
    <title>..:: <?= $_SikayetCek['sikayety_mesaj']; ?> ::..</title>
    <link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
</head>
<body>
<div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <Kategoriler><strong><?= $_SikayetCek['sikayety_mesaj']; ?></strong></Kategoriler>
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
            <center><br><br>
                <?php
                echo '<div id = "Sikayet_Edilen_Konu" >Şikayet Edilen Yorum: '.$_YorumCek['y_yorum'].'</div><br><br>';
                echo '<div id = "Sikayeti_Gonderen_Kullanici" >Şikayeti Gönderen Kullanıcı: 
                <a href="profil.php?kadi='.$_SikayetCek['sikayety_gonderen'].'">'.$_SikayetCek['sikayety_gonderen'].'</a></div><br><br>';
                echo '<div id = "Sikayet_Tarihi" > Şikayet Tarihi: '.$_SikayetCek['sikayety_tarih'].'</div>';
                echo '<div id = "Sikayetk_Mesaji" > <Sikayet_Mesaji>Şikayet Mesajı</Sikayet_Mesaji><br><br><Sikayetk_Mesaji>'.$_SikayetCek['sikayety_mesaj'].'<Sikayetk_Mesaji></div>';
                ?>
            <form action = " " method = "post" >

<input type='submit' name = "submit_kaldir" id = "Konuyu_Tumuyle_Kaldirin" value = "Yorumu Tümüyle Kaldırın!" />

<?php if($_YorumCek["yorum_durum"] == 1) echo '<br><br><input type="submit" name = "submit_gecici_kaldir" id = "Konu_Buton" value = "Yorumu Geçici Kapatın!" />';
else if($_YorumCek["yorum_durum"] == 0) echo '<br><br><input type="submit" name = "submit_aktif_et" id = "Konu_Buton" value = "Yorumu Aktif Edin!" />'; ?>

</form>

<?php

if (isset($_POST["submit_kaldir"]))
{
    $onayla = $db -> prepare("UPDATE sikayety SET sikayety_onay=1 WHERE sikayety_yorum_id=?");
    $onayla -> execute([$yid]);
if ($onayla) {
    $sikayeti_kaldir = $db -> prepare("DELETE FROM sikayety WHERE sikayety_yorum_id=?");
    $sikayeti_kaldir -> execute([$_YorumCek["y_id"]]);
    $yorum_kaldir = $db -> prepare("DELETE FROM yorumlar WHERE y_id=?");
    $yorum_kaldir -> execute([$_YorumCek["y_id"]]);
    echo '<br><br><p class="alert alert-success">Yorum başarıyla kaldırıldı!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
else {
    echo '<br><br><p class="alert alert-danger">Yorum kaldırılamadı!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
}

else if (isset($_POST["submit_gecici_kaldir"])) {
    if ($_YorumCek["yorum_durum"] == 1) {
    $yorum_gizle = $db -> prepare("UPDATE yorumlar SET yorum_durum = 0 WHERE y_id=?");
    $yorum_gizle -> execute([$_SikayetCek["sikayety_yorum_id"]]);
    echo '<br><br><p class="alert alert-success">Yorum başarıyla gizlendi!!!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}

else {
    echo '<br><br><p class="alert alert-danger">Yorum gizlenemedi!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
}

else if (isset($_POST["submit_aktif_et"])) {
    if ($_YorumCek["yorum_durum"] == 0) {
    $yorum_aktif_et = $db -> prepare("UPDATE yorumlar SET yorum_durum = 1 WHERE y_id=?");
    $yorum_aktif_et -> execute([$_SikayetCek["sikayety_yorum_id"]]);
    echo '<br><br><p class="alert alert-success">Yorum başarıyla aktif edildi!!!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
else {
    echo '<br><br><p class="alert alert-danger">Yorum aktif edilemedi!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
} ?>
</div>
</center>
</body>
</html>