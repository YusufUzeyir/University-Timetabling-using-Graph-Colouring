<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "yazlab2_dersprogrami";

$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// sınıflar dizisi
$classrooms = array(1036, 1040, 1041, 1044);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ders silme isteği
    if (isset($_POST["delete_request"])) {
        if (isset($_POST["delete_sinif_id"], $_POST["delete_gun_id"], $_POST["delete_zaman_slotu_id"])) {
            $deleteSinifID = $_POST["delete_sinif_id"];
            $deleteGunID = $_POST["delete_gun_id"];
            $deleteZamanSlotuID = $_POST["delete_zaman_slotu_id"];

            // Akademisyen_ders tablosundan ilgili dersi sil
            $sqlDelete = "DELETE FROM akademisyen_ders 
                          WHERE sinif_id = $deleteSinifID 
                          AND ders_gun_id = $deleteGunID 
                          AND zaman_slotu_id = $deleteZamanSlotuID";

            if ($conn->query($sqlDelete) === TRUE) {
                echo "<script>alert('Ders başarıyla silindi.');</script>";
            } else {
                echo "<script>alert('Ders silinirken bir hata oluştu.');</script>";
            }
        } else {
            echo "<script>alert('Geçersiz silme isteği.');</script>";
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["delete_request"])) {
    // formdan gelen bilgileri al
    if (isset($_POST["ders_id"], $_POST["gun_id"], $_POST["zaman_slotu_id"], $_POST["sinif_id"])) {
        $dersID = $_POST["ders_id"];
        $gunID = $_POST["gun_id"];
        $zamanSlotuID = $_POST["zaman_slotu_id"];
        $sinifID = $_POST["sinif_id"];

        // hocanın o gün ve saatte başka sınıfta ders verip vermediğinin kontrolü
        $sqlHocaCheck = "SELECT akademisyen_id FROM akademisyen_ders
                        WHERE akademisyen_id = (SELECT akademisyen_id FROM akademisyenler WHERE akademisyen_brans = $dersID LIMIT 1)
                        AND ders_gun_id = $gunID
                        AND zaman_slotu_id = $zamanSlotuID
                        AND sinif_id != $sinifID";

        $resultHocaCheck = $conn->query($sqlHocaCheck);

        if ($resultHocaCheck->num_rows > 0) {
            echo "<script>alert('Hoca o gün ve saatte başka bir sınıfta ders vermektedir.');</script>";
        } else {
            // tüm hocaları kontrol et
            $sqlAllHocasCheck = "SELECT akademisyen_id FROM akademisyen_ders
                                WHERE akademisyen_id = (SELECT akademisyen_id FROM akademisyenler WHERE akademisyen_brans = $dersID LIMIT 1)
                                AND ders_gun_id = $gunID
                                AND zaman_slotu_id = $zamanSlotuID";

            $resultAllHocasCheck = $conn->query($sqlAllHocasCheck);

            if ($resultAllHocasCheck->num_rows > 0) {
                echo "<script>alert('Hoca o gün ve saatte başka bir sınıfta ders vermektedir.');</script>";
            } else {
                // çakışma kontrolü
                $sqlCollisionCheck = "SELECT * FROM akademisyen_ders 
                                    WHERE sinif_id = $sinifID 
                                    AND ders_gun_id = $gunID 
                                    AND zaman_slotu_id = $zamanSlotuID";

                $resultCollisionCheck = $conn->query($sqlCollisionCheck);

                if ($resultCollisionCheck->num_rows > 0) {
                    echo "<script>alert('Bu saatte sınıfta başka bir ders bulunmaktadır.');</script>";
                } else {
                    // akademisyen tablosundan ilgili dersi veren hoca bilgisini çek.
                    $sqlHoca = "SELECT akademisyen_ad, akademisyen_soyad
                                FROM akademisyenler
                                WHERE akademisyen_brans = $dersID
                                LIMIT 1";

                    $resultHoca = $conn->query($sqlHoca);

                    if ($resultHoca->num_rows > 0) {
                        $rowHoca = $resultHoca->fetch_assoc();
                        $hocaAd = $rowHoca["akademisyen_ad"] . " " . $rowHoca["akademisyen_soyad"];

                        // akademisyen_ders tablosuna yeni dersi ekle
                        $sqlInsert = "INSERT INTO akademisyen_ders (akademisyen_id, ders_id, ders_gun_id, zaman_slotu_id, sinif_id)
                                      VALUES ((SELECT akademisyen_id FROM akademisyenler WHERE akademisyen_brans = $dersID LIMIT 1), $dersID, $gunID, $zamanSlotuID, $sinifID)";

                        if ($conn->query($sqlInsert) === TRUE) {
                            echo "<script>alert('Ders başarıyla eklendi.');</script>";
                        } else {
                            echo "<script>alert('Ders eklenirken bir hata oluştu.');</script>";
                        }
                    } else {
                        echo "<script>alert('Hoca bilgisi bulunamadı.');</script>";
                    }
                }
            }
        }
    } else {
    }
}

// günlerin dizisi
$days = array(1, 2, 3, 4, 5); // Pazartesi'den Cuma'ya

// maksimum ders sayısı
$maxLessonsPerDay = 5;


// Oluştur butonu tıklanınca
if (isset($_POST["olustur"])) {

    // Önce akademisyen_ders tablosundaki verileri sil (sonrasında verileri ekleki güncel tablo her seferinde görünsün)
    $conn->query("DELETE FROM akademisyen_ders");

    // Tüm sınıflar için
    foreach ($classrooms as $classroom) {
        // Tüm günler için
        foreach ($days as $day) {
            // Rastgele günlük ders sayısı seç (maksimum 5)
            $dailyLessonCount = rand(1, $maxLessonsPerDay);

            for ($i = 0; $i < $dailyLessonCount; $i++) {
                // Rastgele ders seç
                $sqlRandomDers = "SELECT ders_id FROM dersler ORDER BY RAND() LIMIT 1";
                $resultRandomDers = $conn->query($sqlRandomDers);

                if ($resultRandomDers->num_rows > 0) {
                    $rowRandomDers = $resultRandomDers->fetch_assoc();
                    $randomDersID = $rowRandomDers["ders_id"];

                    // Dersi veren hoca bilgisini al
                    $sqlHoca = "SELECT akademisyen_id FROM akademisyenler WHERE akademisyen_brans = $randomDersID LIMIT 1";
                    $resultHoca = $conn->query($sqlHoca);

                    if ($resultHoca->num_rows > 0) {
                        $rowHoca = $resultHoca->fetch_assoc();
                        $randomHocaID = $rowHoca["akademisyen_id"];

                        // kontrol et: Aynı hoca aynı gün ve saatte başka sınıfta ders veriyor mu diye (çakışma olmaması için)
                        $sqlHocaCheck = "SELECT COUNT(*) AS total FROM akademisyen_ders 
                                        WHERE akademisyen_id = $randomHocaID 
                                        AND ders_gun_id = $day 
                                        AND sinif_id != $classroom";

                        $resultHocaCheck = $conn->query($sqlHocaCheck);

                        if ($resultHocaCheck->num_rows > 0) {
                            $rowHocaCheck = $resultHocaCheck->fetch_assoc();
                            $totalLessons = $rowHocaCheck["total"];

                            // Eğer aynı gün ve saatte başka sınıfta ders varsa, bu dersi ekleme
                            if ($totalLessons < $maxLessonsPerDay) {
                                $zamanSlotuID = rand(1, 9); 
                                $sqlInsert = "INSERT INTO akademisyen_ders (akademisyen_id, ders_id, ders_gun_id, zaman_slotu_id, sinif_id)
                                              VALUES ($randomHocaID, $randomDersID, $day, $zamanSlotuID, $classroom)";

                                $conn->query($sqlInsert);
                            }
                        }
                    }
                }
            }
        }
    }

    echo "<script>alert('Ders programları başarıyla oluşturuldu.');</script>";
}

// Temizle butonuan basınca
if (isset($_POST["temizle"])) {

     //tablolardaki verileri sil (dolayısıyla veri tabanından sil)
     $conn->query("DELETE FROM akademisyen_ders");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ders Programı</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <style>
        body {
            min-height: 100vh;
            width: 100%;
            background-color: #485461;
            overflow-x: hidden;
            transform-style: preserve-3d;
        }

        .navbar {
            background-color: #4a5c62;
        }

        .navbar-nav li a {
            font-size: 18px;
            color: white;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item">
                <a class="nav-link" href="dersprogrami.php">Ders Programı</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dersler.php">Dersler</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="akademisyenler.php">Akademisyenler</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="derslikler.php">Derslikler</a>
            </li>
        </ul>
    </nav>

    <div class="container mt-5">

        <h2 class="mb-4">Ders Programı</h2>

        
        <div class="modal" id="dersEkleModal">
            <div class="modal-dialog">
                <div class="modal-content">

                   
                    <div class="modal-header">
                        <h4 class="modal-title">Ders Ekle</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    
                    <div class="modal-body">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label for="ders_id">Ders:</label>
                                <select class="form-control" id="ders_id" name="ders_id">
                                    <?php
                                    // dersler tablosundan verileri çek
                                    $sqlDersler = "SELECT * FROM dersler";
                                    $resultDersler = $conn->query($sqlDersler);

                                    while ($rowDersler = $resultDersler->fetch_assoc()) {
                                        echo "<option value='{$rowDersler['ders_id']}'>{$rowDersler['ders_ad']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gun_id">Gün:</label>
                                <select class="form-control" id="gun_id" name="gun_id">
                                    <?php
                                    // gunler tablosundan verileri çek
                                    $sqlGunler = "SELECT * FROM gunler";
                                    $resultGunler = $conn->query($sqlGunler);

                                    while ($rowGunler = $resultGunler->fetch_assoc()) {
                                        echo "<option value='{$rowGunler['gun_id']}'>{$rowGunler['gun_ad']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="zaman_slotu_id">Saat:</label>
                                <select class="form-control" id="zaman_slotu_id" name="zaman_slotu_id">
                                    <?php
                                    // ders_zamani tablosundan verileri çek
                                    $sqlZamanSlotlari = "SELECT * FROM ders_zamani";
                                    $resultZamanSlotlari = $conn->query($sqlZamanSlotlari);

                                    while ($rowZamanSlotlari = $resultZamanSlotlari->fetch_assoc()) {
                                        echo "<option value='{$rowZamanSlotlari['ders_zamani_id']}'>{$rowZamanSlotlari['ders_zamani_cevresi']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sinif_id">Sınıf:</label>
                                <select class="form-control" id="sinif_id" name="sinif_id">
                                    <?php
                                    // derslikler tablosundan verileri çek
                                    $sqlSiniflar = "SELECT * FROM derslikler";
                                    $resultSiniflar = $conn->query($sqlSiniflar);

                                    while ($rowSiniflar = $resultSiniflar->fetch_assoc()) {
                                        echo "<option value='{$rowSiniflar['derslik_id']}'>{$rowSiniflar['derslik_id']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Ekle</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal" id="dersSilModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    
                    <div class="modal-header">
                        <h4 class="modal-title">Ders Sil</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                   
                    <div class="modal-body">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="form-group">
                                <label for="delete_sinif_id">Sınıf:</label>
                                <select class="form-control" id="delete_sinif_id" name="delete_sinif_id">
                                    <?php
                                    // derslikler tablosundan verileri çek
                                    $sqlSiniflar = "SELECT * FROM derslikler";
                                    $resultSiniflar = $conn->query($sqlSiniflar);

                                    while ($rowSiniflar = $resultSiniflar->fetch_assoc()) {
                                        echo "<option value='{$rowSiniflar['derslik_id']}'>{$rowSiniflar['derslik_id']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="delete_gun_id">Gün:</label>
                                <select class="form-control" id="delete_gun_id" name="delete_gun_id">
                                    <?php
                                    // gunler tablosundan verileri çek
                                    $sqlGunler = "SELECT * FROM gunler";
                                    $resultGunler = $conn->query($sqlGunler);

                                    while ($rowGunler = $resultGunler->fetch_assoc()) {
                                        echo "<option value='{$rowGunler['gun_id']}'>{$rowGunler['gun_ad']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="delete_zaman_slotu_id">Saat:</label>
                                <select class="form-control" id="delete_zaman_slotu_id" name="delete_zaman_slotu_id">
                                    <?php
                                    // ders_zamani tablosundan verileri çek
                                    $sqlZamanSlotlari = "SELECT * FROM ders_zamani";
                                    $resultZamanSlotlari = $conn->query($sqlZamanSlotlari);

                                    while ($rowZamanSlotlari = $resultZamanSlotlari->fetch_assoc()) {
                                        echo "<option value='{$rowZamanSlotlari['ders_zamani_id']}'>{$rowZamanSlotlari['ders_zamani_cevresi']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="delete_request" value="1">
                            <button type="submit" class="btn btn-danger">Sil</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        
        <button type="button" class="btn btn-danger mb-4" data-toggle="modal" data-target="#dersSilModal">Ders Sil</button>

       
        <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#dersEkleModal">Ders Ekle</button>

        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <button type="submit" class="btn btn-primary" name="olustur">Oluştur</button>
            <button type="submit" class="btn btn-warning" name="temizle">Temizle</button>
        </form>
        
        

        <div class="container mt-5">
            <?php
            // her sınıf için ders programını al akademisyen_dert tablosuna ekle 
            foreach ($classrooms as $classroom) {
                $sql = "SELECT ders_zamani.ders_zamani_cevresi, gunler.gun_ad, dersler.ders_ad, akademisyenler.akademisyen_ad, akademisyenler.akademisyen_soyad
            FROM akademisyen_ders
            INNER JOIN derslikler ON akademisyen_ders.sinif_id = derslikler.derslik_id
            INNER JOIN ders_zamani ON akademisyen_ders.zaman_slotu_id = ders_zamani.ders_zamani_id
            INNER JOIN gunler ON akademisyen_ders.ders_gun_id = gunler.gun_id
            INNER JOIN dersler ON akademisyen_ders.ders_id = dersler.ders_id
            INNER JOIN akademisyenler ON akademisyen_ders.akademisyen_id = akademisyenler.akademisyen_id
            WHERE akademisyen_ders.sinif_id = $classroom
            ORDER BY gunler.gun_id, ders_zamani.ders_zamani_id";

                $result = $conn->query($sql);

                echo "<h2 class='mt-4'>Ders Programı - Sınıf $classroom</h2>";

                if ($result->num_rows > 0) {
                    echo "<div class='table-responsive'>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Saat / Gün</th>";

                    // sütunları temisl eden gün aralıkları
                    $days = array('Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma');
                    foreach ($days as $day) {
                        echo "<th>$day</th>";
                    }

                    echo "</tr></thead><tbody>";

                    // satırlara denk gelen saat aralıkları
                    $times = array('08.00-09.00', '09.00-10.00', '10.00-11.00', '11.00-12.00', '12.00-13.00', '13.00-14.00', '14.00-15.00', '15.00-16.00', '16.00-17.00');

                    foreach ($times as $time) {
                        echo "<tr>
                    <th>$time</th>";

                        // gün sırasına göre dersleri ekle
                        foreach ($days as $day) {
                            $found = false;

                            // Ders var mı kontrolü çakışma olmaması için
                            $result->data_seek(0);
                            while ($row = $result->fetch_assoc()) {
                                if ($row['gun_ad'] == $day && $row['ders_zamani_cevresi'] == $time) {
                                    echo "<td>{$row['ders_ad']}<br>{$row['akademisyen_ad']} {$row['akademisyen_soyad']}</td>";
                                    $found = true;
                                    break;
                                }
                            }

                            if (!$found) {
                                echo "<td></td>";
                            }
                        }

                        echo "</tr>";
                    }

                    echo "</tbody></table></div>";
                } else {
                    echo "<p>Ders programı bulunamadı.</p>";
                }

                echo "<hr>";
            }

            // Bağlantıyı kapat
            $conn->close();
            ?>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</body>

</html>