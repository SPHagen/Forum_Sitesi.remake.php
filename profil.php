<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
	include 'ayar.php';
    include 'ukas.php';
    include 'fonksiyon.php';
	$kadi = @$_GET["kadi"];
	$data = $db -> prepare("SELECT *, uye_rep_puani as Rep_Puani FROM uyeler WHERE uye_kadi=?");
	$data -> execute([$kadi]);
	$_data = $data -> fetch(PDO::FETCH_ASSOC);
	?>
	<title><?='..:: '.$_data['uye_kadi'].' ::..'; ?></title>
	<link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
</head>
<body>
	<div id = "Yapıcı" >
		<?php
		session_start();
        ob_start();
		?>
		<div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <br>
            <Kategoriler><strong><?='..:: '.$_data['uye_kadi'].' ::..'; ?></strong></Kategoriler>
            <?php
            if (@$_SESSION['uye_id']) {
                echo '<Profilime_git><a href = "profil.php?kadi='.@$_SESSION["uye_kadi"].'" ><font Color = "Beige" >Profilime Git</font></a></Profilime_git>
                <Cikis_yap><a href = "uyelik.php?p=cikis" ><font Color = "Beige" >Çıkış Yap</font></a></Cikis_yap>'; }
            else {
                echo '<Giris_yap><a href = "uyelik.php" ><font Color = "Beige" >Giriş Yap</font></a></Giris_yap>
                <Kayit_ol><a href = "uyelik.php?p=kayit" class = "KayitOl" ><font Color = "Beige" >Kayıt Ol</font></a></Kayit_ol>'; }
			?>
        </div>
        <div id = "Merkez" >
			<center>
				<br><br><div id = "Profil_Bilgileri" >
					<?= '<Kullanici_Adi>'.$_data['uye_adsoyad'].'</Kullanici_Adi>'; ?>
					<br><br>
					
					<?php

                    echo '<div id = "Bu_Kullanıcının_Seviyesi_Nedir" >';

					echo '<Uyelik_Tarihi_Profil><font Size = "5" >Üyelik Tarihi: </font><font Size = "5" >'.$_data['uyelik_tarihi'].'</font></Uyelik_Tarihi_Profil>';
					
					echo '</div>';

					?>
				</div>
				<div id = "Profil_Bilgileri_2" >
				<?php
				echo '<div id = "Üye_Bilgileri" ><Uye_Bilgileri>'.$_data['uye_kadi'].', Üye Bilgileri</Uye_Bilgileri></div>';
				echo '<br>';
				echo '<font Size = "4px" >İletişim Adresi: <strong>'.$_data['uye_eposta'].'</strong></font>';
				echo '<br><br><hr>';
				echo '<br>';
				echo '<font Size = "4px" >Rep Puanı: '.$_data['Rep_Puani'].'</font>';
				echo '<br><br><hr>';
				echo '<br>';
				if ($_data['uye_onay'] == 1 && $_data['Rep_Puani'] >= 0 && $_data['Rep_Puani'] <= 9) {
					echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" ><Onaylı_Profil><strong>Onaylı</strong></Onaylı_Profil> Yeni Üye</font>';
					echo '<br><br><hr>'; echo '<br>'; }
				
				else if ($_data['uye_onay'] == 0 && $_data['Rep_Puani'] >= 0 && $_data['Rep_Puani'] <= 9) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" >Yeni Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
				
				else if ($_data['uye_onay'] == 1 && $_data['Rep_Puani'] >= 10 && $_data['Rep_Puani'] <= 19) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" ><Onaylı_Profil>Onaylı</Onaylı_Profil> Deneyimli Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
				
				else if ($_data['uye_onay'] == 0 && $_data['Rep_Puani'] >= 10 && $_data['Rep_Puani'] <= 19) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" >Deneyimli Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }

				else if ($_data['uye_onay'] == 1 && $_data['Rep_Puani'] >= 20 && $_data['Rep_Puani'] <= 29) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" ><Onaylı_Profil>Onaylı</Onaylı_Profil> Çalışkan Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
					
				else if ($_data['uye_onay'] == 0 && $_data['Rep_Puani'] >= 20 && $_data['Rep_Puani'] <= 29) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" >Çalışkan Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
				
				else if ($_data['uye_onay'] == 1 && $_data['Rep_Puani'] >= 30 && $_data['Rep_Puani'] <= 39) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" ><Onaylı_Profil>Onaylı</Onaylı_Profil> Başarılı Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
						
				else if ($_data['uye_onay'] == 0 && $_data['Rep_Puani'] >= 30 && $_data['Rep_Puani'] <= 39) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" >Başarılı Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
				
				else if ($_data['uye_onay'] == 1 && $_data['Rep_Puani'] >= 40 && $_data['Rep_Puani'] <= 49) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" ><Onaylı_Profil>Onaylı</Onaylı_Profil> Emektar Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
							
				else if ($_data['uye_onay'] == 0 && $_data['Rep_Puani'] >= 40 && $_data['Rep_Puani'] <= 49) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" >Emektar Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
				
				else if ($_data['uye_onay'] == 1 && $_data['Rep_Puani'] >= 50) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" ><Onaylı_Profil>Onaylı</Onaylı_Profil> Efsane Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
								
				else if ($_data['uye_onay'] == 0 && $_data['Rep_Puani'] >= 50) {
						echo '<font Size = "4px" >Seviyesi: </font><font Size = "5px" >Efsane Üye</font>';
						echo '<br><br><hr>'; echo '<br>'; }
						
						?>
						</div>
						<div id = "Üye_İstatistikleri" >
							<div id = "İstatistikler_Detay" ><Uye_Bilgileri>..:: İstatistikler ::..</Uye_Bilgileri></div>
							<?php

							$Uye_Konu_Sayisi_Cek = $db -> prepare("SELECT *, COUNT(konu_id) as Konu_Sayisi FROM konular WHERE konu_durum = 1 AND konu_uye_id = ?");
							$Uye_Konu_Sayisi_Cek -> execute([$_data['uye_id']]);
							$_Uye_Konu_Sayisi_Cek = $Uye_Konu_Sayisi_Cek -> fetch(PDO::FETCH_ASSOC);
							
							$Uye_Yorum_Sayisi_Cek = $db -> prepare("SELECT *, COUNT(y_yorum) as Yorum_Sayisi FROM yorumlar WHERE yorum_durum = 1 AND y_uye_id = ?");
							$Uye_Yorum_Sayisi_Cek -> execute([$_data['uye_id']]);
							$_Uye_Yorum_Sayisi_Cek = $Uye_Yorum_Sayisi_Cek -> fetch(PDO::FETCH_ASSOC);

							$Uye_Yorum_Sayisi_Cek_2 = $db -> prepare("SELECT * FROM yorumlar WHERE y_uye_id = ? ORDER BY y_id DESC LIMIT 1");
							$Uye_Yorum_Sayisi_Cek_2 -> execute([$_data['uye_id']]);
							$_Uye_Yorum_Sayisi_Cek_2 = $Uye_Yorum_Sayisi_Cek_2 -> fetch(PDO::FETCH_ASSOC);

							$Uye_Konu_Sayisi_Cek_2 = $db -> prepare("SELECT * FROM konular WHERE konu_uye_id = ? ORDER BY konu_id DESC LIMIT 1");
							$Uye_Konu_Sayisi_Cek_2 -> execute([$_data['uye_id']]);
							$_Uye_Konu_Sayisi_Cek_2 = $Uye_Konu_Sayisi_Cek_2 -> fetch(PDO::FETCH_ASSOC);

							$Veri = $db -> prepare("SELECT * FROM konular WHERE konu_id = ? ORDER BY konu_id DESC LIMIT 1");
							$Veri -> execute([@$_Uye_Yorum_Sayisi_Cek_2['y_konu_id']]);
							$_Veri = $Veri -> fetch(PDO::FETCH_ASSOC);
							
							echo '<strong><div id = "Toplam_Konu_Sayısı" ><Actigi_Konu_Sayisi>Açtığı Konu Sayısı: '.$_Uye_Konu_Sayisi_Cek['Konu_Sayisi'].'</Actigi_Konu_Sayisi></div>
							<div id = "Toplam_Yorum_Sayısı" ><Toplam_Yorum_Sayisi>Toplam Yorum Sayısı: '.$_Uye_Yorum_Sayisi_Cek['Yorum_Sayisi'].'</Toplam_Yorum_Sayisi></div></strong>';

							if ($_data['uye_id'] == $_Uye_Konu_Sayisi_Cek['konu_uye_id']) echo '<div id = "Son_Konu" >Açtığı Son Konu<br><br>
							<a href = "konu.php?link='.$_Uye_Konu_Sayisi_Cek_2['konu_link'].'">'.$_Uye_Konu_Sayisi_Cek_2['konu_ad'].'</a></div>';

							if ($_data['uye_id'] == $_Uye_Yorum_Sayisi_Cek['y_uye_id']) echo '<div id = "Son_Yorum" >Attığı Son Yorum<br><br>
							<a href = "konu.php?link='.@$_Veri['konu_link'].'">'.@$_Uye_Yorum_Sayisi_Cek_2['y_yorum'].'</a> </div>';
							
							?>
							</div>
						</center>
					</div>
				</body>
				</html>