<?php

session_start();

if (isset($_SESSION["user_id"])) {

    include("./config.php");

    $sql_get_user = "SELECT * FROM warga WHERE id = {$_SESSION["user_id"]}";

    $result = mysqli_query($db,  $sql_get_user);

    $user = $result->fetch_assoc();

    // submit pesan baru
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['pesan-baru'])) {
        $id_pengirim = $_SESSION["user_id"];
        $id_penerima = $_POST["id-penerima"];
        $isi = htmlspecialchars($_POST["isi-pesan"]);

        $sql = "INSERT INTO pesan (pengirim, pejabat_tujuan, isi, waktu_kirim) VALUES ('$id_pengirim', '$id_penerima', '$isi', NOW())";
        $query = mysqli_query($db,  $sql);

        if ($query) {
            header("Location: warga-home.php");
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
    <title>Silaturahmi Warga</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

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
    <?php if (isset($user)) : ?>
        <!-- Navbar -->
        <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary vw-100" data-bs-theme="dark">
            <div class="container-fluid">
                <div class="profile">
                    <img src="./img/profile-picture.png">
                    <h6><?php echo $user['nama'] ?></h6>
                </div>
                <a class="btn btn-danger" href="logout.php">log out</a>
            </div>
        </nav>

        <h2 class="container m-3 h2 fw-bold">Hello "<?= htmlspecialchars($user["nama"]) ?>"</h2>
        <div class="mx-3 d-flex flex-row mb-3">
            <button type="button" class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#pesanbaru">+ Pesan Baru</button>
        </div>

        <!-- modal form -->
        <div class="modal fade" id="pesanbaru" tabindex="-1" aria-labelledby="modal-pesan-baru" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal-pesan-baru">Pesan Baru</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-pesan-baru" action="" method="POST">
                            <div class="mb-3">
                                <label for="id-penerima" class="col-form-label">Kepada:</label>
                                <?php
                                $sql_get_pejabat = "SELECT id, nama FROM admin";

                                $pejabats = mysqli_query($db,  $sql_get_pejabat);
                                ?>
                                <select class="form-select" id="id-penerima" name="id-penerima">
                                    <?php
                                    while ($row = mysqli_fetch_array($pejabats)) {
                                        echo  "<option value=" . $row['id'] . ">" . $row['nama'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="isi-pesan" class="col-form-label">Pesan:</label>
                                <textarea class="form-control" id="isi-pesan" name="isi-pesan"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="form-pesan-baru" class="btn btn-primary" name="pesan-baru">Kirim Pesan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- pesan-pesan -->
        <div class="mx-3 mb-5 row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
            <?php
            $sql_get_pesan = "SELECT p.isi, a.nama FROM pesan p JOIN admin a ON p.pejabat_tujuan = a.id where p.pengirim = {$_SESSION["user_id"]}";

            $pesans = mysqli_query($db,  $sql_get_pesan);

            while ($row = mysqli_fetch_array($pesans)) {
                echo "<div class='col'>";
                echo "<div class='card h-100'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . $row['nama'] . "</h5>";
                echo "<p class='card-text' >" . $row['isi'] . "</p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                // echo  "<option value=" . $row['isi'] . ">" . $row['nama'] . "</option>";
            }
            ?>
        <?php else : ?>
            <?php header("Location: index.php"); ?>
        <?php endif; ?>
</body>

</html>