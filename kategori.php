<?php
session_start();
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';

$q = @$_GET["q"];

$data = $db -> prepare("SELECT * FROM kategoriler WHERE k_kategori_link=?
");
$data -> execute([
  $q
]);
$_data = $data -> fetch(PDO::FETCH_ASSOC);
?>
<center>
<?php include 'ust_bilgi.php'; ?>

<br> <br>

<h2><?=$_data["k_kategori"]?></h2>

<a href="konu_ac.php?kategori=<?=$_data["k_kategori_link"]?>"><button>Konu Aç</button></a>

<ul>
	<?php
	$dataList = $db -> prepare("SELECT * FROM konular WHERE konu_kategori_link=? ORDER BY konu_id DESC");
	$dataList -> execute([
		$q
	]);
	$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
	foreach($dataList as $row){
	echo '<li><a href="konu.php?link='.$row["konu_link"].'">'.$row["konu_ad"].'</a></li>';
	}
	?>
</ul>

</form>

</center>