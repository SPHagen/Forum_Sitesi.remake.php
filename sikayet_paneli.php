<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>..:: Şikayet Paneli ::..</title>
    <link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
</head>
<body>
<div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <Kategoriler><strong><?= 'Şikayet Paneli'; ?></strong></Kategoriler>
            <?php
            session_start();
            ob_start();
            include 'ayar.php';
            include 'ukas.php';
            include 'fonksiyon.php';
            if ($_SESSION['uye_id']) {
                echo '<Profilime_git><a href = "profil.php?kadi='.@$_SESSION["uye_kadi"].'" ><font color = "Beige" >Profilime Git</font></a></Profilime_git>
                <Cikis_yap><a href = "uyelik.php?p=cikis" ><font color = "Beige" >Çıkış Yap</font></a></Cikis_yap>'; }
            else {
                echo '<Giris_yap><a href = "uyelik.php" ><font color = "Beige" >Giriş Yap</font></a></Giris_yap>
                <Kayit_ol><a href = "uyelik.php?p=kayit" class = "KayitOl" ><font color = "Beige" >Kayıt Ol</font></a></Kayit_ol>'; }
            ?>
        </div>
        <div id = "Merkez" >
        <center>

<?php
if(@$_SESSION['uye_onay'] == 0) {
    echo '<center><h1><br>Onaylı Kullanıcı Olmadığınız Sebebiyle Panele Erişemezsiniz!</h1></center>';
    exit; }
?>

<br><br>

<table border = "2" >
<tr>
<td>

<ol type = "I" >

<?php echo '<center> <strong>ŞİKAYET EDİLEN KONULAR</strong> <br> <br> </center>';

$sikayetk_Listele = $db -> prepare("SELECT * FROM sikayetk");
$sikayetk_Listele -> execute();
$_sikayetk_Listele = $sikayetk_Listele -> fetchALL(PDO::FETCH_ASSOC);
foreach($_sikayetk_Listele as $sikayet_k) {

$KonuCek = $db -> prepare("SELECT * FROM konular WHERE konu_link=?");
$KonuCek -> execute([$sikayet_k["sikayetk_konulink"]]);
$_KonuCek = $KonuCek -> fetch(PDO::FETCH_ASSOC);

if ($_KonuCek["konu_durum"] == 1) {
echo '<center>'.'<li>'.'< '.$sikayet_k["sikayetk_konu_id"]." no'lu Konu".' ><br><br>'.' <strong>
<a href = "sikayet_paneli_.php?link='.$sikayet_k["sikayetk_konulink"].'">'.$_KonuCek["konu_ad"].'</a>
</strong> <br> <br> <font color = "Green" >Konu Açık</font> </li>'.'</center>'; }
    
else {
echo '<center>'.'<li>'.'< '.$sikayet_k["sikayetk_konu_id"]." no'lu Konu".' ><br><br>'.' <strong>
<a href = "sikayet_paneli_.php?link='.$sikayet_k["sikayetk_konulink"].'">'.$_KonuCek["konu_ad"].'</a>
</strong> <br> <br> <font color = "Red" >Konu Gizlendi</font> </li>'.'</center>'; }
echo '<hr>'; } ?>

</ol>

</td>

<td>

<ol type = "I" >

<?php echo '<center> <strong>ŞİKAYET EDİLEN YORUMLAR</strong> <br> <br> </center>';

$sikayety_Listele = $db -> prepare("SELECT * FROM sikayety");
$sikayety_Listele -> execute();
$_sikayety_Listele = $sikayety_Listele -> fetchALL(PDO::FETCH_ASSOC);
foreach($_sikayety_Listele as $sikayet_y) {

$YorumCek = $db -> prepare("SELECT * FROM yorumlar WHERE y_id=?");
$YorumCek -> execute([$sikayet_y["sikayety_yorum_id"]]);
$_YorumCek = $YorumCek -> fetch(PDO::FETCH_ASSOC);
    
if ($_YorumCek["yorum_durum"] == 1) {
echo '<center>'.'<li>'.'< '.$sikayet_y["sikayety_yorum_id"]." no'lu Yorum".' ><br><br>'.' <strong>
<a href = "sikayet_paneli_y.php?yid='.$sikayet_y["sikayety_id"].'">'.$sikayet_y["sikayety_mesaj"].'</a>
</strong> <br> <br> <font color = "Green" >Yorum Aktif</font> </li>'.'</center>'; }

else {
echo '<center>'.'<li>'.'< '.$sikayet_y["sikayety_yorum_id"]." no'lu Yorum".' ><br><br>'.' <strong>
<a href = "sikayet_paneli_y.php?yid='.$sikayet_y["sikayety_id"].'">'.$sikayet_y["sikayety_mesaj"].'</a>
</strong> <br> <br> <font color = "Red" >Yorum Gizlendi</font> </li>'.'</center>'; }
echo '<hr>'; } ?>
    
</ol>

</td>
</tr>
</table>
</center>
</div>
</body>
</html>