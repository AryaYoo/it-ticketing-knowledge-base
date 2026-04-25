# MasTolongMas - IT Ticketing System

Sistem manajemen tiket IT yang dirancang untuk efisiensi pelaporan, pemantauan, dan penyelesaian kendala teknis secara real-time dengan antarmuka premium dan sistem notifikasi cerdas.

---

## 🛠 Panduan Instalasi & Setup Awal

Untuk memastikan sistem berjalan dengan sempurna (terutama fitur gambar dan notifikasi), ikuti langkah-langkah berikut:

### 1. Persiapan Dasar
1. Pastikan environment PHP, Composer, dan MySQL sudah siap (XAMPP direkomendasikan).
2. Jalankan `composer install`.
3. Salin `.env.example` menjadi `.env` dan sesuaikan konfigurasi database.
4. Jalankan `php artisan migrate --seed`.

### 2. Konfigurasi Media (PENTING)
Agar gambar lampiran dan bukti resolusi muncul di browser, folder storage harus terhubung dengan benar sebagai *Symbolic Link*.
Jika Anda memindahkan folder proyek atau baru pertama kali install, jalankan perintah ini di terminal:
```cmd
rmdir /s /q public\storage
php artisan storage:link
```
*Catatan: Pastikan folder `public/storage` muncul kembali sebagai shortcut/link, bukan folder biasa.*

---

## 🔔 Panduan Sistem Notifikasi

Aplikasi ini dilengkapi dengan **Dual-Notification System**:
1. **In-App Toast**: Notifikasi persisten di dalam web yang tidak akan hilang sebelum ditutup/tiket di-resolve.
2. **Desktop Push Notification**: Notifikasi asli Windows yang muncul bahkan saat browser sedang di-minimize.

### Aktivasi Notifikasi Desktop (Akses via IP/HTTP)
Karena browser mewajibkan HTTPS untuk fitur notifikasi, ikuti langkah ini jika Anda mengakses via IP (misal: `192.168.100.177`):
1. Buka Chrome dan ketik: `chrome://flags/#unsafely-treat-insecure-origin-as-secure`
2. Masukkan alamat IP aplikasi Anda (contoh: `http://192.168.100.177`).
3. Ubah status menjadi **Enabled** dan klik **Relaunch**.
4. Klik ikon **Gembok** di sebelah kiri Address Bar, lalu ubah **Notifications** menjadi **Allow**.

---

## 📋 Prosedur Operasional Standar (SOP)

### A. Alur Pelaporan Tiket (Client)
1. Login ke dashboard Client.
2. Klik tombol **"Create Ticket"**.
3. Isi semua field yang diminta (Judul, Kategori, Prioritas, dan Deskripsi).
4. Lampirkan gambar kendala (opsional namun sangat disarankan).
5. Klik **"Submit Ticket"**.

### B. Alur Penanganan Tiket (Staff/Admin)
1. **Penerimaan**: Staff akan menerima notifikasi real-time (suara & popup).
2. **In-Progress**: Klik tombol **"In Progress"** untuk memberi tahu client bahwa tiket sedang dikerjakan.
3. **Eskalasi**: Jika kendala membutuhkan bantuan tingkat lanjut, gunakan tombol **"Escalate"**.
4. **Penyelesaian (Resolve)**:
   - Klik tombol **"Resolve Ticket"**.
   - **Wajib** mengisi: *Problem Summary* (Inti masalah) dan *Steps Taken* (Langkah perbaikan).
   - **Wajib** mengunggah: *Proof of Resolution* (Foto bukti perbaikan selesai).
   - Klik **"Complete & Resolve"**.

---

## 🔍 Troubleshooting (Solusi Kendala)

| Masalah | Solusi |
| :--- | :--- |
| **Gambar Broken (Tanda Silang)** | Jalankan ulang perintah `storage:link` seperti pada panduan instalasi di atas. |
| **Notifikasi Tidak Muncul** | Cek apakah Windows sedang dalam mode **Focus Assist / Do Not Disturb**. |
| **Form Resolve Error** | Pastikan file gambar yang diunggah berukuran **maksimal 2MB**. |
| **Tombol Tidak Merespon** | Pastikan Anda memiliki koneksi internet/lokal yang stabil dan refresh halaman (F5). |

---

Developed with ❤️ by MasTolongMas Team
