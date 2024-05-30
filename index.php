<?php

error_reporting(E_ALL);

include './koneksi.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("location:login.php");
}

if (isset($_POST['simpan'])) {
    $id_user = $_SESSION['id'];
    $judul = $_POST['judul'];
    $tgl = date('d F Y', strtotime($_POST['tanggal']));
    $prioritas = $_POST['prioritas'];

    // var_dump($prioritas);

    $sql = mysqli_query($conn, "INSERT INTO todos VALUES (NULL,'$id_user','$judul', '$tgl', '$prioritas', '0')");

    if (!$sql) {
        echo "
            <script>
                alert('Gagal Menambahkan Todo List');
                window.location.href = './index.php';
            </script>
       ";
    } else {

        header("location:index.php");

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO LIST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="./asset/css/style.css">
</head>

<body class="bg-black text-white">

    <div class="container">
        <div class="row justify-content-center">
            <h1 class="text-center fw-bold mt-5">TODO LIST</h4>

                <div class="col-sm-12 col-md-12 col-lg-10">
                    <div class="card shadow-lg bg-dark">
                        <div class="card-header">
                            <h3 class="text-center">
                                TODO LIST <?= isset($_GET['date']) ? date('d F Y', strtotime(htmlspecialchars($_GET['date'], ENT_QUOTES, 'UTF-8'))) : date('d F Y') ?>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            
                                <div class="container">
                                    <div class="row my-3">
                                        <div class="col-4">
                                            <div class="input-group input-group-sm">
                                                <input type="date" class="form-control" id="date">
                                                <button class="btn btn-warning btn-sm" id="filter">
                                                    Filter
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-primary btn-sm" id="tambah">Tambah</button>
                                            <button class="btn btn-sm btn-success" id="riwayat">Todo List Selesai</button>
                                            <button class="btn btn-sm btn-info d-none" id="sekarang">Todo List Sekarang</button>
                                            <a href="./logout.php" class="btn btn-danger btn-sm">Keluar</a>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-hover text-center text-white table-dark">
                                    <thead>
                                        <tr>
                                            <th class="col-1">No</th>
                                            <th class="col-4">Judul</th>
                                            <th class="col-3">Tanggal</th>
                                            <th class="col-2">Prioritas</th>
                                            <th class="col-2">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody id="listSekarang">

                                    <?php

                                    $hariIni = isset($_GET['date']) ? date('d F Y', strtotime(htmlspecialchars($_GET['date'], ENT_QUOTES, 'UTF-8'))) : date('d F Y');

                                    $no = 1;

                                    $id_user = $_SESSION['id'];
                                    
                                    $query = mysqli_query($conn, "SELECT * FROM todos WHERE id_user = '$id_user' AND selesai = '0' AND tgl = '$hariIni' ORDER BY tgl ASC");

                                    while ($data = mysqli_fetch_assoc($query)) {

                                    ?>

                                            <tr>
                                                <td class="col-1"><?= $no++ ?></td>
                                                <td class="col-4"><?= $data['judul']; ?></td>
                                                <td class="col-3"><?= $data['tgl']; ?></td>
                                                <?php
                                                    
                                                    if ($data['prioritas'] === 'tinggi') {

                                                ?>
                                                
                                                    <td class="col-2">
                                                        <span class="badge rounded-pill bg-danger">Tinggi</span>
                                                    </td>
                                                    
                                                <?php 
                                                
                                                    } elseif($data['prioritas'] === 'sedang') {

                                                ?>

                                                <td class="col-2">
                                                    <span class="badge rounded-pill bg-warning">Sedang</span>
                                                </td>

                                                <?php 
                                                
                                                    } else {
                                                
                                                ?>

                                                <td class="col-2">
                                                    <span class="badge rounded-pill bg-primary">Rendah</span>
                                                </td>

                                                <?php 
                                                
                                                    }
                                                
                                                ?>
                                                <td class="col-2">
                                                    <?php 

                                                        $hariIni = date('d F Y');
                                                    
                                                        if ($hariIni < $data['tgl']) {
                                                            
                                                    ?>

                                                    <button class="btn btn-sm btn-outline-danger" disabled>Selesai</button>

                                                    <?php } else if($hariIni > $data['tgl']) { ?>

                                                    <button class="btn btn-sm btn-outline-warning" disabled>Tertunda</button>

                                                    <?php } else { ?>

                                                        <a href="./selesai.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-outline-success">Selesai</a>

                                                    <?php } ?>

                                                </td>
                                            </tr>

                                    <?php } ?>

                                    </tbody>

                                    <tbody class="d-none" id="listRiwayat">

                                        <?php

                                        $hariIni = isset($_GET['date']) ? date('d F Y', strtotime(htmlspecialchars($_GET['date'], ENT_QUOTES, 'UTF-8'))) : date('d F Y');

                                        $no = 1;

                                        $id_user = $_SESSION['id'];

                                        $query = mysqli_query($conn, "SELECT * FROM todos WHERE id_user = '$id_user' AND selesai = '1' AND tgl = '$hariIni' ORDER BY tgl ASC");

                                        while ($data = mysqli_fetch_assoc($query)) {

                                        ?>

                                                <tr>
                                                    <td class="col-1"><?= $no++ ?></td>
                                                    <td class="col-4"><?= $data['judul']; ?></td>
                                                    <td class="col-3"><?= $data['tgl']; ?></td>
                                                    <?php
                                                    
                                                        if ($data['prioritas'] === 'tinggi') {

                                                    ?>
                                                    
                                                        <td class="col-2">
                                                            <span class="badge rounded-pill bg-danger">Tinggi</span>
                                                        </td>
                                                      
                                                    <?php 
                                                    
                                                        } elseif($data['prioritas'] === 'sedang') {

                                                    ?>

                                                    <td class="col-2">
                                                        <span class="badge rounded-pill bg-warning">Sedang</span>
                                                    </td>

                                                    <?php 
                                                    
                                                        } else {
                                                    
                                                    ?>

                                                    <td class="col-2">
                                                        <span class="badge rounded-pill bg-primary">Rendah</span>
                                                    </td>

                                                    <?php 
                                                    
                                                        }
                                                    
                                                    ?>
                                                    
                                                    <td class="col-2">
                                                        <button type="button" class="btn btn-sm btn-success" disabled>Selesai</button>
                                                    </td>
                                                </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </div>

    <!-- Modal Register -->
    <div class="modal fade" id="Mtambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Todo List</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="" method="post">
                            <div class="mb-4">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" class="form-control" placeholder="Masukkan Judul" required id="judul" name="judul">
                            </div>
                            <div class="mb-4">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" placeholder="Masukkan Tanggal" required id="tanggal" name="tanggal">
                            </div>
                            <div class="mb-4">
                                <label for="prioritas" class="form-label">Prioritas</label>
                                <select name="prioritas" id="prioritas" class="form-select text-center" required>
                                    <option value="" selected class="text-center">----- Pilih Prioritas -----</option>
                                    <option value="tinggi" class="text-center">Tinggi</option>
                                    <option value="sedang" class="text-center">Sedang</option>
                                    <option value="rendah" class="text-center">Rendah</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary" name="simpan">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div> -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {

            let dateValue = getQueryParam('date')

            $('#date').val(dateValue)

            $('#tambah').click(function() {
                $('#Mtambah').modal('show');
            })

            $('#riwayat').click(function (e) { 
                e.preventDefault();
                
                $(this).addClass('d-none');
                $('#sekarang').removeClass('d-none');
                $('#listSekarang').addClass('d-none');
                $('#listRiwayat').removeClass('d-none');

            });
            
            $('#sekarang').click(function (e) { 
                e.preventDefault();
                
                $(this).addClass('d-none');
                $('#riwayat').removeClass('d-none');
                $('#listRiwayat').addClass('d-none');
                $('#listSekarang').removeClass('d-none');

            });

            $('#filter').click(function () {
                let protocol = window.location.protocol
                let hostname = window.location.hostname
                let pathname = window.location.pathname

                window.location.href = `${protocol}//${hostname}${pathname}?date=` + $('#date').val()
            })

            function getQueryParam(param) {
                let url = new URL(window.location.href)
                let params = new URLSearchParams(url.search)

                return params.get(param)
            }
        })
    </script>

</body>

</html>