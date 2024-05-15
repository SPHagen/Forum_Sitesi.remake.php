<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>.: Forum Definitive Edition :.</title>
	<link rel="stylesheet" type="text/css" href="lieh.css">
</head>
<body>
	
</body>
</html>

<?php
session_start();
ob_start();

include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';
include 'ust_bilgi.php';

?>

<center>
<table border = "1" >
	<tr>
		<td> <center> <br> <br> YENİ AÇILAN KONULAR <br> <br> <br> </td>
		<td> <center> <br> <br> KATEGORİLER <br> <br> <br> </td>
		<td> <center> <br> <br> SON CEVAPLAR <br> <br> <br> </td>
		<td> <center> <br> <br> SON ÜYE <br> KULLANICILAR <br> <br> <br> </td>
	</tr>
	<tr>
		<td>
			<?php
			$dataList = $db -> prepare("SELECT * FROM konular ORDER BY konu_id DESC LIMIT 10");
			$dataList -> execute();
			$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
			foreach($dataList as $row) {
				echo '<center> <strong>';
				if ($row["k_cozulmemis"] == 1 && $row["k_cozulmus"] == 0) echo '<br> <a href = konu.php?link='.$row["konu_link"].'>
				'.$row["konu_ad"].'<br> </a> <font color = "Gray" >.:</font> <font color = "Orange" >AÇIK</font> <font color = "Gray" >:.</font> <br> <br>';
				else if ($row["k_cozulmemis"] == 0 && $row["k_cozulmus"] == 1) echo '<br> <a href = konu.php?link='.$row["konu_link"].'>
				'.$row["konu_ad"].'<br> </a> <font color = "Gray" >.:</font> <font color = "Green" >ÇÖZÜLDÜ</font> <font color = "Gray" >:.</font> <br> <br>';
				echo '</strong>   </center>';

			}
				?>
				</td>
				
		<td>
			<?php
			$dataList = $db -> prepare("SELECT * FROM kategoriler LIMIT 10");
			$dataList -> execute();
			$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
			foreach($dataList as $row) {
				echo '<center> <strong>';
				echo '<br> <a href="kategori.php?q='.$row["k_kategori_link"].'"> <strong> .: '.$row["k_kategori"].' :. </strong> <br> <br> <br> </a>';
				echo '</strong> </center>';
			}
			?>
		</td>
		
		<td>
			<?php
			$dataList = $db -> prepare("SELECT * FROM yorumlar ORDER BY y_id DESC LIMIT 100");
			$dataList -> execute();
			$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
			$Yeni_dizi = [];
			foreach ($dataList as $dongu) { array_push ($Yeni_dizi, $dongu["y_konu_id"]); }
			$Yeni_dizi_2 = array_unique ($Yeni_dizi);
			foreach ($Yeni_dizi_2 as $dongu_2) {
				$konu_cek = $db -> prepare("SELECT * FROM konular WHERE konu_id=?");
				$konu_cek -> execute([$dongu_2]);
				$konucek = $konu_cek -> fetch(PDO::FETCH_ASSOC);
				echo '<center> <strong>';
				if ($konucek["k_cozulmemis"] == 1 && $konucek["k_cozulmus"] == 0) echo '<br> <a href="konu.php?link='.$konucek["konu_link"].'">
				'.$konucek["konu_ad"].'<br> </a> <font color = "Gray" >.:</font> <font color = "Orange" >AÇIK</font> <font color = "Gray" >:.</font> <br> <br>';
				else if ($konucek["k_cozulmemis"] == 0 && $konucek["k_cozulmus"] == 1) echo '<br> <a href="konu.php?link='.$konucek["konu_link"].'">
				'.$konucek["konu_ad"].'<br> </a> <font color = "Gray" >.:</font> <font color = "Green" >ÇÖZÜLDÜ</font> <font color = "Gray" >:.</font> <br> <br>';
				@$i++;
				if ($i == 10) break;
				echo '</strong> </center>';

			}
			?>
		</td>
		
		<td>
			<?php
			$dataList = $db -> prepare("SELECT * FROM uyeler ORDER BY uye_id DESC LIMIT 10");
			$dataList -> execute();
			$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
			foreach($dataList as $row) {
				echo '<center> <strong>';
				echo '<br> <a href="profil.php?kadi='.$row["uye_kadi"].'"</a> <font face = "Cascadia Code" > '.$row["uye_kadi"].' </font> <br> <br> <br>';
				echo '</center> </strong>';

			}
			?>
		</td>
	</tr>
</table>
		</center>