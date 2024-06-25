<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>..:: Kaplan Pençesi Forum ::..</title>
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
        ?>
        <div id = "Üst" >
            <center><a href = "index.php" ><img src = "Kaplan_Pencesi.jpg" ></img></a></center>
            <Kaplan_Pencesi><strong>KAPLAN PENÇESİ</Kaplan_Pencesi>
            <br>
            <Foruma_dair_bircok_sey>Forum'a Dair Birçok Şey</strong></Foruma_dair_bircok_sey>
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
            <center><br><br><table>
                <tr Style = "Vertical-Align : Top" >
                    <td>
                        <table Border = "1" >
                            <tr>
                                <td><br><br><font color = "Purple" size = "5" ><strong><center>YENİ AÇILAN<br>KONULAR</center></strong></font><br><br></td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    $dataList = $db -> prepare("SELECT * FROM konular ORDER BY konu_id DESC LIMIT 10");
                                    $dataList -> execute();
                                    $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
                                    foreach ($dataList as $row) {
                                        echo '<center><strong>';
                                        if ($row["k_cozulmemis"] == 1 && $row["k_cozulmus"] == 0) echo '<br> <a href = konu.php?link='.$row["konu_link"].'>
                                        '.$row["konu_ad"].'<br><br></a> <font color = "Black" >..::</font> <font color = "#ec3b83" >AÇIK</font> <font color = "Black" >::..</font><br><br>';
                                        else if ($row["k_cozulmemis"] == 0 && $row["k_cozulmus"] == 1) echo '<br><a href = konu.php?link='.$row["konu_link"].'>
                                        '.$row["konu_ad"].'<br><br></a><font color = "Black" >..::</font> <font color = "Green" >ÇÖZÜLDÜ</font> <font color = "Black" >::..</font><br><br>';
                                        echo '</strong></center>'; }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table Border = "1" >
                            <tr>
                                <td><br><br><font color = "Purple" size = "5" ><strong><center>KONU<br>KATEGORİLERİ</center></strong></font><br><br></td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    $dataList = $db -> prepare("SELECT * FROM kategoriler LIMIT 10");
                                    $dataList -> execute();
                                    $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
                                    foreach ($dataList as $row) {
                                        echo '<center> <strong>';
                                        echo '<br><a href="kategori.php?q='.$row["k_kategori_link"].'"><strong>..:: '.$row["k_kategori"].' ::..</strong><br><br><br></a>';
                                        echo '</strong></center>'; }
                                        ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table Border = "1" >
                            <tr>
                                <td><br><br><font color = "Purple" size = "5" ><strong><center>SON<br>CEVAPLAR</center></strong></font><br><br></td>
                            </tr>
                            <tr>
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
                                        if ($konucek["k_cozulmemis"] == 1 && $konucek["k_cozulmus"] == 0) echo '<br><a href="konu.php?link='.$konucek["konu_link"].'">
                                        '.$konucek["konu_ad"].'<br><br></a><font color = "Black" >..::</font> <font color = "#ec3b83" >AÇIK</font><font color = "Black" > ::..</font><br><br>';
                                        else if ($konucek["k_cozulmemis"] == 0 && $konucek["k_cozulmus"] == 1) echo '<br><a href="konu.php?link='.$konucek["konu_link"].'">
                                        '.$konucek["konu_ad"].'<br><br></a><font color = "Black" >..::</font> <font color = "Green" >ÇÖZÜLDÜ</font><font color = "Black" > ::..</font><br><br>';
                                        @$i++;
                                        if ($i == 10) break;
                                        echo '</strong></center>'; }
                                    ?>
                                    </td>
                                </tr>
                        </table>
                    </td>
                    <td>
                        <table Border = "1" >
                            <tr>
                                <td><br><br><font color = "Purple" size = "5" ><strong><center>SON ÜYE<br>KULLANICILAR</center></strong></font><br><br></td>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    $dataList = $db -> prepare("SELECT * FROM uyeler ORDER BY uye_id DESC LIMIT 10");
                                    $dataList -> execute();
                                    $dataList = $dataList -> fetchALL(PDO::FETCH_ASSOC);
                                    foreach ($dataList as $row) {
                                        echo '<center><strong>';
                                        echo '<br> <a href="profil.php?kadi='.$row["uye_kadi"].'"</a>..:: '.$row['uye_kadi'].' ::..<br><br><br>';
                                        echo '</center></strong>'; }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <?php ob_end_flush(); ?></div>
    </body>
</html>