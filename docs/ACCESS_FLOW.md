# Rancangan Jalur Akses Sistem Notaris/PPAT

## Database
- Driver utama: MySQL.
- Database lokal: `notaris_ppat`.
- Seeder/data dummy: tidak digunakan.
- Tabel inti kosong setelah migrasi: `users`, `office_services`, `file_applications`, `file_progress_steps`, `document_requests`, `appointments`, `staff_attendances`, `notary_device_sessions`.

## Status Akun
- `pending`: akun baru menunggu persetujuan.
- `active`: akun bisa login.
- `blocked`: akun diblokir.
- `resigned`: staf sudah resign dan akses ditutup.

## Role dan Batas Akses
- `user`: hanya boleh masuk ke `/dashboard/user`.
- `staff`: boleh masuk ke `/dashboard/admin` setelah aktif.
- `admin`: boleh masuk ke `/dashboard/admin` setelah aktif.
- `notary`: hanya boleh masuk ke `/dashboard/notaris`, akun utama tunggal.

## Jalur Login
1. Pengguna membuka halaman public `/`.
2. Login dikirim ke route `POST /login`.
3. Sistem cek email, password, status akun, dan role.
4. Redirect berdasarkan role:
   - `user` → `/dashboard/user`
   - `staff` atau `admin` → `/dashboard/admin`
   - `notary` → `/dashboard/notaris`
5. Jika belum login, akses dashboard otomatis redirect ke `/login`.

## Register
- Register user standar bisa aktif langsung setelah validasi dasar.
- Register staf masuk `pending` dan harus di-ACC notaris/super admin.
- Register notaris hanya boleh satu akun. Jika sudah ada akun notaris, pendaftaran notaris ditolak.

## OTP
- OTP belum digunakan.
- UI OTP dibuat nonaktif sementara.
- Integrasi OTP disiapkan untuk pihak ketiga resmi pada tahap berikutnya.

## Device Notaris
- Akun notaris dirancang sebagai akun tunggal.
- Jika login dari device lain, sistem menampilkan konfirmasi: “mau login di sini?”
- Setelah disetujui, sesi device lama harus dianggap tidak berlaku pada implementasi backend berikutnya.

## Catatan Implementasi Saat Ini
- Route dashboard sudah dilindungi middleware `auth` dan `role`.
- Database sudah MySQL dan migrasi sudah berhasil.
- Data dashboard visual lama masih berupa layout UI sampai modul query database dipasang.
