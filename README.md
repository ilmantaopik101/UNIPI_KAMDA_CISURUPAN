INI CUMAN LATIHAN BACKEND MEMBUAT DATABASE WEB KAMPUS 

CARA CEK DI POSTMAN


1. 🔑 Register User
Request:
POST http://localhost/UNIPI_KAMDA_CISURUPAN/API/register.php
Body (raw, JSON):
{
  "username": "ilman123",
  "password": "123456",
  "role": "mahasiswa",
  "nama": "Ilman Taopik",
  "nim": "231001",
  "prodi": "Informatika",
  "alamat": "Garut"
}
Response (contoh sukses):
{
  "status": "success",
  "message": "Registrasi berhasil",
  "user_id": 1
}


2. 🔑 Login User
Request:
POST http://localhost/UNIPI_KAMDA_CISURUPAN/API/login.php
Body (raw, JSON):
{
  "username": "ilman123",
  "password": "123456"
}
Response (contoh sukses):
{
  "status": "success",
  "message": "Login berhasil",
  "user_id": 1,
  "role": "mahasiswa"
}


3. 📊 Dashboard (akses data sesuai role)
Request:
GET http://localhost/UNIPI_KAMDA_CISURUPAN/API/dashboard.php?user_id=1&role=mahasiswa
Response (contoh):
{
  "status": "success",
  "dashboard": {
    "welcome": "Selamat datang Ilman Taopik",
    "role": "mahasiswa"
  }
}


4. 👨‍🎓 Read All Mahasiswa
Request:
GET http://localhost/UNIPI_KAMDA_CISURUPAN/API/mahasiswa.php
Response (contoh):
[
  {
    "id": 1,
    "nama": "Ilman Taopik",
    "nim": "231001",
    "prodi": "Informatika",
    "alamat": "Garut"
  }
]


5. 🔍 Read One Mahasiswa
Request:
GET http://localhost/UNIPI_KAMDA_CISURUPAN/API/mahasiswa.php?id=1
Response (contoh):
{
  "id": 1,
  "nama": "Ilman Taopik",
  "nim": "231001",
  "prodi": "Informatika",
  "alamat": "Garut"
}


6. ➕ Create Mahasiswa (manual tanpa register)
Request:
POST http://localhost/UNIPI_KAMDA_CISURUPAN/API/mahasiswa.php
Body (raw, JSON):
{
  "nama": "Rafah Madani",
  "nim": "231002",
  "prodi": "Sistem Informasi",
  "alamat": "Bekasi"
}
Response (contoh sukses):
{
  "status": "success",
  "message": "Mahasiswa berhasil ditambahkan"
}


7. ✏️ Update Mahasiswa
Request:
PUT http://localhost/UNIPI_KAMDA_CISURUPAN/API/mahasiswa.php
Body (raw, JSON):
{
  "id": 1,
  "nama": "Ilman Taopik",
  "nim": "231001",
  "prodi": "Teknik Informatika",
  "alamat": "Bandung"
}
Response (contoh sukses):
{
  "status": "success",
  "message": "Data mahasiswa berhasil diperbarui"
}


8. ❌ Delete Mahasiswa
Request:
DELETE http://localhost/UNIPI_KAMDA_CISURUPAN/API/mahasiswa.php
Body (raw, JSON):
{
  "id": 1
}
Response (contoh sukses):
{
  "status": "success",
  "message": "Data mahasiswa berhasil dihapus"
}
