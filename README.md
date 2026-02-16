# Microsite Mudik PGN 2026

Microsite untuk pendaftaran dan manajemen program Mudik PGN 2026.

## Tech Stack
- **Framework:** Laravel 12.x
- **PHP Version:** 8.2
- **Database:** MySQL 8.0
- **Containerization:** Docker & Docker Compose

---

## Docker Setup & Development

Aplikasi ini menggunakan Docker untuk standarisasi environment development dan deployment.

### 1. Persiapan
- Pastikan Docker dan Docker Compose sudah terinstal di komputer/server kamu.

### 2. Instalasi Cepat
1. Copy environment file:
   ```bash
   cp .env.example .env
   ```
2. Jalankan container:
   ```bash
   docker-compose up -d --build
   ```
3. Setup aplikasi di dalam container:
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan storage:link
   docker-compose exec app php artisan migrate
   ```
4. Akses aplikasi di: [http://localhost:8000](http://localhost:8000)

### 3. Deployment & Update (Ubuntu Server)

#### A. Persiapan Awal (Hanya sekali di server baru)
1. Clone repository ke server.
2. Copy environment file:
   ```bash
   cp .env.example .env
   ```
3. Edit `.env` dan sesuaikan `DB_HOST=db`, `DB_PASSWORD`, dll.
4. Jalankan `docker-compose up -d --build`.
5. **PENTING: Jalankan perintah ini HANYA SEKALI:**
   ```bash
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan storage:link
   ```
   > [!WARNING]
   > Jangan jalankan `key:generate` lagi setelah aplikasi berjalan dan ada data user, karena akan merusak enkripsi data dan memaksa semua user logout.

#### B. Update Rutin (Setiap ada perubahan di Git)
Cukup jalankan script otomatis:
1. Pastikan file `deploy.sh` punya izin eksekusi (hanya sekali):
   ```bash
   chmod +x deploy.sh
   ```
2. Jalankan deployment:
   ```bash
   ./deploy.sh
   ```
   *Script ini otomatis melakukan git pull, build ulang, install vendor, dan running migration.*
  
### 4. Hybrid Development (Docker vs Local)
Kamu bisa memilih menggunakan Docker atau PHP native (Laragon/XAMPP) dengan mengubah `.env`:

- **Pakai Docker**: Set `DB_HOST=db` dan `DB_PASSWORD=rootpassword`.
- **Pakai Local**: Set `DB_HOST=127.0.0.1` dan `DB_PASSWORD=` (sesuai setting localmu).

---

## Koneksi Database Luar Container
Jika ingin connect ke database Docker menggunakan aplikasi database (TablePlus/DBeaver/HeidiSQL), gunakan:
- **Host:** `127.0.0.1`
- **Port:** `33060`
- **User:** `root`
- **Password:** `rootpassword` (sesuai setting `.env`)
