<?php

$conn = new mysqli(
"db_maulana",
"maulana",
"12345",
"galeri"
);


if(isset($_POST['upload'])){


$file=$_FILES['gambar']['name'];

$tmp=$_FILES['gambar']['tmp_name'];


move_uploaded_file(
$tmp,
"uploads/".$file
);


$conn->query(

"INSERT INTO gambar(nama_file)

VALUES('$file')"

);


}


$data=$conn->query(
"SELECT * FROM gambar"
);


?>


<h1>Galeri Cloud</h1>


<form method="post" enctype="multipart/form-data">

<input type="file" name="gambar">

<button name="upload">

Upload

</button>

</form>


<hr>


<?php while($row=$data->fetch_assoc()){ ?>


<img width="200"

src="uploads/<?php echo $row['nama_file'];?>">


<br>


<a href="uploads/<?php echo $row['nama_file'];?>">

Download

</a>


<hr>


<?php } ?>
