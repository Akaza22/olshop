# 👕 ThriftVTO - Manual Overlay Edition

Aplikasi katalog baju thrift dengan fitur **Virtual Try-On (VTO) Manual**

---

## 📋 Prasyarat Sistem

Sebelum melakukan instalasi, pastikan perangkat kamu sudah terpasang:
* **PHP:** v8.2 atau lebih baru
* **Composer:** Dependency Manager untuk PHP
* **Node.js & NPM:** Untuk kompilasi aset (Tailwind CSS)
* **Database:** MySQL (XAMPP / Laragon / Desktop MySQL)

---

## 🚀 Langkah Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan lokal kamu:

### 1. Persiapan Project
Ekstrak project dari file ZIP, lalu buka terminal (CMD/PowerShell) di dalam folder project dan jalankan:

1. composer install
2. npm install
3. npm run dev

### 2. Environment Configuration
Masukkan file .env ke dalam root project, lalu pastikan di dalam .env ada konfigurasi seperti ini :

 * DB_CONNECTION=mysql
 * DB_HOST=127.0.0.1
 * DB_PORT=3306
 * DB_DATABASE=thrift_vto
 * DB_USERNAME=root
 * DB_PASSWORD=

Pastikan kamu sudah membuat database kosong bernama thrift_vto di phpMyAdmin/MySQL.

### 3. Setup Database & Key
Jalankan perintah berikut secara berurutan:

 * php artisan key:generate
 * php artisan migrate --seed
   
Catatan: Perintah --seed akan otomatis mengisi katalog dengan produk-produk contoh.

### 4. Menghubungkan Folder Gambar (WAJIB)
Fitur Try-On memerlukan akses publik ke folder penyimpanan. Jalankan perintah ini agar gambar muncul di browser:

 * php artisan storage:link

### 5. 💻 Cara Menjalankan Aplikasi

 * php artisan serve
Akses Browser: Buka alamat http://127.0.0.1:8000.

### 6. Gunakan Fitur Try-On:
 1. Pilih salah satu produk dari katalog.
 2. Scroll ke bawah ke bagian AI Dressing Room (Versi Manual).
 3. Unggah foto diri kamu.
 4. Baju akan muncul di atas foto. Kamu bisa menggeser, memutar, dan mengubah ukuran baju agar pas dengan badan kamu di foto.
    
### 7. 🔐 Akses Admin
Kamu bisa mengelola produk melalui dashboard admin:

URL: http://127.0.0.1:8000/login

Email: admin@thriftvto.com (atau cek file DatabaseSeeder.php)
Password: password

## 🛠 Teknologi yang Digunakan
 #### Laravel 12: Framework PHP Utama.

 #### Tailwind CSS: Desain UI yang responsif.

 #### Fabric.js: Library JavaScript untuk fitur manipulasi gambar (Drag, Drop, Resize) pada canvas.

 #### Lucide React: Untuk ikon-ikon di website.

## ⚠️ Troubleshooting
Gambar Tidak Muncul: Pastikan kamu sudah menjalankan php artisan storage:link.

Error Mix/Vite: Pastikan terminal yang menjalankan npm run dev tetap terbuka saat kamu mengakses website.

Database Error: Pastikan MySQL di XAMPP/Laragon sudah dalam status Start.
