<!DOCTYPE html>
<html lang="en">
<head>
	<?php
	session_start();
	ob_start();
	include 'ayar.php';
	include 'ukas.php';
	include 'fonksiyon.php';

	$q = @$_GET["q"];
	$data = $db -> prepare("SELECT * FROM kategoriler WHERE k_kategori_link=?");
	$data -> execute([$q]);
	$_data = $data -> fetch(PDO::FETCH_ASSOC);

	?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>..:: <?=$_data['k_kategori']; ?> ::..</title>
	<link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
</head>
<body>
	<div id = "Yapıcı" >
		<div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <Kategoriler><strong>KATEGORİLER</strong></Kategoriler>
            <?php
            if (@$_SESSION['uye_id']) {
                echo '<Profilime_git><a href = "profil.php?kadi='.@$_SESSION["uye_kadi"].'" ><font color = "Beige" >Profilime Git</font></a></Profilime_git>
                <Cikis_yap><a href = "uyelik.php?p=cikis" ><font color = "Beige" >Çıkış Yap</font></a></Cikis_yap>'; }
            else {
                echo '<Giris_yap><a href = "uyelik.php" ><font color = "Beige" >Giriş Yap</font></a></Giris_yap>
                <Kayit_ol><a href = "uyelik.php?p=kayit" class = "KayitOl"><font color = "Beige" >Kayıt Ol</font></a></Kayit_ol>'; }
            ?>
			</div>
			<div id = "Merkez" >
				<center><br><br><strong><Kategori_ismi_ek>Kategori İsmi</Kategori_ismi_ek></strong><br><br><Kategori_ismi><?=$_data["k_kategori"]?></Kategori_ismi></center>
				<br><a href="konu_ac.php?kategori=<?=$_data["k_kategori_link"]?>"><center><button class = "button Butonu_yapilandir" >Yeni Bir Konu Aç!</button></center></a>
				<?php
				$dataList = $db -> prepare("SELECT * FROM konular WHERE k_kategori_link=? ORDER BY konu_id DESC");
				$dataList -> execute([$q]);
				$dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
				?>
				<br><br><center><table Border = "1" >
					<tr>
						<td>
							<center>
								<table Border = "1" >
									<tr>
										<td><center><br><br><font color = "Purple" Size = "5" ><center><strong>KATEGORİ'DE<br>AÇILAN KONULAR</strong></center></font><br><br></center> </td>
									</tr>
								</table>
								<?php
								foreach ($dataList as $row) {
									echo '<tr><td><center><br><strong><a href="konu.php?link='.$row["konu_link"].'">'
									.$row["konu_ad"].'</strong><br><br></a></center>'; }
								?>
								</td>
							</tr>
						</table></center>
						<div id = "Alt" ></div></div>
					<?php ob_end_flush(); ?> </div>
				</body>
			</html>