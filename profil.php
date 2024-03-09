<?php
session_start();
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';

$kadi = @$_GET["kadi"];

$data = $db -> prepare("SELECT * FROM uyeler WHERE
  uye_kadi=?
");
$data -> execute([
  $kadi
]);
$_data = $data -> fetch(PDO::FETCH_ASSOC);
?>

<center>
<?php include 'ust_bilgi.php'; ?>
<br><br>
<h2><?=$_data["uye_adsoyad"]?></h2>
<strong> Gmail: </strong><?=$_data["uye_eposta"]?>
<br><br>
<?php
$seviye = $db -> prepare("SELECT count(y_yorum) as yorumsayisi FROM yorumlar WHERE y_uye_id=?");
$seviye -> execute([
	$_data["uye_id"]
]);
$_seviye = $seviye -> fetch(PDO::FETCH_ASSOC);
echo'Attığı Yorum Sayısı: '.($_seviye["yorumsayisi"].'<br><br>');
if ($_seviye["yorumsayisi"] >= 0 && $_seviye["yorumsayisi"] < 5) echo 'SEVİYESİ: YENİ ÜYE';
else if ($_seviye["yorumsayisi"] >= 5 && $_seviye["yorumsayisi"] < 10) echo 'SEVİYESİ: DENEYİMLİ';
else if ($_seviye["yorumsayisi"] >= 10 && $_seviye["yorumsayisi"] < 15) echo 'SEVİYESİ: EFSANEVİ';
?>
<hr>

<table border = "1" width = 100% >
	<tr>
		<td>
			<strong> Yeni Açılan Konular: </strong>
			<ul>
				<?php
				$dataList = $db -> prepare("SELECT * FROM konular WHERE konu_uye_id=?");
				$dataList -> execute([
					$_data["uye_id"]
				]);
				$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
				
				foreach($dataList as $row){
					echo '<li><a href="konu.php?link='.$row["konu_link"].'">'.$row["konu_ad"].'</a></li>';
				}
				?>
				</ul>
		</td>
		<td>
			<strong> Son Cevaplar: </strong><hr>
			<ul>
			<?php
				$dataList = $db -> prepare("SELECT * FROM yorumlar WHERE y_uye_id=? LIMIT 100");
				$dataList -> execute([
					$_data["uye_id"]
				]);
				$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
				
				$konu_idler = [];

				foreach($dataList as $row){
					array_push( $konu_idler, $row["y_konu_id"] );

				}

				$konu_idler = array_unique( $konu_idler );

				foreach($konu_idler as $konu_id) {

$konu_cek = $db -> prepare("SELECT * FROM konular WHERE
  konu_id=?
");
$konu_cek -> execute([
  $konu_id
]);
$konucek = $konu_cek -> fetch(PDO::FETCH_ASSOC);

echo '<li><a href="konu.php?link='.$konucek["konu_link"].'">'.$konucek["konu_ad"].'</a></li>';
@$i++;
					if ($i == 10)
					{
						break;
					}

				}
				?>
			</ul>
		</td>
	</tr>
</table>

</center>