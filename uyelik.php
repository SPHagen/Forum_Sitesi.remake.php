<?php
          session_start();
          include 'ayar.php';
          include 'ukas.php';

          $p = @$_GET["p"];

          switch ($p) {
           case 'sifremiunuttum':
            if ($_POST) {
                $eposta = htmlspecialchars( $_POST["eposta"] );

                if (
                    empty( $eposta )
                ) {
                    echo '<p class="alert alert-warning">Lütfen boş bırakmayınız!</p>';
                } else {
                    if (filter_var($eposta, FILTER_VALIDATE_EMAIL)){ //  :)
                        $selectRow = $db -> prepare("SELECT * FROM uyeler WHERE
                            uye_eposta =:uye_eposta
                        ");
                        $selectRow -> execute([
                            'uye_eposta' => $eposta
                        ]);
                        $selectRow = $selectRow -> rowCount();
                        
                        if($selectRow > 0){ // Var
                            $yeniSifre  = time() . rand(111,999);
                            $Sifrele    = md5(sha1( $yeniSifre ));
                        }
                    } else {
                        echo '<p class="alert alert-danger">Lütfen gerçek bir eposta adresi yazınız!</p>';
                    }
                }
                
            }
            echo '
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <form action="" method="post">
                            <strong>Eposta:</strong>
                            <input type="email" name="eposta" class="form-kontrol" placeholder="sakip@sabanci.com">
                            </form>
                        <a href="uyelik" class="d-block mt-2">Giriş Yap</a>
                    </div>
                </div>
            </div>';

            break;
            case 'cikis':
                if (@$_SESSION["uye_id"]) {
                    ukas_cikis("index.php");
                }else{
                    header("LOCATION:index.php");
                }
                break;

            case 'kayit':
                if (@$_SESSION["uye_id"]) {
                    header("LOCATION:index.php");
                }else{
                    ukas_kayit("<p class='text-warning'>Lütfen boş bırakmayınız!</p>", "<p class='text-danger'>Böyle bir eposta mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p class='text-warning'>Böyle bir kullanıcı adı mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p class='text-success'>Başarıyla kaydoldun! :)</p>", "index.php", "<p class='text-danger'>Kullanıcı adı veya şifre hatalı!</p>", "<p class='text-danger'>Kayıt başarısız!</p>", "<p>Şifreniz bir birine eşleşmiyor!</p>", "<p>Lütfen gerçek bir eposta giriniz!</p>");
                    echo '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>..:: Şimdi Kayıt Ol! ::..</title>
                        <link rel = "StyleSheet" Href = "Sinfonia_Per_Hagen.css" style="text/css" />
                    </head>
                    <body>
                    <div id = "Yapıcı" >
                    <div id = "Üst" >
                    <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
                    <Kategoriler><strong>Kayıt Ol</strong></Kategoriler>
                    </div>
                    <div id = "Merkez" >
                    <center>
                    <form action="" method="POST">
                    <br><br><strong>Ad Soyad:</strong>
                    <input type="text" class="form-control" name="adsoyad"><br><br>
                    <strong>Kullanıcı adı:</strong>
                    <input type="text" class="form-control" name="kadi"><br><br>
                    <strong>Şifre:</strong>
                    <input type="password" class="form-control" name="sifre"><br><br>
                    <strong>Şifre (Tekrar):</strong>
                    <input type="password" class="form-control" name="sifret"><br><br>
                    <strong>E-Posta:</strong>
                    <input type="text" class="form-control" name="eposta"><br /><br>
                    <input type= "submit" id = "Buton_giris_yap" name="kayit" value="Kayıt Ol">
                    <br><br><strong><a href = "uyelik.php" ><h3>Giriş Yap!</h3></a></strong>
                    </center>
                    </div>
                    </div>
                    </body>
                    </html>';
                }
                break;

            default:
                if (@$_SESSION["uye_id"]) {
                    header("LOCATION:index.php");
                }else{
                    ukas_giris("index.php", "<p class='text-warning'>Lütfen boş bırakmayınız!</p>", "<p class='text-danger'>Kullanıcı adı veya şifre hatalı!</p>");
                    echo '<!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>..:: Giriş Yap ::..</title>
                        <link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
                    </head>
                    <body>
                    <div id = "Yapıcı" >
                    <div id = "Üst" >
                    <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
                    <Kategoriler><strong>Giriş Yap</strong></Kategoriler>
                    </div>
                    <div id = "Merkez" >
                    <center>
                    <br /><form action="" method="POST">
                        <br><br><strong>Kullanıcı Adı: </strong>
                        <input type="text" class="form-control" name="kadi"><br><br>
                        <hr><br><strong>Şifre:</strong>
                        <input type="password" class="form-control" name="sifre"><br />
                        <br><br><input type="submit" name="giris" id = "Buton_giris_yap" value="Giriş Yap">
                        <br><br><strong><a href = "uyelik.php?p=kayit" ><h3>Şimdi Kayıt Ol!</h3></a></strong>
                    </form>
                    </center>
                    </div>
                    </div>
                    </body>
                    </html>';
                    }
                break;
        }
        ?>