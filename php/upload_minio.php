<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;


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


    $result = $minio->putObject([

        'Bucket' => 'galeri',

        'Key' => $name,

        'SourceFile' => $file

    ]);


    echo "Upload MinIO berhasil";


} catch(Exception $e){

    echo $e->getMessage();

}

?>
