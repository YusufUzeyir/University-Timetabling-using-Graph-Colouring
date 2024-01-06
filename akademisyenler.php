<?php include 'baglanti.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Akademisyenler</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

  <style>
    body {
      min-height: 100vh;
      width: 100%;
      background-color: #485461;
      background-image: linear-gradient(135deg, #485461 0%, #28313b 74%);
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
      <li class="nav-item ">
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

  <div class="container" style="width:100%; color:white; background-color:#6c747e; margin-top: 40px; padding-top: 10px; padding-bottom:5px;">
    <a href="#" data-bs-toggle="modal" data-bs-target="#akademisyenEkleModal">
      <button style="margin-left: 600px;" type="button" class="btn btn-warning">Akademisyen Ekle</button>
    </a>

    <table id="example" class="table background:#6c747e" style="width:100%;">
      <thead>
        <tr>
          <th>Akademisye-Branş ID</th>
          <th>Akademisyen Adı</th>
          <th>Akademisyen Soyadı</th>
          <th>Branşı</th>
          <th>Akademisyen Sil</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $akademisor = $baglanti->prepare("SELECT a.*, d.ders_ad
                                 FROM akademisyenler a
                                 LEFT JOIN dersler d ON a.akademisyen_brans = d.ders_id");
        $akademisor->execute();

        while ($akademicek = $akademisor->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td><?php echo $akademicek['akademisyen_id'] ?></td>
            <td><?php echo $akademicek['akademisyen_ad'] ?></td>
            <td><?php echo $akademicek['akademisyen_soyad'] ?></td>
            <td><?php echo $akademicek['ders_ad'] ?></td>
            <td><a href="islemler.php?akademisyensil&akademisyen_id=<?php echo $akademicek['akademisyen_id'] ?>"><button type="button" class="btn btn-danger">Sil</button></a></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  <div class="modal fade" id="akademisyenEkleModal" tabindex="-1" aria-labelledby="akademisyenEkleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="akademisyenEkleModalLabel">Akademisyen Ekle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="islemler.php" method="post">
            <div class="form-group">
              <label for="akademisyenAdi">Akademisyen-Branş ID</label>
              <input type="text" class="form-control" id="akademisyen_id" name="akademisyen_id" required>
            </div>
            <div class="form-group">
              <label for="akademisyenAdi">Akademisyen Adı</label>
              <input type="text" class="form-control" id="akademisyen_ad" name="akademisyen_ad" required>
            </div>
            <div class="form-group">
              <label for="akademisyenSoyadi">Akademisyen Soyadı</label>
              <input type="text" class="form-control" id="akademisyen_soyad" name="akademisyen_soyad" required>
            </div>
            <div class="form-group">
              <label for="akademisyenBransi">Branşı</label><br>
              <select name="akademisyen_brans" id="akademisyen_brans" class="form-control" required>
                <?php
                $conn = mysqli_connect("localhost", "root", "", "yazlab2_dersprogrami");
                $sql = "SELECT * FROM dersler";
                $result1 = mysqli_query($conn, $sql);
                ?>
                <?php while ($row1 = mysqli_fetch_array($result1)) :; ?>
                  <option value="<?php echo $row1[0]; ?>"><?php echo $row1[0]; ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <button type="submit" name="akademisyenekle" class="btn btn-primary">Ekle</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#example').DataTable();
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</body>

</html>