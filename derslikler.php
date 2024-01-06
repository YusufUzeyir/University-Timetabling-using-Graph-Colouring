<?php include 'baglanti.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Sınıflar</title>
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
    <table id="example" class="table background:#6c747e" style="width:100%;">
      <thead>
        <tr>
          <th>Sınıf ID</th>
          
        </tr>

      <tbody>
        <?php
        $sinifsor = $baglanti->prepare("SELECT * FROM derslikler");
        $sinifsor->execute();
        while ($sinifcek = $sinifsor->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <tr>
            <td><?PHP echo $sinifcek['derslik_id'] ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
      </thead>
    </table>
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