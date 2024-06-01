<!-- untuk koneksi -->


<?php
$host   = "localhost";
$user   = "root";
$pass   = "";
$db     = "akademik";

$koneksi    = mysqli_connect($host,$user,$pass,$db);
if(!$koneksi){

//cek koneksi
    die("Gagal Terkoneksi");
}
$nim ="";
$nama  = "";
$alamat ="";
$prodi = "";
$sukses = "";
$error = "";


// Menangani parameter 'op' di URL
if(isset($_GET['op'])){
    $op = $_GET['op'];
}else{
    $op = "";
}

// Bagian Delete
if($op == 'delete'){
    $id     = $_GET['id'];
    $sql1   = "delete from mahasiswa where id = '$id'";
    $q1     = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Data Terhapus";
    }else{
        $error  = "Data Gagal dihapus";
    }
}

// Bagian Edit (untuk mendapatkan data yang akan diubah)
if($op =='edit'){
    $id     = $_GET['id'];
    $sql1   = "select * from mahasiswa where id = '$id'";
    $q1     = mysqli_query($koneksi,$sql1);
    $r1     = mysqli_fetch_array($q1);
    $nim    = $r1['nim'];
    $nama   = $r1['nama'];
    $alamat = $r1['alamat'];
    $prodi  = $r1['prodi'];

    if($nim == ''){
        $error = "Data tidak ditemukan";
    }
}
// Bagian Create dan Update
if(isset($_POST['simpan'])){
    $nim    = $_POST['nim'];
    $nama   = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $prodi  = $_POST['prodi'];

    if($nim && $nama && $alamat && $prodi){
        // bagian update
        if($op == 'edit'){
            $sql1   ="update mahasiswa set nim ='$nim',nama = '$nama',alamat = '$alamat',prodi = '$prodi' where id = '$id'";
            $q1     = mysqli_query($koneksi,$sql1);
            if($sql1){
                $sukses = "Data berhasil diubah";
            }else{
                $error = "Data gagal diubah";
            }
        }else
        // bagian insert 
        {
            $sql1 = "insert into mahasiswa(nim, nama, alamat, prodi) values ('$nim','$nama','$alamat','$prodi')";
            $q1   = mysqli_query($koneksi,$sql1);
        if($q1){
            $sukses     = "Berhasil menambahkan data";
        }else{
            $error      = "Gagal menambahkan data";
        }
        }
        
    }else{
        $error = "Silahkan Input Ulang";
    }
}
?>

<!-- tampilan web -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" >
    <style>
        .mx-auto {
            width:800px 
        }
        .card { margin-top: 10px}
    </style>

</head>
<body>
<div class="mx-auto">

<!-- untuk menambahkan data -->
    <div class="card">
        <div class="card-header">
            Create / Edit Data
        </div>
        <div class="card-body">
            <?php
            if($error){
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error?>
                </div>
            <?php
                header("refresh:5;url=index.php");
            }
            ?>
            <?php
            if($sukses){
                ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses?>
                </div>
            <?php
                header("refresh:5;url=index.php");
            }
            ?>
            <form action="" method="POST">
            <div class="mb-3 row">
                <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                <div class="col-sm-10">
                    <input type="text"  class="form-control" id="nim" name="nim" value="<?php echo $nim?>">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="nama" class="col-sm-2 col-form-label">NAMA</label>
                <div class="col-sm-10">
                    <input type="text"  class="form-control" id="nim" name="nama" value="<?php echo $nama?>">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="alamat" class="col-sm-2 col-form-label">ALAMAT</label>
                <div class="col-sm-10">
                    <input type="text"  class="form-control" id="alamat" name="alamat" value="<?php echo $alamat?>">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="prodi" class="col-sm-2 col-form-label">PROGRAM STUDI</label>
                <div class="col-sm-10">
                    <select class="form-control" name="prodi" id="prodi">
                        <option value="">
                            - Pilih Prodi -
                        </option>
                        <option value="pjj informatika" <?php if($prodi == "pjj informatika") echo "selected" ?>>
                            PJJ Informatika
                        </option>
                        <option value="pjj sistem informasi" <?php if($prodi == "pjj sistem informasi") echo "selected" ?>>
                            PJJ Sistem Informasi
                        </option>
                        <option value="pjj sains data" <?php if($prodi == "pjj sains data") echo "selected" ?>>
                            PJJ Sains Data
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <input type="submit" name="simpan" value="simpan data" class="btn btn-primary">
            </div>
            </form>
        </div>
    </div>
    <!-- untuk tampilkan data -->
    <div class="card">
        <div class="card-header text-white bg-secondary">
            Data Mahasiswa
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NIM</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Prodi</th>
                        <th scope="col">Aksi</th>
                    </tr>
                    <tbody>
                        <?php 
                        $sql2   = "select * from mahasiswa order by id desc";
                        $q2     = mysqli_query($koneksi,$sql2);
                        $urut   = 1;
                        while($r2 = mysqli_fetch_array($q2)){
                            $id = $r2['id'];
                            $nim = $r2['nim'];
                            $nama = $r2['nama'];
                            $alamat = $r2['alamat'];
                            $prodi = $r2['prodi'];
                        
                        ?>
                        <tr>
                            <th scope="row"><?php echo $urut++ ?></th>
                            <td scope="row"><?php echo $nim ?></td>
                            <td scope="row"><?php echo $nama ?></td>
                            <td scope="row"><?php echo $alamat ?></td>
                            <td scope="row"><?php echo $prodi ?></td>
                            <td scope="row">
                                <a href="index.php?op=edit&id=<?php echo $id?>">
                                    <button type="button" class="btn btn-warning">Edit</button>
                                </a>
                                <a href="index.php?op=delete&id=<?php echo $id?>" onclick="return confirm('Sudahkah anda yakin?')">
                                    <button type="button" class="btn btn-danger">Delete</button>
                                </a>
                                
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