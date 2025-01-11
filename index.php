<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dirmawa";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$nim = "";
$nama = "";
$alamat = "";
$beasiswa = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op =='delete'){
    $id = $_GET['id'];
    $sql1 = "delete from beasiswa where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if($q1){
        $sukses = "Data berhasil dihapus";
    }else{
        $error = "gagal melakukan delete";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "select * from beasiswa where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $nim = $r1['nim'];
    $nama = $r1['nama'];
    $alamat = $r1['alamat'];
    $beasiswa = $r1['beasiswa'];

    if ($nim == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $beasiswa = $_POST['beasiswa'];

    if ($nim && $nama && $alamat && $beasiswa) {
        if ($op == 'edit') { //untuk update
            $sql1 = "update beasiswa set nim = '$nim', nama='$nama',alamat = '$alamat',beasiswa='$beasiswa' where id = '$id'";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1 = "insert into beasiswa(nim,nama,alamat,beasiswa) values ('$nim','$nama','$alamat','$beasiswa')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "berhasil memasukkan data baru";
            } else {
                $error = "gagal memasukkan data baru";
            }
        }

    } else {
        $error = "silahkan isi semua data";
    }

}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Beasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data-->
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
                    header("refresh:5;url=index.php");//5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                    ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                    <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat"
                                value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="beasiswa" class="col-sm-2 col-form-label">Jenis beasiswa</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="beasiswa" id="beasiswa">
                                <option value="">-Pilih Jenis Beasiswa -</option>
                                <option value="Bidikmisi" <?php if ($beasiswa == "Bidikmisi")
                                    echo "selected" ?>>Bidikmisi
                                    </option>
                                    <option value="KIP-K" <?php if ($beasiswa == "KIP-K")
                                    echo "selected" ?>>KIP-K</option>
                                    <option value="Prestasi" <?php if ($beasiswa == "Prestasi")
                                    echo "selected" ?>>Prestasi
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="submit" name="simpan" value="Simpan data" class="btn btn-primary" />
                        </div>

                    </form>
                </div>
            </div>

            <!-- untuk mengeluarkan data-->
            <div class="card">
                <div class="card-header text-white bg-secondary">
                    Data beasiswa
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">NIM</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Jenis Beasiswa</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        <tbody>
                            <?php
                                $sql2 = "select * from beasiswa order by id desc";
                                $q2 = mysqli_query($koneksi, $sql2);
                                $urut = 1;
                                while ($r2 = mysqli_fetch_array($q2)) {
                                    $id = $r2['id'];
                                    $nim = $r2['nim'];
                                    $nama = $r2['nama'];
                                    $alamat = $r2['alamat'];
                                    $beasiswa = $r2['beasiswa'];

                                    ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $nim ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $beasiswa ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                            <?php
                                }
                                ?>
                    </tbody>
                    </thead>

                </table>
            </div>
</body>

</html>