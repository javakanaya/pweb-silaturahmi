<?php
/*include 'config.php';

$sql="select * from admin";
$query=mysqli_query($db,$sql);

while($admin=mysqli_fetch_array($query)):
    echo $admin['nama'];
    echo "\n";
endwhile;*/

if(empty($_POST['nama'])){
    die("Name is required");
}

else if(empty($_POST['password'])){
    die("Password is required");
}

include 'config.php';

$query='select * from admin where nama="Eri Cahyadi"';
$result = mysqli_query($db, $query);
echo $result['nama'];

?>