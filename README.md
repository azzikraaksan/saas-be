# 📋 Checklist Onboarding - Laravel Backend - Azzikra Ramadhanti Aksan
API sederhana untuk mengelola checklist onboarding pengguna. Dibangun menggunakan Laravel 12.20, dan database MySQL.

# 🚀 Fitur Utama
- Register & Login User (Auth)
- CRUD Checklist
- Tandai Checklist Selesai

# ⚙️ Setup & Instalasi
1. Clone Project
```bash
git clone https://github.com/azzikraaksan/saas-be.git
cd saas-be
```
2. Install Dependency
bash
```bash
composer install
```
3. Copy File .env & Generate Key
```bash
cp .env.example .env
php artisan key:generate
```
4. Set Konfigurasi .env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saas-javan
DB_USERNAME=root
DB_PASSWORD=
```
5. Jalankan Migrasi
```bash
php artisan migrate
```
6. Jalankan Server
```bash
php artisan serve
```

# 🧪 Endpoints Utama
Method	Endpoint	                Deskripsi
POST	/api/register	            Register pengguna
POST	/api/login	                Login pengguna/admin
GET	    /api/checklists	            Ambil semua checklist milik user
POST	/api/checklists/create	    Tambah checklist
PUT	    /api/checklists/update/{id}	Edit checklist
POST	/api/checklists/done/{id}	Tandai selesai
DELETE	/api/checklists/delete/{id}	Hapus checklist
