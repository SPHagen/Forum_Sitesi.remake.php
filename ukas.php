<?php

	$base = "http://localhost/";

	function reptr($text) {
	    $text     = trim($text);
	    $search   = array(
	        'Ç',
	        'ç',
	        'Ğ',
	        'ğ',
	        'ı',
	        'İ',
	        'Ö',
	        'ö',
	        'Ş',
	        'ş',
	        'Ü',
	        'ü',
	        ' ',
	        '!',
	        '.',
	        ':',
	        ';',
	        '?',
	        ',',
	        ')',
	        '(',
	        ']',
	        '[',
	        '}',
	        '{',
	        "/",
	        "&"
	    );
	    $replace  = array(
	        'c',
	        'c',
	        'g',
	        'g',
	        'i',
	        'i',
	        'o',
	        'o',
	        's',
	        's',
	        'u',
	        'u',
	        '-',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        "",
	        "-"
	    );
	    $new_text = str_replace($search, $replace, $text);
	    return strtolower($new_text);
	}

	function epostakontrol($mail) {
	    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
	        return 1;
	    }else{
	        return 0;
	    }
	}

	function ukas_giris($ukas_yonlendir, $ukas_uyariyazisi, $ukas_hatayazisi) { 

		global $db;

	    $uyecek = $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi=? && uye_sifre=?");
	    
	    if (isset($_POST["giris"])) {

	        $kadi  = htmlspecialchars(trim(str_replace(" ", "", $_POST["kadi"])));
	        $sifre = md5(sha1($_POST["sifre"]));

	        if (empty($kadi) || empty($sifre)) {
	            echo $ukas_uyariyazisi;
	        }else{

	            $uyecek -> execute(array(
	                $kadi,
	                $sifre
	            ));
	            $fetch    = $uyecek -> fetch(PDO::FETCH_ASSOC);
	            $rowcount = $uyecek -> rowCount();
	            
	            if ($rowcount) {
			    
	                $_SESSION["uye_id"] 			= $fetch["uye_id"];
	                $_SESSION["uye_adsoyad"] 		= $fetch["uye_adsoyad"]; 
	                $_SESSION["uye_kadi"] 			= $fetch["uye_kadi"];
               		$_SESSION["uye_sifre"] 			= $fetch["uye_sifre"];
	                $_SESSION["uye_eposta"] 		= $fetch["uye_eposta"];
	                $_SESSION["uye_onay"] 			= $fetch["uye_onay"];
	                
	                header("REFRESH:2;URL=" . $ukas_yonlendir);

	            }else{
	                echo $ukas_hatayazisi;
	            }
	        }
	    }
	}

	function ukas_mail($ukas_benimmailim, $ukas_konu, $ukas_mesaj){
		global $ukas_benimmailimx;
		global $ukas_konux;
		global $ukas_mesajx;

		$ukas_benimmailim 	= $ukas_benimmailimx;
		$ukas_konu 			= $ukas_konux;
		$ukas_mesaj 		= $ukas_mesajx;
	}

	function ukas_kayit($ukas_bosbirakilmauyarisi, $ukas_mailvarsamesaji, $ukas_kadivarmesaji, $ukas_kayitbasarili, $ukas_yonlendir, $ukas_kadisifrehatali, $ukas_kayitbasarisiz, $ukas_sifreeslesmiyor, $ukas_sahtemailuyarisi) {

		global $ukas_benimmailimx;
		global $ukas_konux;
		global $ukas_mesajx;

		global $db;

	    if (isset($_POST["kayit"])) {

	        $isim  			= htmlspecialchars(trim($_POST["adsoyad"]));
	        $kadi  			= htmlspecialchars(trim(str_replace(" ", "", $_POST["kadi"])));
	        $sifre  		= htmlspecialchars(trim($_POST["sifre"]));
	        $sifret  		= htmlspecialchars(trim($_POST["sifret"]));
	        $mail  			= htmlspecialchars(trim($_POST["eposta"]));

	        $sifrele 		= md5(sha1($sifre));

	        if (empty($isim) || empty($kadi) || empty($sifre) || empty($sifret) || empty($mail)) { // Boş bırakılmışsa
				echo $ukas_bosbirakilmauyarisi;
	        }else{

	            $kontrol_et = epostakontrol($mail);
	            
	            if ($kontrol_et == "1") {

	                $epostakontrol = $db -> prepare("SELECT * FROM uyeler WHERE uye_eposta =:uye_eposta");
					$epostakontrol -> execute(array('uye_eposta'=>$mail));
					$epostasaydirma = $epostakontrol -> rowCount();
					 
					if($epostasaydirma > 0){
						echo $ukas_mailvarsamesaji;
					}else{

						$kadikontrol = $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi =:uye_kadi");
						$kadikontrol -> execute(array('uye_kadi' => $kadi));
						$kadisaydirma = $kadikontrol -> rowCount();
						 
						if($kadisaydirma > 0){
							echo $ukas_kadivarmesaji;
						}else{
							if ($sifre == $sifret) {
								$sql = "INSERT INTO uyeler SET uye_adsoyad=?, uye_kadi=?, uye_sifre=?, uye_eposta=?";
								$kayit = $db -> prepare($sql);
								$kayit -> execute(array(
								    $isim,
								    $kadi,
								    $sifrele,
								    $mail
								));

								$uyecek = $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi=? && uye_sifre=?");

								if ($kayit) {
									$uyecek -> execute(array(
						                $kadi,
						                $sifrele
						            ));
						            $fetch    = $uyecek -> fetch(PDO::FETCH_ASSOC);
						            $rowcount = $uyecek -> rowCount();
						            
						            if ($rowcount) {
						                
						                $_SESSION["uye_id"] 			= $fetch["uye_id"]; // üye id
						                $_SESSION["uye_adsoyad"] 		= $fetch["uye_adsoyad"]; // üye adı soyadı
						                $_SESSION["uye_kadi"] 			= $fetch["uye_kadi"]; // üye kullanıcı adı
					               		$_SESSION["uye_sifre"] 			= $fetch["uye_sifre"]; // üye şifresi
						                $_SESSION["uye_eposta"] 		= $fetch["uye_eposta"]; // üye epostası
						                $_SESSION["uye_onay"] 			= $fetch["uye_onay"]; // üye onayı
						                
										echo $ukas_kayitbasarili;
								  		header("REFRESH:2;URL=" . $ukas_yonlendir);

								  	}else{
						                echo $ukas_kadisifrehatali;
						            }
								}else{
									echo $ukas_kayitbasarisiz;
								}
							}else{
								echo $ukas_sifreeslesmiyor;
							}
						}
		            } 
		        }else{
		            echo $ukas_sahtemailuyarisi;
		        }
	        }
	    }
	}

	function ukas_profil($p){
		global $db; 
		global $ukas_profil_id;
		global $ukas_profil_adsoyad;
		global $ukas_profil_kadi;
		global $ukas_profil_eposta;

		// üye kontrol
		$uyekontrol 	= $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi =:uye_kadi ");
		$uyekontrol		-> execute(array('uye_kadi'=>$p));
		$uyesaydirma 	= $uyekontrol -> rowCount();
		 
		if($uyesaydirma > 0){

			$uyecek 	= $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi=?");
			$uyecek 	-> execute(array($p));
			$uye_cek 	= $uyecek -> fetch(PDO::FETCH_ASSOC);

				$ukas_profil_id 		= $uye_cek["uye_id"];
				$ukas_profil_adsoyad 	= $uye_cek["uye_adsoyad"];
				$ukas_profil_kadi 		= $uye_cek["uye_kadi"];
				$ukas_profil_eposta 	= $uye_cek["uye_eposta"];
		}
	}

	function ukas_cikis($ukas_cikisyonlendir){
		global $db;
		session_destroy();
		header("REFRESH:2;URL=" . $ukas_cikisyonlendir);
	}
