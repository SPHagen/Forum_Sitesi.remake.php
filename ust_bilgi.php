<a href = "index.php"> <h1> Forum Sitesi PHP <> Serkan Armutlu </h1> </a>

<?php
if (@$_SESSION['uye_id'])
{
    echo '<a href = "profil.php?kadi='.@$_SESSION["uye_kadi"].'">Profilime Git</a>
    <a href = "uyelik.php?p=cikis"> Çıkış Yap </a>';
}
else
{
    echo '<a href = "uyelik.php?p=kayit"> Üye Ol </a> veya <a href = "uyelik.php"> Giriş Yap </a>';
}
?>