<?php 

$wrongpass=false;
$wrongname=false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    include 'config.php';

    $nama=$_POST['nama'];

    $sql="select * from admin where nama='$nama'";
    $query=mysqli_query($db,$sql);
    $admin=mysqli_fetch_array($query);

    if($admin){
        if(password_verify($_POST['password'],$admin['password'])){
            session_start();

            $_SESSION['user_id']=$admin['id'];

            header('Location: admin-home.php');
            exit;
        }
        else{
            $wrongpass=true;
            $wrongname=false;
        }
    }
    else{
        $wrongpass=false;
        $wrongname=true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar bg-dark navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled">Disabled</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Masuk ke akun admin</h2>

        <form method="post">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="nama" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($_POST["nama"] ?? "")  ?>">
                <?php if ($wrongname): ?>
                    <em style="color:red;">Wrong name!</em>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Password</label>
                <input type="password" class="form-control" id="pass" name="password">
                <?php if ($wrongpass): ?>
                    <em style="color:red;">Wrong password!</em>
                <?php endif; ?>
            </div>

            <button class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>