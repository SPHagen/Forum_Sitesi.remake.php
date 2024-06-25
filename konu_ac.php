<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>..:: Konu Aç! ::..</title>
    <link Rel = "Stylesheet" Style = "text/css" Href = "Sinfonia_Per_Hagen.css" />
</head>
<body>
    <div id = "Yapıcı" >
        <?php
        session_start();
        ob_start();
        include 'ayar.php';
        include 'ukas.php';
        include 'fonksiyon.php';
        $kategori = $_GET['kategori'];
        ?>
        <div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <Kategori_ismi_konu_ac><strong><?=kategori_linkten_kategori_adi($kategori);?></Kategori_ismi_konu_ac>
            <br>
            <isimli_kategoride_konu_aciniz>İsimli Kategori'de Konu Açıyorsunuz!</strong></isimli_kategoride_konu_aciniz>
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
            if (!@$_SESSION["uye_id"]) {
                echo '<center><br><br><h1>KONU AÇABİLMEK İÇİN KAYIT OLMALISINIZ!<br><br>> <a href = "uyelik.php" >KAYIT OLUN</a> <';
                exit; }
            if ($_POST && !empty($_POST['Mesaj']) && !empty($_POST['Ad'])) {
                $ad = $_POST["Ad"];
                $mesaj = $_POST["Mesaj"];
                $link = permalink($ad) . "/" . rand(0, 1000);
                $dataAdd = $db -> prepare("INSERT INTO konular SET
                konu_ad=?,
                konu_link=?,
                konu_mesaj=?,
                konu_uye_id=?,
                k_kategori_link=?");
                $dataAdd -> execute([
                    $ad,
                    $link,
                    $mesaj,
                    @$_SESSION["uye_id"],
                    $kategori]);
                    if ($dataAdd) {
                        echo '<center><p><font Size = "16" >Konunuz Açıldı!</font></p></center>';
                        header("REFRESH:1;URL=konu.php?link=" . $link); }
                    else {
                        echo '<center><p><font Size = "16" >Konunuz Paylaşılırken Bir Sıkıntı Oldu!</font></p></center>';
                        header("REFRESH:1;URL=konu_ac.php"); }
            }
            ?>
            <form Action = " " Method = "Post" >
                <br><br><center>
                <yeni_bir_konu_ac><strong>Yeni Bir Konu Aç!</strong><br><br></yeni_bir_konu_ac>
                <konunuzun_basligi_ne_olsun>Konunuzun Başlığı Ne Olsun? </konunuzun_basligi_ne_olsun>
                <input Type = "Text" Name = "Ad" id = "input_Text_Konu_Adi" ><br>
                <br><br>
                <textarea name = "Mesaj" cols = "30" rows = "10" ></textarea>
                <br><br>
                <input Type = "Submit" Value = "Konuyu Aç!" id = "Konuyu_Ac" >
                <input Type = "Reset" Value = "Sayfayı Temizle!" id = "Sayfayi_Temizle" >
            </form>
            <div id = "Alt" ></div></div>
        <?php ob_end_flush(); ?> </div>
    </body>
</html>