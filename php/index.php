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

if(!is_dir("uploads")){
    mkdir("uploads",0777,true);
}

move_uploaded_file(

$tmp,

"uploads/".$file

);



$conn->query(

"INSERT INTO gambar(nama_file)

VALUES('$file')"

);



echo "Upload berhasil";


}



$result=$conn->query(

"SELECT * FROM gambar"

);


?>


<h2>
Galeri Cloud Maulana
</h2>



<form action="upload_minio.php" method="post" enctype="multipart/form-data">


<input type="file" name="gambar">


<button name="submit">

Upload

</button>


</form>



<hr>



<?php while($row=$result->fetch_assoc()){ ?>


<img width="200"

src="uploads/<?php echo $row['nama_file'];?>">


<br>


<a download

href="uploads/<?php echo $row['nama_file'];?>">

Download

</a>


<hr>


<?php } ?>
