<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if(!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}
$npm = "";
$nama = "";
$alamat = "";
$fakultas = "";
$sukses = "";
$error = "";

if(isset($_GET["op"])) {
    $op = $_GET["op"];
} else {
    $op = "";
}

if($op == "delete") {
    $id = $_GET["id"];
    $sql1 = "DELETE FROM mahasiswa WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);

    if($q1) {
        $sukses = "Data berhasil dihapus";
    } else {
        $error = "Gagal melakukan delete data";
    }
}


if($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM mahasiswa WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);

    if($q1 && mysqli_num_rows($q1) > 0) {
        $r1 = mysqli_fetch_array($q1);
        $npm = $r1['npm'];
        $nama = $r1['nama'];
        $alamat = $r1['alamat'];
        $fakultas = $r1['fakultas'];
    } else {
        $error = "Data Tidak ditemukan";
    }
}

if(isset($_POST["simpan"])) { //untuk create
    $npm = $_POST["npm"];
    $nama = $_POST["nama"];
    $alamat = $_POST["alamat"];
    $fakultas = $_POST["fakultas"];

    if($npm && $nama && $alamat && $fakultas) {
        if($op == 'edit') { // untuk update
            $sql1 = "UPDATE mahasiswa SET npm = '$npm', nama='$nama', alamat='$alamat', fakultas='$fakultas' WHERE id ='$id'";
            $q1 = mysqli_query($koneksi, $sql1);
            if($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else { // untuk insert
            $sql1 = "INSERT INTO mahasiswa (npm, nama, alamat, fakultas) VALUES ('$npm','$nama','$alamat','$fakultas')";
            $q1 = mysqli_query($koneksi, $sql1);
            if($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silahkan Masukkan Semua Data";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .max-auto {
            width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="max-auto">
        <!--untuk memasukan data-->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Create / Edit Data
            </div>
            <div class="card-body ">
                <?php
                if($error) {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                    <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <?php
                if($sukses) {
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
                        <label for="npm" class="form-label">NPM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="npm" name="npm" value="<?php echo $npm ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat"
                                value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="fakultas" class="form-label">Fakultas</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="fakultas" id="fakultas">
                                <option value="">- Pilih Fakultas -</option>
                                <option value="FTI" <?php if($fakultas == "FTI")
                                    echo "selected" ?>>FTI</option>
                                    <option value="FE" <?php if($fakultas == "FE")
                                    echo "selected" ?>>FE</option>
                                    <option value="FIKOM" <?php if($fakultas == "FIKOM")
                                    echo "selected" ?>>FIKOM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="submit" name="simpan" value="Simpan data" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>
            <!--untuk mengeluarkan data-->
            <div class="card">
                <div class="card-header text-white bg-secondary">
                    Data Mahasiswa
                </div>
                <div class="card-body ">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">NPM</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Fakultas</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        <tbody>
                            <?php
                                $sql2 = "SELECT * from mahasiswa order by id desc";
                                $q2 = mysqli_query($koneksi, $sql2);
                                $urut = 1;
                                while($r2 = mysqli_fetch_array($q2)) {
                                    $id = $r2["id"];
                                    $npm = $r2["npm"];
                                    $nama = $r2["nama"];
                                    $alamat = $r2["alamat"];
                                    $fakultas = $r2["fakultas"];

                                    ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?php echo $npm ?>
                                </td>
                                <td scope="row">
                                    <?php echo $nama ?>
                                </td>
                                <td scope="row">
                                    <?php echo $alamat ?>
                                </td>
                                <td scope="row">
                                    <?php echo $fakultas ?>
                                </td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button"
                                            class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>"
                                        onclick="return confirm ('apakah anda yakin?')"><button type="button"
                                            class="btn btn-danger">Delete</button></a>
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