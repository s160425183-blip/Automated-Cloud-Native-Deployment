<?php
// 1. KONEKSI DATABASE MYSQL DOCKER
$conn = new mysqli("db_maulana", "maulana", "12345", "galeri");

if ($conn->connect_error) {
    die("Koneksi MySQL Gagal: " . $conn->connect_error);
}

// 2. SETTING URL ENDPOINT PUBLIK MINIO
// Pastikan policy bucket 'galeri' di MinIO sudah diatur ke 'Public' atau 'download'
$minio_public_url = "http://maulana.local:9000/galeri/";

// Ambil semua data dari database diurutkan dari yang terbaru
$result = $conn->query("SELECT * FROM gambar ORDER BY id DESC"); // sesuaikan kolom jika tidak ada id
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Galeri Cloud Maulana</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .gallery-container { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        .gallery-item { padding: 15px; border: 1px solid #ddd; border-radius: 8px; width: 220px; text-align: center; }
        .gallery-item img { width: 100%; height: 150px; object-fit: cover; border-radius: 4px; display: block; margin-bottom: 10px; }
        .gallery-item a { text-decoration: none; color: blue; font-weight: bold; }
    </style>
</head>
<body>

<div style="text-align: center; margin-top: 20px; margin-bottom: 30px;">
    <h1 style="color: #333; font-family: Arial, sans-serif; letter-spacing: 1px;">
        Automated Cloud-Native Deployment
    </h1>
    <p style="color: #666; font-style: italic;">
        Integrasi PHP, MySQL Docker, & MinIO Object Storage
    </p>
    <p style="color: #666; font-style: italic;">
        Maulana Ikromullah
    </p>
    <p style="color: #666; font-style: italic;">
        160425183
    </p>
</div>


    <!-- Form melempar data ke upload_minio.php -->
    <form action="upload_minio.php" method="post" enctype="multipart/form-data">
        <input type="file" name="gambar" required>
        <button name="submit" type="submit">Upload</button> 
    </form>

    <hr>

    <div class="gallery-container">
        <?php 
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()){ 
                // Otomatis mengarahkan ke URL MinIO berdasarkan nama file di database MySQL
                $url_gambar_minio = $minio_public_url . $row['nama_file'];
        ?>
                <div class="gallery-item">
                    <!-- Memuat Gambar Langsung dari Cloud MinIO -->
                    <img src="<?php echo $url_gambar_minio; ?>" alt="<?php echo $row['nama_file']; ?>">
                    
                    <p style="font-size: 12px; margin: 5px 0;"><b><?php echo $row['nama_file']; ?></b></p>
                    <p style="font-size: 11px; color: #555;"><?php echo isset($row['deskripsi']) ? $row['deskripsi'] : ''; ?></p>
                    
                    <a href="<?php echo $url_gambar_minio; ?>" target="_blank" download>Download</a>
                </div>
        <?php 
            }
        } else {
            echo "<p>Belum ada gambar di database.</p>";
        } 
        ?>
    </div>

</body>
</html>
