<?php
session_start();
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';

if (@$_SESSION['uye_onay'] !== 1 )
{
    echo '<center><h1>Yönetici değilsiniz! Panele erişemezsiniz!</h1><center>';
    exit;
}
?>

<center>
<?php include 'ust_bilgi.php'; ?>

<br> <br>

<h2> Admin Paneli </h2>

<hr>

<form action = " " method = "post">

<h3> Kategori Ekle: </h3>
    
<input type = "text" name = "kategori">

<br>

<input type = "submit" value = "Kategori Oluştur">
<?php
if ($_POST) {
    $kategori = $_POST["kategori"];
    $kategori_link = permalink($kategori);

$dataAdd = $db -> prepare("INSERT INTO kategoriler SET
    k_kategori=?,
    k_kategori_link=?
");
$dataAdd -> execute([
    $kategori,
    $kategori_link
]);

if ( $dataAdd ) {
    echo '<p class="alert alert-success">Kategori Eklendi!</p>';
    
    header("REFRESH:1;URL=admin_paneli.php");
} else {
    echo '<p class="alert alert-danger">Kategori eklenirken sıkıntı oldu!</p>';
    
    header("REFRESH:1;URL=admin_paneli.php");
}
}
?>
</form>

<hr>

<ol>
<?php

$dataList = $db -> prepare("SELECT * FROM kategoriler ");
$dataList -> execute();
$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);

foreach($dataList as $row){
    echo '<li><a href = "kategori.php?q='.$row["k_kategori_link"].'">'.$row["k_kategori"].'</a></li>';
}
?>
</ol>

</center>