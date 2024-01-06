<?php

// publik değişkenler
$dersler = [];
$akademisyenler = [];
$zaman_slotlari = [];
$gunler = [];
$derslikler = [];

// Veri tabanıan bağlan
$db = new PDO("mysql:host=localhost;dbname=yazlab2_dersprogrami", "root", "");

// Dersler tablosunu verileri çek
$dersler_sorgu = $db->query("SELECT * FROM dersler");
foreach ($dersler_sorgu as $ders) {
    $dersler[$ders["ders_id"]] = $ders;
}

// Akademisyenler tablosunu verileri çek
$akademisyenler_sorgu = $db->query("SELECT * FROM akademisyenler");
foreach ($akademisyenler_sorgu as $akademisyen) {
    $akademisyenler[$akademisyen["akademisyen_id"]] = $akademisyen;
}

// Zaman slotları tablosundan verileri çek
$zaman_slotlari_sorgu = $db->query("SELECT * FROM ders_zamani");
foreach ($zaman_slotlari_sorgu as $zaman_slotu) {
    $zaman_slotlari[$zaman_slotu["ders_zamani_id"]] = $zaman_slotu;
}

// Günler tablosundann verileri çek
$gunler_sorgu = $db->query("SELECT * FROM gunler");
foreach ($gunler_sorgu as $gun) {
    $gunler[$gun["gun_id"]] = $gun;
}

// Derslikler tablosundan verileri çek
$derslikler_sorgu = $db->query("SELECT * FROM derslikler");
foreach ($derslikler_sorgu as $derslik) {
    $derslikler[$derslik["derslik_id"]] = $derslik;
}

// Matrisi oluşturma
$matris = [];
foreach ($gunler as $gun) {
    foreach ($zaman_slotlari as $zaman_slotu) {
        $matris[$gun["gun_id"]][$zaman_slotu["ders_zamani_id"]] = [];
    }
}

// Dersleri matrise yerleştir
foreach ($dersler as $ders) {
    $akademisyen = $akademisyenler[$ders["akademisyen_id"]];
    $ders_gunu = $gunler[$ders["ders_gun_id"]];
    $zaman_slotu = $zaman_slotlari[$ders["zaman_slotu_id"]];

    $matris[$ders_gunu["gun_id"]][$zaman_slotu["ders_zamani_id"]][] = [$ders["ders_id"], $akademisyen["akademisyen_ad"], $akademisyen["akademisyen_soyad"]];
}

$kromatik_sayi = 0;
while (true) {
    $max_degisiklik = 0;
    foreach ($matris as $gun_id => $gun_matrisi) {
        foreach ($gun_matrisi as $zaman_slotu_id => $zaman_slotu_matrisi) {
            if (count($zaman_slotu_matrisi) > 1) {
                $degisiklik = $this->welsh_powell_algoritmasi($zaman_slotu_matrisi);
                if ($degisiklik > $max_degisiklik) {
                    $max_degisiklik = $degisiklik;
                    $degisecek_satir_index = $gun_id;
                    $degisecek_sutun_index = $zaman_slotu_id;
                }
            }
        }
    }

    if ($max_degisiklik == 0) {
        break;
    }

    $this->matrisi_guncelle($matris, $degisecek_satir_index, $degisecek_sutun_index);
    $kromatik_sayi++;
}

function welsh_powell_algoritmasi($matris)
{
    $degisiklik = 0;
    $renkler = array();

    // Her düğüme ilk renk atanması
    for ($i = 0; $i < count($matris); $i++) {
        $renkler[$i] = 1;
    }

    
    while (true) {
        $degisiklik = 0;

        for ($i = 0; $i < count($matris); $i++) {
            for ($j = 0; $j < count($matris); $j++) {
                if ($i != $j && $matris[$i][$j]) {
                    if ($renkler[$i] == $renkler[$j]) {
                        $degisiklik = 1;

                        // Renkleri değiştir
                        $renkler[$i]++;
                        $renkler[$j]++;
                    }
                }
            }
        }

        // tüm düğümler renklendirildiyse döngüyü sonlandır
        if ($degisiklik == 0) {
            break;
        }
    }

    return $degisiklik;
}

// oluşturulan programı akademisen_ders (sınıf tablolarına) ekle
foreach ($matris as $gun_id => $gun_matrisi) {
    foreach ($gun_matrisi as $zaman_slotu_id => $zaman_slotu_matrisi) {
        foreach ($zaman_slotu_matrisi as $ders_bilgisi) {
            $ders_id = $ders_bilgisi[0];
            $akademisyen_ad = $ders_bilgisi[1];
            $akademisyen_soyad = $ders_bilgisi[2];
            $ders_gunu = $gunler[$gun_id];
            $zaman_slotu = $zaman_slotlari[$zaman_slotu_id];
            $derslik = $derslikler[$ders_gunu["derslik_id"]];

            $sorgu = $db->prepare("INSERT INTO akademisyen_ders (akademisyen_id, ders_id, ders_gun_id, zaman_slotu_id, sinif_id) VALUES (:akademisyen_id, :ders_id, :ders_gun_id, :zaman_slotu_id, :derslik_id)");
            $sorgu->execute([
                "akademisyen_id" => $akademisyen_id,
                "ders_id" => $ders_id,
                "ders_gun_id" => $ders_gunu["gun_id"],
                "zaman_slotu_id" => $zaman_slotu["ders_zamani_id"],
                "derslik_id" => $derslik["derslik_id"]
            ]);
        }
    }
}