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
                    echo '<h1 class="text-center"><strong>Şimdi Kayıt Ol!</strong></h1>
                    <form action="" method="POST">
                        <strong>Ad Soyad:</strong><br>
                        <input type="text" class="form-control" name="adsoyad"><br>
                        <strong>Kullanıcı adı:</strong><br>
                        <input type="text" class="form-control" name="kadi"><br>
                        <strong>Şifre:</strong><br>
                        <input type="password" class="form-control" name="sifre"><br>
                        <strong>Şifre (Tekrar):</strong><br>
                        <input type="password" class="form-control" name="sifret"><br>
                        <strong>E-Posta:</strong><br>
                        <input type="text" class="form-control" name="eposta"><br />
                        <input type="submit" class="btn btn-block btn-dark" name="kayit" value="Kayıt Ol">
                    </form>
                    <hr>
                    <a href="uyelik.php?p=giris" class="btn btn-block btn-secondary">Şimdi giriş yap!</a><br />
                    <a href="index.php" class="text-dark"><small>&larr; Anasayfaya dön</small></a>';
                }
                break;

            default:
                if (@$_SESSION["uye_id"]) {
                    header("LOCATION:index.php");
                }else{
                    ukas_giris("index.php", "<p class='text-warning'>Lütfen boş bırakmayınız!</p>", "<p class='text-danger'>Kullanıcı adı veya şifre hatalı!</p>");

                    echo '<h1 class="text-center"><strong>Giriş Yap</strong></h1>
                    <br /><form action="" method="POST">
                        <strong>Kullanıcı Adı:</strong>
                        <input type="text" class="form-control" name="kadi">
                        <strong>Şifre:</strong>
                        <input type="password" class="form-control" name="sifre"><br />
                        <input type="submit" class="btn btn-block btn-dark" name="giris" value="Giriş Yap">
                    </form>
                    <a href="uyelik.php?p=kayit" class="btn btn-block btn-secondary mt-3">Şimdi kayıt ol!</a>
                    <hr>
                    <a href="index.php" class="text-dark"><small>&larr; Anasayfaya dön</small></a>';
                    }
                break;
        }

      ?>
