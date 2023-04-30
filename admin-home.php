<?php
session_start();

if(isset($_SESSION['user_id'])){
    include 'config.php';

    $sql_get_pejabat="select * from admin where id={$_SESSION['user_id']}";
    $query_get_pejabat=mysqli_query($db,$sql_get_pejabat);
    $admin=mysqli_fetch_array($query_get_pejabat);

    $sql_get_pesan_belum="select w.nama, p.waktu_kirim, p.isi, p.id, p.pengirim
                    from pesan p, warga w, admin a 
                    where p.pengirim=w.id and
                    p.pejabat_tujuan=a.id and 
                    pejabat_tujuan={$admin['id']}
                    and balasan=''";
    $query_get_pesan_belum=mysqli_query($db,$sql_get_pesan_belum);
    //$pesan=mysqli_fetch_array($query_get_pesan);

    $sql_get_pesan_sudah="select w.nama, p.waktu_kirim, p.isi, p.balasan, p.waktu_balas
                    from pesan p, warga w, admin a 
                    where p.pengirim=w.id and
                    p.pejabat_tujuan=a.id and 
                    pejabat_tujuan={$admin['id']}
                    and balasan!=''";
    $query_get_pesan_sudah=mysqli_query($db,$sql_get_pesan_sudah);

    //reply
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['balas-pesan'])) {
        $id_admin = $_SESSION["user_id"];
        $id_warga = $_POST['warga'];
        $isi_balasan = $_POST['isi-balasan'];
        $id_pesan = $_POST['id'];

        $sql="update pesan set balasan='$isi_balasan',
                waktu_balas=NOW() where id='$id_pesan'";
        $query=mysqli_query($db,$sql);

        if ($query) {
            header("Location: admin-home.php");
            exit;
        } else {
            die($mysqli->error . " " . $mysqli->errno);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>

    <style>
        .profile{
            display:flex;
            align-items:center;
        }
        .profile:hover{
            cursor: pointer;
        }
        .profile img{
            height: 40px;
            margin-right:15px;
        }
        h6{
            font-size: 16px;
            color:#efefef;
        }
    </style>
</head>
<body>
        <?php if (isset($admin)) : ?>
        <!-- Navbar -->
        <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
            <div class="container-fluid">
                <div class="profile">
                    <img src="./img/profile-picture.png">
                    <h6><?php echo $admin['nama'] ?></h6>
                </div>
                <a class="btn btn-danger" href="logout.php">log out</a>
            </div>
        </nav>

        <h2 class="container m-3 h2 fw-bold">Pesan belum dibalas</h2>

        <!-- pesan-pesan -->
        <div class="mx-3 mb-5 row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            <?php while ($row = mysqli_fetch_array($query_get_pesan_belum)) {?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <form id="form-pesan-baru" action="" method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                <input type="hidden" name="warga" value="<?php echo $row['pengirim'] ?>">
                                <div class="form-group">
                                    <h5 class="card-title"> <?php echo $row['nama'] ?> </h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['waktu_kirim'] ?></h6>
                                    <p class="card-text"> <?php echo $row['isi'] ?> </p>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="isi-balasan" class="col-form-label">Balas:</label>
                                    <textarea class="form-control" id="isi-balasan" name="isi-balasan"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary float-end" name="balas-pesan">Balas</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>

        <h2 class="container m-3 h2 fw-bold">Pesan sudah dibalas</h2>

        <div class="mx-3 mb-5 row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            <?php while ($row = mysqli_fetch_array($query_get_pesan_sudah)) {?>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"> <?php echo $row['nama'] ?> </h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['waktu_kirim'] ?></h6>
                            <p class="card-text"> <?php echo $row['isi'] ?> </p>
                            <label for="isi-balasan" class="col-form-label">Balasan:</label>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['waktu_balas'] ?></h6>
                            <p class="card-text"> <?php echo $row['balasan'] ?> </p>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>

        <?php else : ?>
            <a href="admin-login.php">Login terlebih dahulu</a>
        <?php endif; ?>
</body>
</html>