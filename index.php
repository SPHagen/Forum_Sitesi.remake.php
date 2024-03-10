<?php
session_start();
include 'ayar.php';
include 'ukas.php';
include 'fonksiyon.php';
?>

<center>
<?php include 'ust_bilgi.php'; ?>

<br> <br>

<table border = "1" >
	<tr>
		<td>
			<strong> Yeni Açılan Konular: </strong><hr>
			<ul>
			<?php
			$dataList = $db -> prepare("SELECT * FROM konular ORDER BY konu_id DESC LIMIT 10");
			$dataList -> execute();
			$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
			foreach($dataList as $row){
			echo '<li><a href=konu.php?link='.$row["konu_link"].'>'.$row["konu_ad"].'</a></li>';
		}
			?>
			</ul>
		</td>
		<td>
			<strong> Son Cevaplar: </strong><hr>
			<ul>
			<?php
				$dataList = $db -> prepare("SELECT * FROM yorumlar ORDER BY y_id DESC LIMIT 100");
				$dataList -> execute();
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
	<tr>
		<td colspan = "2" >
			<h2> Kategoriler: </h2>

			<ul>
			<?php
			$dataList = $db -> prepare("SELECT * FROM kategoriler LIMIT 10");
			$dataList -> execute();
			$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
			foreach($dataList as $row){
				echo '<li><a href="kategori.php?q='.$row["k_kategori_link"].'">'.$row["k_kategori"].'</a></li>';
			}
			?>
			</ul>
		</td>
	</tr>
	<tr>
		<td colspan = "2" >
			<h2> Son Üye Kullanıcılar: </h2>

			<ul>
			<?php
	$dataList = $db -> prepare("SELECT * FROM uyeler ORDER BY uye_id DESC LIMIT 10");
	$dataList -> execute();
	$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
	foreach($dataList as $row){
	echo '<li><a href="profil.php?kadi='.$row["uye_kadi"].'"</a>'.$row["uye_kadi"].'<br><br></li>';
	}
			?>
			</ul>
		</td>
	</tr>
</table>

</center>