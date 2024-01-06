<?php
include 'baglanti.php';




if (isset($_GET['derssil'])) {


  $sil = $baglanti->prepare("DELETE FROM dersler WHERE ders_id=:ders_id");

  $sil->execute(array(

    'ders_id' => $_GET['ders_id']

  ));


  if ($sil) {
    Header("Location:dersler.php?durum=yes");
  } else {
    Header("Location:dersler.php?durum=no");
  }
}


if (isset($_POST['dersekle'])) {

  try {

    $baglanti->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $ders_id = $_POST["ders_id"];
      $ders_ad = $_POST["ders_ad"];
      

      $sql = "SELECT * FROM dersler WHERE ders_id = :ders_id";
      $stmt = $baglanti->prepare($sql);
      $stmt->bindParam(":ders_id", $ders_id);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        echo "<script>alert('Bu ID değerine sahip ders bulunmaktadır.')</script>";

        echo "<script>window.location.href = 'ekle-ilac.php';</script>";

        exit;

        error_reporting(0);
      } else {
        if (!is_numeric($ders_id)) {
          echo "<script>alert('Hata: ID değeri bir sayı olmalıdır.')</script>";

          echo "<script>window.location.href = 'ekle-ilac.php';</script>";

          exit;

          error_reporting(0);
        } elseif (!is_string($ders_ad)) {
          echo "<script>alert('Hata: Ders Adı bir metin olmalıdır.')</script>";

          echo "<script>window.location.href = 'ekle-ilac.php';</script>";

          exit;

          error_reporting(0);
        }  else {
          $sql = "INSERT INTO dersler (ders_id, ders_ad) VALUES (:ders_id, :ders_ad)";
          $stmt = $baglanti->prepare($sql);
          $stmt->bindParam(":ders_id", $ders_id);
          $stmt->bindParam(":ders_ad", $ders_ad);
          

          if ($stmt->execute()) {
            Header("Location:dersler.php?durum=yes");
          } else {
            Header("Location:dersler.php?durum=no");
          }
        }
      }
    }
  } catch (PDOException $e) {
    echo "Hata: " . $e->getMessage();
  }
} else {
  
}




if (isset($_GET['akademisyensil'])) {


  $sil = $baglanti->prepare("DELETE FROM akademisyenler WHERE akademisyen_id=:akademisyen_id");

  $sil->execute(array(

    'akademisyen_id' => $_GET['akademisyen_id']

  ));


  if ($sil) {
    Header("Location:akademisyenler.php?durum=yes");
  } else {
    Header("Location:akademisyenler.php?durum=no");
  }
}



if(isset($_POST['akademisyenekle'])){

    

  $akademisyenkaydet = $baglanti->prepare("INSERT INTO akademisyenler SET 

akademisyen_id = :akademisyen_id,
akademisyen_ad = :akademisyen_ad,
akademisyen_soyad = :akademisyen_soyad,
akademisyen_brans = :akademisyen_brans
      
  ");

  $insert = $akademisyenkaydet->execute(array(

      'akademisyen_id' => $_POST['akademisyen_id'],
      'akademisyen_ad' => $_POST['akademisyen_ad'],
      'akademisyen_soyad' => $_POST['akademisyen_soyad'],
      'akademisyen_brans' => $_POST['akademisyen_brans']
     
  ));

  if($insert){
      Header("Location:akademisyenler.php?durum=yes");
  }
  else{
      Header("Location:akademisyenler.php?durum=no");
  }
}
else{
 
}

if(isset($_POST['akademisyen_dersekle'])){

    

  $akademisyenkaydet = $baglanti->prepare("INSERT INTO akademisyen_ders SET 

akademisyen_id = :akademisyen_id,
ders_id = :ders_id,
ders_gun_id = :ders_gun_id,
zaman_slotu_id = :zaman_slotu_id
sinif_id = :sinif_id
      
  ");

  $insert = $akademisyenkaydet->execute(array(

      'akademisyen_id' => $_POST['akademisyen_id'],
      'ders_id' => $_POST['ders_id'],
      'ders_gun_id' => $_POST['ders_gun_id'],
      'zaman_slotu_id' => $_POST['zaman_slotu_id'],
      'sinif_id' => $_POST['sinif_id']

  ));

  if($insert){
      Header("Location:dersprogrami.php?durum=yes");
  }
  else{
      Header("Location:dersprogrami.php?durum=no");
  }
}
else{

}