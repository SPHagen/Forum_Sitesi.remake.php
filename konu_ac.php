<?php
session_start();
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';

if(!@$_SESSION["uye_id"])
{
    echo '<center><h1>Konu paylaşmanız için üye olmanız gereklidir! <a href = "uyelik.php">Üye Ol</a></center><h1>';
    exit;
}

$kategori = $_GET["kategori"];
?>

<center>

<?php include 'ust_bilgi.php'; ?>

<br> <br>

<?php
if ($_POST) {
    $ad     = $_POST["ad"];
    $mesaj  = $_POST["mesaj"];
    $link = permalink($ad) . "/" . rand(0, 100);

    $dataAdd = $db -> prepare("INSERT INTO konular SET
                konu_ad=?,
                konu_link=?,
                konu_mesaj=?,
                konu_uye_id=?,
                konu_kategori_link=?
    ");
    $dataAdd -> execute([
        $ad,
        $link,
        $mesaj,
        @$_SESSION["uye_id"],
        $kategori
    ]);

    if ( $dataAdd ) {
        echo '<p class="alert alert-success">Başarıyla konunuz paylaşıldı.</p>';
        
        header("REFRESH:1;URL=konu.php?link=" . $link);
    } else {
        echo '<p class="alert alert-danger">Sorun oldu, yeniden deneyiniz. :/</p>';
        
        header("REFRESH:1;URL=konuac.php");
    }
}
?>
<strong><?=kategori_linkten_kategori_adi($kategori)?> Kategorisinde Konu açmaktasınız.</strong>
<h2> Konu Paylaşma </h2>

<form action = " " method = "post">

<strong> Konu Adı: </strong>

<input type = "text" name = "ad"> <br>

<strong> Konu Mesajı: </strong>
    
<textarea name = "mesaj" cols = "30" rows = "10"> </textarea>

<br>

<input type = "submit" value = "Konuyu Aç">

</form>

</center>