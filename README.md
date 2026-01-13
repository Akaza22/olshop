👕 ThriftVTO - Manual Overlay Edition
Aplikasi katalog baju thrift dengan fitur Virtual Try-On Manual. Versi ini menggunakan teknik Image Overlay (tanpa API berbayar), sehingga 100% gratis, ringan, dan bisa dijalankan tanpa koneksi internet (offline/localhost).

📋 Prasyarat Sistem
PHP >= 8.2

Composer (Dependency Manager PHP)

Node.js & NPM

MySQL (XAMPP / Laragon / Desktop MySQL)

🚀 Langkah Instalasi
1. Persiapan Project
Ekstrak project dari file ZIP, lalu buka terminal (CMD/PowerShell) di dalam folder project:

Bash

composer install
npm install
npm run dev
2. Konfigurasi Environment (.env)
Salin file .env.example menjadi .env:

Bash

cp .env.example .env
Buka file .env menggunakan teks editor (VS Code/Notepad), lalu sesuaikan bagian database:

Code snippet

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thrift_vto
DB_USERNAME=root
DB_PASSWORD=
Pastikan kamu sudah membuat database kosong bernama thrift_vto di phpMyAdmin/MySQL.

3. Setup Database & Key
Jalankan perintah berikut secara berurutan:

Bash

php artisan key:generate
php artisan migrate --seed
Catatan: Perintah --seed akan otomatis mengisi katalog dengan produk-produk contoh.

4. Menghubungkan Folder Gambar (WAJIB)
Fitur Try-On memerlukan akses publik ke folder penyimpanan. Jalankan perintah ini agar gambar muncul di browser:

Bash

php artisan storage:link
💻 Cara Menjalankan Aplikasi
Nyalakan Server:

Bash

php artisan serve
Akses Browser: Buka alamat http://127.0.0.1:8000.

Gunakan Fitur Try-On:

Pilih salah satu produk dari katalog.

Scroll ke bawah ke bagian AI Dressing Room (Versi Manual).

Unggah foto diri kamu.

Baju akan muncul di atas foto. Kamu bisa menggeser, memutar, dan mengubah ukuran baju agar pas dengan badan kamu di foto.

🔐 Akses Admin
Kamu bisa mengelola produk melalui dashboard admin:

URL: http://127.0.0.1:8000/login

Email: admin@thriftvto.com (atau cek file DatabaseSeeder.php)

Password: password

🛠 Teknologi yang Digunakan
Laravel 12: Framework PHP Utama.

Tailwind CSS: Desain UI yang responsif.

Fabric.js: Library JavaScript untuk fitur manipulasi gambar (Drag, Drop, Resize) pada canvas.

Lucide React: Untuk ikon-ikon di website.

⚠️ Troubleshooting
Gambar Tidak Muncul: Pastikan kamu sudah menjalankan php artisan storage:link.

Error Mix/Vite: Pastikan terminal yang menjalankan npm run dev tetap terbuka saat kamu mengakses website.

Database Error: Pastikan MySQL di XAMPP/Laragon sudah dalam status Start.