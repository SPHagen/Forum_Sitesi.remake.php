SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE `uyeler` (
  `uye_id` int(11) NOT NULL,
  `uye_adsoyad` varchar(300) COLLATE utf8_turkish_ci NOT NULL,
  `uye_kadi` varchar(300) COLLATE utf8_turkish_ci NOT NULL,
  `uye_sifre` varchar(300) COLLATE utf8_turkish_ci NOT NULL,
  `uye_eposta` varchar(300) COLLATE utf8_turkish_ci NOT NULL,
  `uye_onay` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
ALTER TABLE `uyeler`
  ADD PRIMARY KEY (`uye_id`);
ALTER TABLE `uyeler`
  MODIFY `uye_id` int(11) NOT NULL AUTO_INCREMENT;