<?php include 'baglanti.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Dersler</title>
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

  <div class="container" style="width:100%; color:white; background-color:#6c747e; margin-top: 40px; padding-top: 10px; padding-bottom:5px;">
    <a style="margin-left: 600px;" href="#" data-bs-toggle="modal" data-bs-target="#dersEkleModal">
      <button type="button" class="btn btn-warning">Ders Ekle</button>
    </a>

    <table id="example" class="table background:#6c747e" style="width:100%;">
      <thead>
        <tr>
          <th>Ders ID</th>
          <th>Ders Adı</th>
          <th>Ders Sil</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $derssor = $baglanti->prepare("SELECT * FROM dersler");
        $derssor->execute();
        while ($derscek = $derssor->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td><?php echo $derscek['ders_id'] ?></td>
            <td><?php echo $derscek['ders_ad'] ?></td>
            <td><a href="islemler.php?derssil&ders_id=<?php echo $derscek['ders_id'] ?>"><button type="button" class="btn btn-danger">Sil</button></a></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  
  <div class="modal fade" id="dersEkleModal" tabindex="-1" aria-labelledby="dersEkleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dersEkleModalLabel">Ders Ekle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="islemler.php" method="post">
            <div class="form-group">
              <label for="exampleInputEmail1">Ders ID</label>
              <input name="ders_id" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Ders Adı</label>
              <input name="ders_ad" type="text" class="form-control" id="exampleInputPassword1">
            </div>
            <button name="dersekle" type="submit" class="btn btn-primary">Ekle</button>
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

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</body>

</html>
