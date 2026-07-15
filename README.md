# 📸 Automated Cloud-Native Deployment

Proyek ini adalah aplikasi Galeri Cloud berbasis PHP yang berjalan di atas infrastruktur Docker Compose dengan arsitektur 3 kontainer (PHP, MySQL, dan MinIO S3 Storage).

---

## 🏗️ Arsitektur Sistem

Aplikasi ini berjalan menggunakan 3 layanan utama dalam jaringan privat `192.168.83.0/24`:

1. **`php_maulana`** (`192.168.83.10`): Kontainer frontend/backend PHP yang menjalankan aplikasi utama.
2. **`db_maulana`** (`192.168.83.20`): Kontainer basis data MySQL untuk menyimpan metadata teks foto.
3. **`minio_maulana`** (`192.168.83.30`): Kontainer Object Storage (S3 kompatibel) untuk menyimpan file gambar secara otomatis.

---

## 🛠️ Persyaratan Sistem

Sebelum menjalankan proyek, pastikan perangkat Anda sudah terinstal:
* Docker Engine.
* Docker Compose (v2.0+).

---

## 🚀 Langkah Instalasi & Menjalankan

### 1. Persiapan File Hosts (Penting!)
Agar domain lokal dapat diakses, tambahkan konfigurasi berikut pada file `hosts` sistem operasi Anda:

* **Windows**: `C:\Windows\System32\drivers\etc\hosts`
* **Linux / MacOS**: `/etc/hosts`

Tambahkan baris ini di bagian paling bawah:
```text
192.168.18.1 maulana.local
```
*Catatan: untuk ip addres di isi dengan ip servernya disini saya menggunakan ip tersebut*

### 2. Struktur Direktori Proyek
Pastikan struktur folder Anda sebelum menjalankan Docker adalah sebagai berikut:
```text
├── php/
│   ├── Dockerfile
│   └── index.php
├── mysql/
│   └── init.sql
└── docker-compose.yml
```

### 3. Menjalankan Kontainer
Buka terminal di direktori proyek ini, kemudian jalankan perintah berikut:
```bash
docker compose up -d
```
*Catatan: Perintah ini akan mengunduh image, membangun container PHP, dan menjalankannya di latar belakang (detached mode).*

---

## 🔗 Informasi Akses Layanan

Setelah semua kontainer berhasil berjalan, Anda dapat mengakses layanan melalui informasi berikut:

| Nama Layanan | URL Akses | IP Internal | Kredensial Kontainer |
| :--- | :--- | :--- | :--- |
| **Aplikasi PHP** | [http://maulana.local:8080](http://maulana.local:8080) | `192.168.83.10` | - |
| **Console MinIO** | [http://maulana.local:9001](http://maulana.local:9001) | `192.168.83.30` | **User**: `admin`<br>**Password**: `password123` |
| **Database MySQL** | *Akses Internal DNS:* `db_maulana` | `192.168.83.20` | **DB**: `galeri`<br>**User**: `maulana`<br>**Pass**: `12345`<br>**Root Pass**: `root` |

---

## ⚙️ Konfigurasi Koneksi Aplikasi (PHP)

Gunakan informasi berikut di dalam kode program PHP Anda untuk menghubungkan antar-layanan:

### Koneksi Database (PDO / MySQLi)
* **Host**: `db_maulana` (atau gunakan IP `192.168.83.20`)
* **Database**: `galeri`
* **Username**: `maulana`
* **Password**: `12345`

### Koneksi Storage (AWS SDK / S3 Client)
* **Endpoint**: `http://minio_maulana:9000` (atau gunakan IP `192.168.83.30:9000`)
* **Access Key**: `admin`
* **Secret Key**: `password123`

---

## 🛑 Menghentikan Layanan

Untuk mematikan seluruh kontainer tanpa menghapus data di dalam database dan MinIO, jalankan:
```bash
docker compose down
```

Jika Anda ingin menghapus seluruh kontainer beserta volume data yang tersimpan, gunakan:
```bash
docker compose down -v
```
