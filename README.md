# LPMS — Learning Project Management System


**Aplikasi Project Based Learning dan Monitoring Keterampilan Berpikir Kreatif Siswa**

LPMS (Learning Project Management System) merupakan aplikasi berbasis web yang dikembangkan menggunakan PHP Native dan MySQL untuk mendukung penerapan model pembelajaran **Project Based Learning (PjBL)** serta monitoring keterampilan berpikir kreatif siswa.

Aplikasi ini dirancang untuk memfasilitasi interaksi antara Admin, Guru, dan Siswa dalam proses pengelolaan proyek pembelajaran, pengumpulan hasil proyek, monitoring perkembangan siswa, serta penilaian keterampilan berpikir kreatif berdasarkan empat indikator kreativitas.

---

## 🚀 Key Features

### 1. Authentication & User Management
- Login dan Logout pengguna.
- Sistem hak akses berdasarkan tiga role:
  - **Admin**
  - **Guru**
  - **Siswa**
- Pengamanan password menggunakan `password_hash()` dan `password_verify()`.
- Pengelolaan sesi pengguna.

### 2. Admin Dashboard
- Mengelola akun Admin, Guru, dan Siswa.
- Menambahkan, mengubah, dan menghapus data pengguna.
- Mengelola kelas.
- Mengelola jurusan.
- Mengatur atribut kelas dan jurusan pada data pengguna.

### 3. Teacher Dashboard
- Membuat dan mengelola proyek pembelajaran.
- Melihat hasil pengumpulan proyek siswa.
- Melakukan monitoring perkembangan siswa.
- Memberikan penilaian menggunakan rubrik kreativitas.
- Mencatat hasil Pretest dan Posttest.
- Memantau perkembangan keterampilan berpikir kreatif siswa.

### 4. Student Dashboard
- Melihat daftar proyek pembelajaran yang aktif.
- Mengunggah hasil pengerjaan proyek dalam format PDF.
- Melihat status pengumpulan proyek.
- Melihat hasil Pretest dan Posttest.
- Mengetahui hasil penilaian proyek.

---

## 🧠 Creative Thinking Indicators

LPMS menggunakan empat indikator keterampilan berpikir kreatif sebagai dasar monitoring dan penilaian siswa.

| Indicator | Description |
|---|---|
| Fluency | Kemampuan menghasilkan banyak ide atau gagasan. |
| Flexibility | Kemampuan menghasilkan ide dari berbagai sudut pandang. |
| Originality | Kemampuan menghasilkan ide yang unik dan orisinal. |
| Elaboration | Kemampuan mengembangkan dan merinci suatu gagasan. |

Keempat indikator tersebut digunakan untuk membantu guru mengevaluasi keterampilan berpikir kreatif siswa selama proses pembelajaran berbasis proyek.

---

## 🛠️ Technology Stack

| Technology | Description |
|---|---|
| PHP Native | Backend application |
| MySQL | Database management |
| HTML5 | Web structure |
| CSS3 | Interface styling |
| JavaScript | Client-side interaction |
| PDO | Database connectivity |
| PDF | Project submission format |

---

## 🔐 Security Features

Aplikasi LPMS menerapkan beberapa mekanisme keamanan dasar untuk melindungi data dan aktivitas pengguna.

- **Password Hashing:** Menggunakan `password_hash()` dan `password_verify()`.
- **PDO Prepared Statements:** Membantu mencegah SQL Injection.
- **CSRF Token:** Melindungi form dari Cross-Site Request Forgery.
- **Session Regeneration:** Menggunakan `session_regenerate_id()` untuk membantu mencegah Session Fixation.
- **Role Validation:** Membatasi akses berdasarkan hak pengguna.
- **Random File Naming:** Menggunakan nama file acak untuk mengurangi risiko konflik dan manipulasi nama berkas.

---

## 📂 File Storage

Dokumen hasil pengerjaan proyek siswa disimpan dalam direktori `uploads`.

File PDF tidak disimpan langsung di dalam database. Database hanya digunakan untuk menyimpan informasi atau referensi berkas sesuai implementasi aplikasi.

---

## ⚙️ Installation Guide

### Requirements
- PHP dengan dukungan PDO MySQL.
- MySQL atau MariaDB.
- Apache Web Server.
- Laragon atau XAMPP.
- Web Browser.

### Installation Steps

**1. Clone Repository**

```bash
git clone https://github.com/MandiriCode/lpms-Project_Based_Learning.git
```

**2. Move Project Folder**

Letakkan folder project ke direktori web server, misalnya:

```text
C:\laragon\www\lpms
```

**3. Create Database**

Buka phpMyAdmin atau MySQL, kemudian buat database sesuai konfigurasi aplikasi.

**4. Database Configuration**

Sesuaikan konfigurasi koneksi database pada file konfigurasi aplikasi:

```php
$host = "localhost";
$dbname = "nama_database";
$username = "root";
$password = "";
```

Sesuaikan nilai konfigurasi dengan lingkungan lokal yang digunakan.

**5. Run Application**

Jalankan Apache dan MySQL melalui Laragon, kemudian buka:

```text
http://localhost/lpms/
```

Pastikan database sudah diimpor sesuai struktur SQL project sebelum menjalankan aplikasi.

---

## 🎯 Project Objectives

LPMS dikembangkan dengan tujuan:

1. Mendukung penerapan model pembelajaran Project Based Learning.
2. Mempermudah guru dalam mengelola dan memonitor proyek pembelajaran.
3. Memfasilitasi pengumpulan hasil proyek siswa secara digital.
4. Membantu proses pengukuran keterampilan berpikir kreatif siswa.
5. Mendokumentasikan hasil Pretest dan Posttest sebagai bagian dari evaluasi pembelajaran.

---

## 👨‍💻 Developer

**LPMS — Learning Project Management System**

Developed as a web-based educational project to support Project Based Learning and creative thinking skills monitoring.

**Repository:** [LPMS Project Based Learning](https://github.com/MandiriCode/lpms-Project_Based_Learning)

---

*Thank you for visiting this repository!*


# LPMS — Proyek Lengkap

Aplikasi PHP Native + MySQL untuk mendukung Project Based Learning dan monitoring keterampilan berpikir kreatif siswa.

## Fitur
- Login/logout dan role Admin, Guru, Siswa.
- Admin: kelola akun pengguna (Admin/Guru/Siswa), kelas dan jurusan disimpan sebagai atribut pengguna.
- Guru: buat proyek, melihat pengumpulan, menambahkan monitoring, menilai rubrik kreativitas, mencatat pretest dan posttest.
- Siswa: melihat proyek aktif, mengunggah hasil proyek PDF, melihat status pengumpulan dan hasil tes.
- Empat indikator kreativitas: Fluency, Flexibility, Originality, Elaboration.
- Berkas PDF disimpan pada folder `uploads`, bukan di database.
- Keamanan dasar: password_hash/password_verify, PDO prepared statements, CSRF token, session_regenerate_id, validasi role, dan nama file acak.


## Instalasi Laragon
1. Ekstrak seluruh isi ZIP ke `C:\laragon\www\lpms`.
2. Jalankan Laragon, aktifkan Apache dan MySQL.
3. Buka phpMyAdmin: `http://localhost/phpmyadmin`.
4. Import file `database/lpms_db.sql`.
5. Periksa `config/database.php`. Default Laragon biasanya user `root`, password kosong.
6. Periksa `config/config.php`. `BASE_URL` default `/lpms`; ubah bila nama folder berbeda.
7. Buka `http://localhost/lpms/` untuk melihat landing page. Buat akun admin melalui `http://localhost/lpms/index.php?page=install`.
8. Buat akun admin pertama (password minimal 10 karakter).
9. Guru dan siswa login melalui tombol di landing page. Administrator dapat login melalui `http://localhost/lpms/index.php?page=login&role=admin`.

**Penting:** Landing page publik tampil di `http://localhost/lpms/`. Tombol Login Guru dan Login Siswa mengarahkan ke formulir login sesuai role. Aplikasi memakai query string `index.php?page=...`, jadi tidak memerlukan `.htaccess` atau mod_rewrite.

## Instalasi XAMPP
Ekstrak ke `C:\xampp\htdocs\lpms`, hidupkan Apache/MySQL, lalu ikuti langkah import database. URL tetap `http://localhost/lpms/index.php?page=install`.

## Pengaturan upload
Batas ukuran PDF 10 MB. Jika unggahan gagal, periksa izin tulis folder `uploads` dan `upload_max_filesize` serta `post_max_size` pada `php.ini` (setidaknya 12M).

## Struktur folder
- `index.php`: front controller dan routing sederhana.
- `config/`: konfigurasi aplikasi dan database.
- `database/`: SQL database enam tabel.
- `app/pages/`: halaman fitur.
- `app/layout/`: kerangka tampilan.
- `assets/`: CSS.
- `uploads/`: berkas PDF hasil proyek.
- `download.php`: pengunduhan PDF dengan pemeriksaan hak akses.

## Catatan rancangan
Database terdiri tepat enam tabel: `user`, `proyek`, `unggah_proyek`, `monitoring`, `penilaian`, `hasil_tes`. Tidak ada tabel kelas terpisah; kelas/jurusan dicatat di tabel user untuk menjaga kesepakatan enam tabel. Skor indikator memakai skala 0–100 dan nilai akhir proyek dihitung sebagai rata-rata empat indikator. Penetapan bobot khusus, instrumen butir soal uraian, dan alur persetujuan/revisi lebih rinci perlu disesuaikan dengan instrumen penelitian final.

## Catatan sebelum digunakan sungguhan
Ini adalah paket aplikasi awal yang terintegrasi untuk diuji secara lokal. Uji alur dengan akun dummy terlebih dahulu. Untuk penerapan produksi, tambahkan HTTPS, backup database, pembatasan percobaan login, audit log, dan kebijakan retensi file.


## Laporan dan rekapitulasi
Menu Laporan & Rekap tersedia untuk Admin, Guru, dan Siswa. Admin dapat melihat semua data, Guru hanya data proyek yang dibuatnya, dan Siswa hanya data pengumpulan miliknya. Filter laporan mendukung tanggal mulai, tanggal akhir, serta kelas (Admin/Guru). Hasil dapat dicetak dari browser atau diekspor ke CSV. Fitur ini menggunakan tabel yang sudah ada dan tidak menambah tabel database.

## Identitas tampilan role
Dashboard Admin menggunakan aksen ungu, Guru menggunakan aksen teal, dan Siswa menggunakan aksen oranye. Struktur komponen tetap konsisten agar aplikasi terasa satu kesatuan, tetapi tiap peran memiliki identitas visual yang mudah dibedakan.


### Ekspor laporan
Pada menu **Laporan & Rekap**, pengguna dapat mengunduh laporan dalam dua format: **CSV** (untuk pertukaran data) dan **Excel (.xls)** (SpreadsheetML yang dapat langsung dibuka di Microsoft Excel). Kedua format mengikuti filter periode dan kelas yang sedang dipilih.


Untuk User Dengan Role Guru dan Murid Bisa di atur oleh role admin 
akses sementera dengan role:
1. admin :  username = admin
            pass     = admin12345

2. guru  :  username = guru
            pass     = guru123456

3. siswa :  username = murid
            pass     = murid123456#   L P M S 
 
 
