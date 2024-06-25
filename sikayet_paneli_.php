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
    $link = @$_GET["link"];
    $Konu = $db -> prepare("SELECT * FROM konular WHERE konu_link=?");
    $Konu -> execute([$link]);
    $_Konu = $Konu -> fetch(PDO::FETCH_ASSOC);
    
    $data = $db -> prepare("SELECT * FROM sikayetk WHERE sikayetk_konulink=?");
    $data -> execute([$link]);
    $_data = $data -> fetch(PDO::FETCH_ASSOC);
    ?>
    <title>..:: <?= $_data['sikayetk_mesaj']; ?> ::..</title>
    <link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
</head>
<body>
<div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <Kategoriler><strong><?= $_data['sikayetk_mesaj']; ?></strong></Kategoriler>
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
                echo '<div id = "Sikayet_Edilen_Konu" >Şikayet Edilen Konu: '.$_Konu['konu_ad'].'</div><br><br>';
                echo '<div id = "Sikayeti_Gonderen_Kullanici" >Şikayeti Gönderen Kullanıcı: 
                <a href="profil.php?kadi='.$_data["sikayetk_gonderen"].'">'.$_data["sikayetk_gonderen"].'</a></div><br><br>';
                echo '<div id = "Sikayet_Tarihi" > Şikayet Tarihi: '.$_data['sikayetk_tarih'].'</div>';
                echo '<div id = "Sikayetk_Mesaji" > <Sikayet_Mesaji>Şikayet Mesajı</Sikayet_Mesaji><br><br><Sikayetk_Mesaji>'.$_data['sikayetk_mesaj'].'<Sikayetk_Mesaji></div>';
                ?>
            <form action = " " method = "post" >

<input type='submit' name = "submit_kaldir" id = "Konuyu_Tumuyle_Kaldirin" value = "Konuyu Tümüyle Kaldırın!" />

<?php if($_Konu["konu_durum"] == 1) echo '<br><br><input type="submit" name = "submit_gecici_kaldir" id = "Konu_Buton" value = "Konuyu Geçici Kapatın!" />';
else if($_Konu["konu_durum"] == 0) echo '<br><br><input type="submit" name = "submit_aktif_et" id = "Konu_Buton" value = "Konuyu Aktif Edin!" />'; ?>

</form>

<?php

if (isset($_POST["submit_kaldir"]))
{
    $onayla = $db -> prepare("UPDATE sikayetk SET sikayetk_onay=1 WHERE sikayetk_konu_id=?");
    $onayla -> execute([$_Konu["konu_id"]]);
if ($onayla) {
    $sikayeti_kaldir = $db -> prepare("DELETE FROM sikayetk WHERE sikayetk_konu_id=?");
    $sikayeti_kaldir -> execute([$_Konu["konu_id"]]);
    $konuyu_kaldir = $db -> prepare("DELETE FROM konular WHERE konu_link=?");
    $konuyu_kaldir -> execute([$_Konu["konu_link"]]);
    $yorum_kaldir = $db -> prepare("DELETE FROM yorumlar WHERE y_konu_id=?");
    $yorum_kaldir -> execute([$_Konu["konu_id"]]);
    echo '<br><br><p class="alert alert-success">Konu başarıyla kaldırıldı!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
else {
    echo '<br><br><p class="alert alert-danger">Konu kaldırılamadı!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
}

else if (isset($_POST["submit_gecici_kaldir"])) { 

    if ($_Konu["konu_durum"] == 1)
{
    $uye_yorum_sayisi_gizle = $db -> prepare("UPDATE yorumlar SET yorum_durum = 0 WHERE y_konu_id=?");
    $uye_yorum_sayisi_gizle -> execute([$_data["sikayetk_konu_id"]]);
    $gecici_ = $db -> prepare("UPDATE konular SET konu_durum = 0 WHERE konu_id=?");
    $gecici_ -> execute([$_data["sikayetk_konu_id"]]);
    echo '<br><br><p class="alert alert-success">Konu başarıyla gizlendi!!!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
else {
    echo '<br><br><p class="alert alert-danger">Konu gizlenirken sıkıntı oldu!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
}

else if (isset($_POST["submit_aktif_et"])) { 

    if ($_Konu["konu_durum"] == 0)
{
    $uye_yorum_sayisi_gizle = $db -> prepare("UPDATE yorumlar SET yorum_durum = 1 WHERE y_konu_id=?");
    $uye_yorum_sayisi_gizle -> execute([$_data["sikayetk_konu_id"]]);
    $aktif_ = $db -> prepare("UPDATE konular SET konu_durum = 1 WHERE konu_id=?");
    $aktif_ -> execute([$_data["sikayetk_konu_id"]]);
    echo '<br><br><p class="alert alert-success">Konu tekrar aktif edildi!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
else {
    echo '<br><br><p class="alert alert-danger">Konu aktif edilirken sıkıntı oldu!</p>';
    header("REFRESH:1;URL=sikayet_paneli.php");
}
} ?>
</div>
</center>
</body>
</html>