👕 ThriftVTO - Virtual Try-On Project
Aplikasi katalog baju thrift dengan fitur Manual Virtual Try-On. Project ini dikembangkan menggunakan Laravel 12 dan Fabric.js untuk memungkinkan pengguna mencoba pakaian secara digital melalui metode Image Overlay yang ringan, instan, dan 100% gratis.

📋 1. Prasyarat Sistem
Sebelum memulai, pastikan perangkat Anda sudah terinstall:

PHP >= 8.2.12

Composer (Dependency Manager untuk PHP)

Node.js & NPM (Untuk pengelolaan aset frontend)

MySQL (XAMPP / Laragon / Desktop MySQL)

🚀 2. Langkah Instalasi & Setup
A. Persiapan Project
Ekstrak project dari file ZIP, lalu buka terminal (CMD/VS Code Terminal) di dalam folder project dan jalankan:

Bash

composer install
npm install
npm run dev
Catatan: Biarkan terminal yang menjalankan npm run dev tetap terbuka selama Anda mengakses website agar CSS dan JS (Vite) termuat sempurna.

B. Konfigurasi Database (.env)
Salin file .env.example menjadi .env:

Bash

cp .env.example .env
Buka file .env dan sesuaikan pengaturan database (buat database kosong bernama thrift_vto di phpMyAdmin terlebih dahulu):

Code snippet

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thrift_vto
DB_USERNAME=root
DB_PASSWORD=
C. Migrasi & Seed Data
Jalankan perintah ini untuk membuat struktur tabel dan mengisi katalog dengan produk contoh:

Bash

php artisan key:generate
php artisan migrate --seed
D. Menghubungkan Folder Gambar (WAJIB)
Agar gambar produk dan foto yang diunggah muncul di browser, jalankan perintah ini:

Bash

php artisan storage:link
💻 3. Cara Menjalankan & Menggunakan
Menjalankan Server
Nyalakan server lokal dengan perintah:

Bash

php artisan serve
Akses aplikasi di browser melalui alamat: http://127.0.0.1:8000

Cara Menggunakan Fitur Try-On (Manual)
Pilih salah satu produk di halaman Katalog.

Scroll ke bawah ke bagian AI Dressing Room (Mode Manual Overlay).

Klik Pilih Foto untuk mengunggah foto diri Anda.

Baju produk akan muncul otomatis di atas foto.

Gunakan mouse untuk menggeser (drag), memutar (rotate), dan mengubah ukuran (resize) baju agar pas dengan tubuh pada foto.

🔐 4. Akses Dashboard Admin
Anda dapat mengelola produk, stok, dan pesanan melalui dashboard admin:

URL Login: http://127.0.0.1:8000/login

Email Admin: admin@thriftvto.com

Password: password

Penting: Jika terjadi error pada menu Orders, pastikan memanggil route admin.orders.index sesuai konfigurasi terbaru.

📤 5. Cara Mengunggah ke GitHub
Jika Anda ingin menyimpan atau membagikan project ini melalui GitHub:

Inisialisasi Git:

Bash

git init
git add .
git commit -m "Initial commit ThriftVTO Manual Edition"
Push ke Repositori:

Bash

git remote add origin https://github.com/username-anda/nama-repo.git
git branch -M main
git push -u origin main
File .env, folder vendor, dan node_modules secara otomatis diabaikan oleh Git demi keamanan.

⚠️ 6. Troubleshooting
Gambar Tidak Muncul: Jalankan kembali perintah php artisan storage:link.

404 Not Found: Pastikan Anda mengakses via php artisan serve, bukan membuka file .blade.php secara langsung di browser.

Database Error: Pastikan MySQL di XAMPP sudah aktif dan nama database di .env sudah benar.
