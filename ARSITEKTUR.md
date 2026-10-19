# E-Learning Platform

## Ringkasan
Proyek E-Learning berbasis web dan API untuk platform pembelajaran sekolah (tingkat SMP) dengan tiga peran pengguna: **admin**, **guru**, dan **siswa**. Admin mengelola data induk melalui panel web berbasis template JustBoil; guru membuat materi, tugas, dan quiz beserta rekap penilaiannya; siswa mengonsumsi konten melalui **aplikasi mobile** yang memakai REST API (Laravel Sanctum). Sistem juga mendukung notifikasi push sederhana, analisis quiz siswa, dan audit log.

## MVP (Minimum Viable Product)
Alur pengguna:

1. Pengguna masuk melalui halaman login (`login` → `login.action`) — admin & guru via web, siswa via aplikasi mobile (API Sanctum).
2. Admin mengelola data induk: siswa, guru, admin, kelas, wali kelas, tahun ajaran, dan mata pelajaran.
3. Guru mengunggah **materi** dan **tugas** (dengan tenggat waktu); siswa dinotifikasi setiap ada materi/tugas baru.
4. Guru membuat **quiz** — soal dapat diimpor dari file Excel (dengan alur preview sebelum tersimpan), quiz memiliki level adaptif (`QuizLevelSetting`) dan batas waktu.
5. Siswa mengerjakan quiz melalui API engine: mulai attempt → jawab soal satu per satu → finish / auto-finish, lalu melihat skor dan ranking (top five).
6. Siswa mengumpulkan tugas melalui API (upload submit tugas); guru menilai melalui halaman Rekap Tugas dan mengekspor ke Excel.
7. Guru melihat Rekap Quiz, Rekap Tugas (dengan export Excel), serta analisis siswa (`analysis-siswa`).
8. Admin dapat memindahkan data guru↔mata pelajaran (`pindah-data`) dan memantau **audit log**.

## Fitur & Teknologi

| Teknologi/library | Kegunaan dalam proyek |
|---|---|
| Laravel 11 | Kerangka utama aplikasi: routing, controller, Eloquent ORM, middleware, dan session. |
| PHP 8.2 | Bahasa backend untuk seluruh logika bisnis dan API. |
| MySQL/Database migrasi | 39 file migrasi untuk tabel users, guru, siswa, kelas, materi, tugas, quiz, attempt, audit_logs, dll. |
| Laravel Sanctum | Autentikasi token API untuk aplikasi mobile siswa (`personal_access_tokens`). |
| maatwebsite/excel | Impor soal quiz dari Excel (dengan preview ke Session) dan ekspor rekap nilai (FromView: QuizExport, TugasExport, TugasDetailExport, RekapExport). |
| realrashid/sweet-alert | Notifikasi flash (alert) di antarmuka admin/guru. |
| Laravel Notifications | Notifikasi tugas/quiz/materi baru ke siswa melalui tabel `notifications`. |
| Role Middleware | `RoleMiddleware` dengan sintaks `role:admin` / `role:guru` untuk proteksi rute per peran. |
| Repository Pattern | 17 kelas repository yang memisahkan logika query dari controller (termasuk repository khusus API). |
| Blade + JustBoil Template | Layout panel admin (`resources/views/layouts`, `partials/sidebar`, `template/css/js`). |
| Vite + Tailwind CSS + Flowbite | Build aset frontend, styling utilities, dan komponen UI (Heroicons). |
| Chart.js | Grafik/statistik pada dashboard (contoh di `public/template/js/chart.sample.js`). |
| Axios | HTTP client untuk kebutuhan aset frontend. |

## Arsitektur
- **Pemisahan per peran:** controller web di `App\Http\Controllers` (admin) dan namespace `App\Http\Controllers\RoleGuru` (halaman guru); controller API di `App\Http\Controllers\Api` untuk aplikasi mobile.
- **Autentikasi ganda:** session-based untuk web, token Sanctum untuk API (`CheckApiToken` untuk validasi Bearer token).
- **Routing:**
  - `routes/web.php` — login, change-password, grup `role:admin` (CRUD master data, dashboard, pindah-data, audit-log), grup `role:guru` (materi, tugas, quiz, rekap).
  - `routes/api.php` — login/logout, materi, tugas & submit tugas, engine quiz (start/next-question/answer/finish/auto-finish), ranking & analisis, notifikasi siswa, master data.
- **Repository pattern:** logika data dipusatkan di `app/Repositories` (mis. `QuizRepository` dipakai bersama oleh controller web dan API; `ApiTugasRepository`, `ApiSubmitTugasRepository` khusus mobile).
- **Respons API seragam:** trait `App\Http\Controllers\Traits\ApiResponse`; API Resources (`SubmitTugasResource`, `TugasResource`).
- **Audit log:** model `AuditLog` + tabel `audit_logs` + halaman admin.

## Struktur kode
```
app/
├── Exports/                        # Ekspor Excel (QuizExport, TugasExport, TugasDetailExport, RekapExport)
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Login/logout web
│   │   ├── DashboardController.php # Dashboard admin
│   │   ├── SiswaController.php     # CRUD siswa
│   │   ├── GuruController.php      # CRUD guru
│   │   ├── AdminController.php     # CRUD admin + pindah-data + audit log
│   │   ├── KelasContoller.php      # CRUD kelas
│   │   ├── WaliKelasController.php # CRUD wali kelas
│   │   ├── TahunAjaranController.php
│   │   ├── MataPelajaranController.php
│   │   ├── UserController.php      # Change password (web & API)
│   │   ├── RoleGuru/               # Halaman guru (web)
│   │   │   ├── MateriController.php
│   │   │   ├── TugasController.php
│   │   │   ├── QuizController.php  # CRUD quiz + impor Excel + preview
│   │   │   ├── RekapQuizController.php
│   │   │   └── RekapTugasController.php  # Penilaian + export
│   │   ├── Api/                    # API mobile (Sanctum)
│   │   │   ├── AuthController.php
│   │   │   ├── MateriController.php
│   │   │   ├── TugasController.php
│   │   │   ├── SubmitTugasController.php
│   │   │   ├── QuizController.php  # Engine attempt quiz
│   │   │   └── QuizAnalysisController.php
│   │   └── Traits/ApiResponse.php  # Respons JSON seragam
│   ├── Middleware/RoleMiddleware.php   # Proteksi rute per peran
│   └── Middleware/CheckApiToken.php    # Validasi token API
├── Models/                         # User, Guru, Siswa, Kelas, Materi, Tugas, Quizzes, QuizAttempts, AuditLog, dst.
├── Notifications/                  # TugasBaruNotification, QuizBaruNotification, MateriBaruNotification
├── Providers/
├── Repositories/                   # 17 repository (pola Repository)
└── View/Components/Icon.php        # Komponen ikon Blade

database/
├── migrations/                     # 39 migrasi (users, guru, siswa, kelas, quiz, audit_logs, ...)
└── seeders/

resources/
├── js/                             # Vite entry (app.js, bootstrap.js, axios)
├── css/                            # Tailwind
└── views/
    ├── layouts/app.blade.php       # Layout utama JustBoil
    ├── partials/                   # sidebar, navbar, footer, scripts
    ├── pages/auth/                 # Login, change password
    ├── pages/role_admin/           # Dashboard + CRUD master data
    └── pages/role_guru/            # Materi, tugas, quiz, rekap tugas

public/
├── template/                       # Aset JustBoil (css/main.css, js/main.js, chart.sample.js, gambar)
└── vendor/sweetalert/

routes/
├── web.php                         # Rute web (admin & guru, middleware role)
└── api.php                         # Rute API mobile (Sanctum)
```

## Penutup
Proyek E-Learning ini merupakan platform pembelajaran sekolah yang dibangun dengan Laravel 11, menggabungkan panel web (Blade + Tailwind/JustBoil) untuk admin dan guru dengan REST API berbasis Sanctum untuk aplikasi mobile siswa. Arsitekturnya menerapkan repository pattern, middleware role-based, dan pemisahan namespace per peran sehingga kode terorganisir dan mudah dikembangkan. Fitur unggulannya mencakup manajemen data induk, materi/tugas dengan notifikasi, quiz engine dengan impor Excel dan level adaptif, rekap penilaian dengan ekspor Excel, analitik siswa, serta audit log.
