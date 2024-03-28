<?php
if (@$_SESSION['uye_id'])
{
    echo '<a href = "profil.php?kadi='.@$_SESSION["uye_kadi"].'"><font face="Cascadia Code" >Profilime Git</font></a>
    <a href = "uyelik.php?p=cikis">Oturumu Sonlandır</a>';
}
else
{
    echo '<a href = "uyelik.php?p=kayit" class="KayitOl"><font face="Cascadia Code" >KAYIT OL</font></a><br><br>Veya<br><br><a href = "uyelik.php">GİRİŞ YAP</a>';
}
?>