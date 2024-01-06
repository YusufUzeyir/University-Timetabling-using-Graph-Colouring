-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 06 Oca 2024, 15:57:58
-- Sunucu sürümü: 10.4.28-MariaDB
-- PHP Sürümü: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `yazlab2_dersprogrami`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `akademisyenler`
--

CREATE TABLE `akademisyenler` (
  `akademisyen_id` int(11) NOT NULL,
  `akademisyen_ad` varchar(45) NOT NULL,
  `akademisyen_soyad` varchar(45) NOT NULL,
  `akademisyen_brans` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `akademisyenler`
--

INSERT INTO `akademisyenler` (`akademisyen_id`, `akademisyen_ad`, `akademisyen_soyad`, `akademisyen_brans`) VALUES
(129, 'Alper', 'METİN', 101),
(130, 'Çiğdem', 'GÜNDÜZ', 102),
(131, ' Hikmet Hakan', 'GÜREL', 103),
(132, 'Meryem', 'KÜÇÜK', 105),
(133, 'Yavuz Selim', 'FATİHOĞLU', 106),
(134, 'M. Hikmet Bilgehan', 'UÇAR', 201),
(135, ' Zeynep Hilal', 'KİLİMCİ', 203),
(136, 'Serdar', 'SOLAK', 205),
(137, 'İrem', 'ÇAY', 209),
(138, 'Vildan', 'ÇETKİN', 211),
(139, 'Kerem', 'ÇOLAK', 213),
(140, 'Hikmet Hakan', 'GÜREL', 221),
(141, 'Önder', 'YAKUT', 223),
(142, 'Halil', 'YİĞİT', 301),
(143, 'Önder', 'YAKUT', 303),
(144, 'Alper', 'METİN', 305),
(145, 'Alper', 'METİN', 307),
(146, 'Gazi', 'UÇKUN', 309),
(147, ' M. Hikmet Bilgehan', 'UÇAR', 315),
(148, 'Halil', 'YİĞİT', 322),
(149, 'Halil', 'YİĞİT', 337),
(150, 'Süleyman', 'EKEN', 339),
(151, 'Önder', 'YAKUT', 345),
(152, 'Kamile', 'DEMİREL', 408),
(153, 'Zeynep Hilal', 'KİLİMCİ', 412),
(154, 'Zeynep Hila', 'KİLİMCİ', 414),
(155, 'Yavuz Selim', 'FATİHOĞLU', 422),
(156, 'Süleyman', 'EKEN', 424),
(157, 'Asiye', 'YÜKSEL', 450);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `akademisyen_ders`
--

CREATE TABLE `akademisyen_ders` (
  `akademisyen_ders_id` int(11) NOT NULL,
  `akademisyen_id` int(11) NOT NULL,
  `ders_id` int(11) NOT NULL,
  `ders_gun_id` int(11) NOT NULL,
  `zaman_slotu_id` int(11) NOT NULL,
  `sinif_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `dersler`
--

CREATE TABLE `dersler` (
  `ders_id` int(11) NOT NULL,
  `ders_ad` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `dersler`
--

INSERT INTO `dersler` (`ders_id`, `ders_ad`) VALUES
(101, 'Bilişim Sistemleri Müh. Giriş'),
(102, 'Matematik I'),
(103, 'Fizik I'),
(105, 'İş Sağlığı ve Güvenliği'),
(106, 'Algoritma ve Programlama I'),
(201, 'Elektrik Elektronik Devreler'),
(203, 'Nesne Yönelimli Programlama'),
(205, 'Veri Yapıları ve Algoritmalar'),
(209, 'İstatistik ve Olasılık'),
(211, 'Diferansiyel Denklemler'),
(213, 'İşletme Ekonomisi'),
(221, 'Nanoteknolojiye Giriş'),
(223, 'Mobil Uygulama Geliştirme'),
(301, ' Bilgisayar Mimari ve Organizasyonu'),
(303, 'Web Tasarımı'),
(305, ' Bilişim Sistemleri Analizi ve Tasarım'),
(307, 'E-İşletme ve E-Ticaret Uygulamalar'),
(309, 'Yönetim ve Organizasyon'),
(315, 'Sayısal İşaret İşleme'),
(322, 'Kablosuz Ağ Teknolojileri ve Uygulamaları'),
(337, 'Veri Haberleşmesi'),
(339, 'Ayrık Matematik'),
(345, 'Bulut Bilişimde Sanallaştırma Teknolojilerine'),
(408, 'Kalite Yönetim'),
(412, 'Yapay Sinir Ağları'),
(414, 'Veri Madenciliği'),
(422, 'Oyun Programlama'),
(424, 'Yapay Zeka'),
(450, 'Proje Yönetimi');

--
-- Tetikleyiciler `dersler`
--
DELIMITER $$
CREATE TRIGGER `before_delete_dersler` BEFORE DELETE ON `dersler` FOR EACH ROW BEGIN
    DECLARE akademisyen_id_val INT;

    -- Dersler tablosundan silinecek ders_id'ye karşılık gelen akademisyen_brans değerini al
    SELECT akademisyen_brans INTO akademisyen_id_val
    FROM akademisyenler
    WHERE akademisyen_brans = OLD.ders_id
    LIMIT 1;

    -- Akademisyen_brans değeri bulunduysa, ilgili akademisyeni ve dersi sil
    IF akademisyen_id_val IS NOT NULL THEN
        DELETE FROM akademisyenler WHERE akademisyen_brans = OLD.ders_id;
        DELETE FROM akademisyen_ders WHERE ders_id = OLD.ders_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `derslikler`
--

CREATE TABLE `derslikler` (
  `derslik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `derslikler`
--

INSERT INTO `derslikler` (`derslik_id`) VALUES
(1036),
(1040),
(1041),
(1044);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ders_zamani`
--

CREATE TABLE `ders_zamani` (
  `ders_zamani_id` int(11) NOT NULL,
  `ders_zamani_cevresi` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `ders_zamani`
--

INSERT INTO `ders_zamani` (`ders_zamani_id`, `ders_zamani_cevresi`) VALUES
(1, '08.00-09.00'),
(2, '09.00-10.00'),
(3, '10.00-11.00'),
(4, '11.00-12.00'),
(5, '12.00-13.00'),
(6, '13.00-14.00'),
(7, '14.00-15.00'),
(8, '15.00-16.00'),
(9, '16.00-17.00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `gunler`
--

CREATE TABLE `gunler` (
  `gun_id` int(11) NOT NULL,
  `gun_ad` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Tablo döküm verisi `gunler`
--

INSERT INTO `gunler` (`gun_id`, `gun_ad`) VALUES
(1, 'Pazartesi'),
(2, 'Salı'),
(3, 'Çarşamba'),
(4, 'Perşembe'),
(5, 'Cuma');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kisitlamalar`
--

CREATE TABLE `kisitlamalar` (
  `kisit_id` int(11) NOT NULL,
  `akademisyen_kisit_id` int(11) NOT NULL,
  `gun_kisiti` int(11) NOT NULL,
  `zaman_kisiti` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `akademisyenler`
--
ALTER TABLE `akademisyenler`
  ADD PRIMARY KEY (`akademisyen_id`),
  ADD KEY `akademisyen_brans_fk_idx` (`akademisyen_brans`) USING BTREE;

--
-- Tablo için indeksler `akademisyen_ders`
--
ALTER TABLE `akademisyen_ders`
  ADD PRIMARY KEY (`akademisyen_ders_id`),
  ADD KEY `ders_gun_fk_idx` (`ders_gun_id`),
  ADD KEY `zaman_slotu_fk_idx` (`zaman_slotu_id`),
  ADD KEY `sinif_fk_idx` (`sinif_id`),
  ADD KEY `ders_fk_idx` (`ders_id`),
  ADD KEY `akademisyen_fk_idx` (`akademisyen_id`) USING BTREE;

--
-- Tablo için indeksler `dersler`
--
ALTER TABLE `dersler`
  ADD PRIMARY KEY (`ders_id`);

--
-- Tablo için indeksler `derslikler`
--
ALTER TABLE `derslikler`
  ADD PRIMARY KEY (`derslik_id`);

--
-- Tablo için indeksler `ders_zamani`
--
ALTER TABLE `ders_zamani`
  ADD PRIMARY KEY (`ders_zamani_id`);

--
-- Tablo için indeksler `gunler`
--
ALTER TABLE `gunler`
  ADD PRIMARY KEY (`gun_id`);

--
-- Tablo için indeksler `kisitlamalar`
--
ALTER TABLE `kisitlamalar`
  ADD PRIMARY KEY (`kisit_id`),
  ADD KEY `gun_kisiti_fk_idx` (`gun_kisiti`),
  ADD KEY `zaman_kisiti_fk_idx` (`zaman_kisiti`),
  ADD KEY `akademisyen_fk_idx` (`akademisyen_kisit_id`) USING BTREE;

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `akademisyenler`
--
ALTER TABLE `akademisyenler`
  MODIFY `akademisyen_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- Tablo için AUTO_INCREMENT değeri `akademisyen_ders`
--
ALTER TABLE `akademisyen_ders`
  MODIFY `akademisyen_ders_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4302;

--
-- Tablo için AUTO_INCREMENT değeri `dersler`
--
ALTER TABLE `dersler`
  MODIFY `ders_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=809;

--
-- Tablo için AUTO_INCREMENT değeri `ders_zamani`
--
ALTER TABLE `ders_zamani`
  MODIFY `ders_zamani_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `kisitlamalar`
--
ALTER TABLE `kisitlamalar`
  MODIFY `kisit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `akademisyenler`
--
ALTER TABLE `akademisyenler`
  ADD CONSTRAINT `ogretmen_brans_fk` FOREIGN KEY (`akademisyen_brans`) REFERENCES `dersler` (`ders_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `akademisyen_ders`
--
ALTER TABLE `akademisyen_ders`
  ADD CONSTRAINT `ders2_fk` FOREIGN KEY (`ders_id`) REFERENCES `dersler` (`ders_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `ders_gun2_fk` FOREIGN KEY (`ders_gun_id`) REFERENCES `gunler` (`gun_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `ogretmen2_fk` FOREIGN KEY (`akademisyen_id`) REFERENCES `akademisyenler` (`akademisyen_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sinif2_fk` FOREIGN KEY (`sinif_id`) REFERENCES `derslikler` (`derslik_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `zaman_slotu2_fk` FOREIGN KEY (`zaman_slotu_id`) REFERENCES `ders_zamani` (`ders_zamani_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Tablo kısıtlamaları `kisitlamalar`
--
ALTER TABLE `kisitlamalar`
  ADD CONSTRAINT `gun_kisiti_fk` FOREIGN KEY (`gun_kisiti`) REFERENCES `gunler` (`gun_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `ogretmen_fk` FOREIGN KEY (`akademisyen_kisit_id`) REFERENCES `akademisyen_ders` (`akademisyen_ders_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `zaman_kisiti_fk` FOREIGN KEY (`zaman_kisiti`) REFERENCES `ders_zamani` (`ders_zamani_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
