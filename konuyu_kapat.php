<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>..:: Konuyu Kapat! ::..</title>
    <link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
</head>
<body>
    <form Action = "" Method = "Post" >
    <div id = "Yapıcı" >
        <?php
        session_start();
        ob_start();
        include 'ayar.php';
        include 'ukas.php';
        include 'fonksiyon.php';
        $kid = $_GET['kid'];

        $Rep_konu = $db -> prepare("SELECT * FROM konular WHERE konu_id = ?");
        $Rep_konu -> execute([$kid]);
        $_Rep_konu = $Rep_konu -> fetch(PDO::FETCH_ASSOC);

        ?>
        <div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <Kategori_ismi_konu_ac><strong><?=$_Rep_konu['konu_ad']; ?></Kategori_ismi_konu_ac>
            <br>
            <isimli_kategoride_konu_aciniz>İsimli Konuyu Kapatıyorsunuz!</strong></isimli_kategoride_konu_aciniz>
            <?php
            if (@$_SESSION['uye_id']) {
                echo '<Profilime_git><a href = "profil.php?kadi='.@$_SESSION["uye_kadi"].'" ><font color = "Beige" >Profilime Git</font></a></Profilime_git>
                <Cikis_yap><a href = "uyelik.php?p=cikis" ><font color = "Beige" >Çıkış Yap</font></a></Cikis_yap>'; }
            else {
                echo '<Giris_yap><a href = "uyelik.php" ><font color = "Beige" >Giriş Yap</font></a></Giris_yap>
                <Kayit_ol><a href = "uyelik.php?p=kayit" class = "KayitOl" ><font color = "Beige" >Kayıt Ol</font></a></Kayit_ol>'; }
            ?>
            </div>
            <div id = "Merkez" >
            <?php
            if (@$_SESSION['uye_id'] !== $_Rep_konu["konu_uye_id"]) {
                echo '<center><h1>Konuyu Sadece İstek Sahibi Kişi Kapatabilir!</h1></center>';
                exit; }
            $Repd = [];
            $Repy = $db -> prepare("SELECT * FROM yorumlar WHERE y_konu_id = ?");
            $Repy -> execute([$kid]);
            while ($_Repy = $Repy -> fetch(PDO::FETCH_ASSOC)) { array_push ($Repd, $_Repy["y_uye_id"]); }
            $Repd_2 = array_unique($Repd);
            echo '<br><br>';
            echo '<center><h2>REP Puanını, Kime (Kimlere) Vermek İstersiniz?</h2></center>';
            echo '<br>';
            foreach ($Repd_2 as $Rep) {
                if ($_Rep_konu['konu_uye_id'] !== $Rep) {
                $Konuyu_Cozen = '<a href = "profil.php?kadi='.uye_ID_to_kadi($Rep).'">'.uye_ID_to_kadi($Rep).'</a>';
                echo "<table Border = '1' id = 'Yorum_Atan_Kullanıcılar' ><tr><td id = 'Yorum_Atan_Kullanıcılar_ek' ><input Type = 'Checkbox' Name = '$Rep' ></td><td id = 'Yorum_Atan_Kullanıcılar_ek'><strong><font Size = '5px' >".$Konuyu_Cozen."</font></strong></td></tr></table>";
            }
            if (isset($_POST["$Rep"]) && isset($_POST['Rep'])) {
                $onayla = $db -> prepare("UPDATE uyeler SET uye_rep_puani = uye_rep_puani + 1 WHERE uye_id = $Rep");
                $onayla -> execute();
                $k_cozulmemis = $db -> prepare("UPDATE konular SET k_cozulmemis = 0 WHERE konu_id = $kid");
                $k_cozulmemis -> execute();
                $k_cozulmus = $db -> prepare("UPDATE konular SET k_cozulmus = 1 WHERE konu_id = $kid");
                $k_cozulmus -> execute();
                $Konuyu_Cozen = '<a href = "profil.php?kadi='.uye_ID_to_kadi($Rep).'">'.uye_ID_to_kadi($Rep).'</a>';
                $dataAdd = $db -> prepare("INSERT INTO yorumlar SET
                y_uye_id = 24,
                y_konu_id = $kid,
                y_yorum = '$Konuyu_Cozen isimli kullanıcı, konu çözümünde yardımcı olduğu için Rep Puanı otomatik verilmiştir.<br><br>Konu, Çözülmüş Konulara taşınmıştır!'");
                $dataAdd -> execute();
                echo '<center><h2><p class = "alert alert-success" >Seçtiğiniz Kişiye, Rep Puanı Verildi ve Konu Kapatıldı!</p></h2></center>';
                header("REFRESH:1;URL=index.php"); }
            }

            if (isset($_POST['Rep_kendim_cozdum'])) {
                $k_cozulmemis = $db -> prepare("UPDATE konular SET k_cozulmemis = 0 WHERE konu_id = $kid");
                $k_cozulmemis -> execute();
                $k_cozulmus = $db -> prepare("UPDATE konular SET k_cozulmus = 1 WHERE konu_id = $kid");
                $k_cozulmus -> execute();
                $dataAdd = $db -> prepare("INSERT INTO yorumlar SET
                y_uye_id = 24,
                y_konu_id = $kid,
                y_yorum = 'Konu, istek sahibi tarafından çözülmüştür!'");
                $dataAdd -> execute();
                echo '<center><h2><p class = "alert alert-success" >Konunuz, Çözülmüş Konulara Taşındı ve Kapatıldı!</p></h2></center>';
                header("REFRESH:1;URL=index.php");
            }
            ?>
            <br><br>
            <?php
            if ($_Rep_konu['konu_uye_id'] !== @$Rep && count($Repd_2) >= 1) echo '<input type = "Submit" name = "Rep" id = "Rep_Puanı_Verin_Ve_Konuyu_Kapatın" value = "Rep Puanı Verin ve Konuyu Kapatın!" >';
            ?>
            <br><br>
            <input type = "Submit" name = "Rep_kendim_cozdum" id = "Konuyu_Kendim_Çözdüm" value = "Konuyu Kendim Çözdüm!" >
            </center>
            </form>
            <? = ob_end_flush(); ?></div>
        </body>
        </html>