<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;

// 1. KONEKSI KE DATABASE MYSQL DOCKER
$conn = new mysqli("db_maulana", "maulana", "12345", "galeri");

if ($conn->connect_error) {
    die("Koneksi MySQL Gagal: " . $conn->connect_error);
}

// 2. KONEKSI KE OBJECT STORAGE MINIO
$minio = new S3Client([
    'version' => 'latest',
    'region' => 'us-east-1',
    'endpoint' => 'http://192.168.83.30:9000',
    'use_path_style_endpoint' => true,
    'signature_version' => 'v4',
    'credentials' => [
        'key' => 'admin',
        'secret' => 'password123'
    ]
]);

$file = $_FILES['gambar']['tmp_name'];
$name = $_FILES['gambar']['name'];

try {
    // 3. DETEKSI JENIS FILE GAMBAR SECARA OTOMATIS
    $mime_type = mime_content_type($file);

    // 4. PROSES UPLOAD FILE KE MINIO (Dengan ContentType yang Benar)
    $result = $minio->putObject([
        'Bucket'      => 'galeri',
        'Key'         => $name,
        'SourceFile'  => $file,
        'ContentType' => $mime_type 
    ]);

    // 5. PROSES SIMPAN TEKS DATA KE MYSQL (Kunci Perbaikan Nama Kolom)
    $teks_deskripsi = "File " . $name . " sukses diunggah ke cloud storage MinIO.";
    
    // NAMA KOLOM DIUBAH MENJADI 'deskripsi' SESUAI DATABASE ANDA
    $query = "INSERT INTO gambar (nama_file, deskripsi) VALUES ('$name', '$teks_deskripsi')"; 
    
    if($conn->query($query)){
        header("Location: index.php?status=sukses");
        exit();
    } else {
        echo "Gagal menyimpan teks ke MySQL: " . $conn->error;
    }

} catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
?>
