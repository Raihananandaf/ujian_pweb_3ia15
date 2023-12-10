<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "akademik";


$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) { //cek koneksi
    die("tidak bisa terkonesi ke database");
}

$NPM = "";
$NAMA = "";
$ALAMAT = "";
$FAKULTAS = "";
$sukses = "";
$error = "";

if (isset($_GET["op"])) {
    $op = $_GET["op"];
} else {
    $op = "";
}
if($op == "delete") {
    $id     = $_GET["id"];
    $sql1   = "delete from mahasiswa where id = '$id '";
    $q1     = mysqli_query($koneksi, $sql1);
    if($q1){
        $sukses  = "Berhasil hapus data";
    }else{
        $error  = "Gagal menghapus data";
    }
}

if ($op == "edit") {
    $id = $_GET["id"];
    $sql1 = "select * from mahasiswa where id = '$id' ";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $NPM = $r1["NPM"];
    $NAMA = $r1["NAMA"];
    $ALAMAT = $r1["ALAMAT"];
    $FAKULTAS = $r1["FAKULTAS"];

    if ($NPM == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST["simpan"])) { // untuk create
    $NPM = $_POST["NPM"];
    $NAMA = $_POST["NAMA"];
    $ALAMAT = $_POST["ALAMAT"];
    $FAKULTAS = $_POST["FAKULTAS"];

    if ($NPM && $NAMA && $ALAMAT && $FAKULTAS) {
        if ($op == 'edit') {    // update data
            $sql1 = "update mahasiswa set NPM = '$NPM', NAMA = '$NAMA', ALAMAT = '$ALAMAT', FAKULTAS = '$FAKULTAS' WHERE id ='$id'";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Gagal mengupdate data";
            }
        } else { // insert data
            $sql1 = "insert into mahasiswa (NPM,NAMA,ALAMAT,FAKULTAS) values ('$NPM',  '$NAMA' , '$ALAMAT', '$FAKULTAS' )";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error = "Gagal memasukkan data baru";
            }
        }

    } else {
        $error = "Sihlakan masukan semua data";
    }
}
?>

<!-- FORMAT html-->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raihan_51421226</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 10px;
        }
    </style>

</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php 
                    header("refresh:5;url=index.php"); // 5 detik
                }
                ?>

                <?php
                if ($sukses) {
                    ?>
                    <div class="alert alert-sukses" role="alert">
                        <?php echo $sukses ?>
                    </div>
                 <?php
                   header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="NPM" class="col-sm-2 col-form-label">NPM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="NPM" name="NPM" value="<?php echo $NPM ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="NAMA" class="col-sm-2 col-form-label">NAMA</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="NAMA" name="NAMA" value="<?php echo $NAMA ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="ALAMAT" class="col-sm-2 col-form-label">ALAMAT</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="ALAMAT" name="ALAMAT"
                                value="<?php echo $ALAMAT ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="FAKULTAS" class="col-sm-2 col-form-label">FAKULTAS</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="FAKULTAS" id="FAKULTAS">
                                <option value="">- pilih fakultas - </option>
                                <option value="fasilkom" <?php if ($FAKULTAS == " fasilkom")
                                    echo "selected" ?>>FASILKOM
                                    </option>
                                    <option value="fti" <?php if ($FAKULTAS == " fti")
                                    echo "selected" ?>>FTI </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="submit" name="simpan" value="Simpan Data" class="btn-btn-primary">
                        </div>
                    </form>

                </div>
            </div>

            <!-- untuk mengeluarkan data -->

            <div class="card">
                <div class="card-header text-white bg-secondary">
                    Data mahasisawa
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">NPM</th>
                                <th scope="col">NAMA</th>
                                <th scope="col">ALAMAT</th>
                                <th scope="col">FAKULTAS</th>
                                <th scope="col">AKSI</th>
                            </tr>
                        <tbody>
                            <?php
                                $sql2 = "select * from mahasiswa order by id desc ";
                                $q2 = mysqli_query($koneksi, $sql2);
                                $urut = 1;
                                while ($r2 = mysqli_fetch_array($q2)) {
                                    $id = $r2["id"];
                                    $NPM = $r2["NPM"];
                                    $NAMA = $r2["NAMA"];
                                    $ALAMAT = $r2["ALAMAT"];
                                    $FAKULTAS = $r2["FAKULTAS"];


                                    ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?php echo $NPM ?>
                                </td>
                                <td scope="row">
                                    <?php echo $NAMA ?>
                                </td>
                                <td scope="row">
                                    <?php echo $ALAMAT ?>
                                </td>
                                <td scope="row">
                                    <?php echo $FAKULTAS ?>
                                </td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button"
                                            class="btn btn-danger">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('yakin mau delete')"><button type="button" class="btn btn-warning">Delete</button></a>
                                    

                                </td>
                            </tr>
                            <?php

                                }
                                ?>
                    </tbody>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</body>

</html>