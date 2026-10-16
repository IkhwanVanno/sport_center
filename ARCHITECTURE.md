# System Architecture & Technical Specifications

## 1. Stack Teknologi
* **Backend Framework:** Laravel 11.x (PHP 8.3+)
* **Database:** MySQL 8.0+ / MariaDB 10.6+ (InnoDB Engine)
* **Frontend:** Blade Template Engine + Tailwind CSS (v3.x) + Alpine.js (v3.x)
* **Authentication & RBAC:** Laravel Breeze + Spatie Laravel-Permission
* **Queue & Scheduler:** Laravel Queue (Database Driver) + Task Scheduling

## 2. Pola Arsitektur (Service-Oriented Layered MVC)
Aplikasi menerapkan pola MVC yang diperluas dengan Service Layer untuk menjaga Controller tetap tipis (*Thin Controller, Fat Service*).
[ HTTP Request ] -> [ Routes ] -> [ Form Request Validation ] -> [ Controller ]
│
v
[ Service Layer ]
│
v
[ HTTP Response / Blade View ] <--------------------------------- [ Eloquent Model ]

### Tanggung Jawab Komponen
* **Form Request:** Validasi input, tipe data, dan otorisasi parameter request.
* **Controller:** Menerima HTTP Request, memanggil Service Layer, dan mengembalikan Response/Blade View.
* **Service Layer (`app/Services`):** Menampung seluruh logika bisnis, kalkulasi harga, transaksi database (`DB::transaction`), dan kunci ketersediaan (`lockForUpdate`).
* **Eloquent Model:** Definisi struktur tabel, mutator/accessor, scope query, dan relasi antar tabel.

## 3. Struktur Folder Proyek
```
├── 📁 app
│   ├── 📁 Http
│   │   ├── 📁 Controllers
│   │   │   ├── 📁 Admin
│   │   │   │   ├── 🐘 AdminDashboardController.php
│   │   │   │   ├── 🐘 BranchController.php
│   │   │   │   ├── 🐘 CourtController.php
│   │   │   │   ├── 🐘 OrderManagementController.php
│   │   │   │   ├── 🐘 PriceRuleController.php
│   │   │   │   └── 🐘 ReportController.php
│   │   │   ├── 📁 Auth
│   │   │   │   ├── 🐘 AuthenticatedSessionController.php
│   │   │   │   └── 🐘 RegisteredUserController.php
│   │   │   ├── 📁 Customer
│   │   │   │   ├── 🐘 BookingController.php
│   │   │   │   ├── 🐘 CatalogController.php
│   │   │   │   ├── 🐘 CustomerDashboardController.php
│   │   │   │   └── 🐘 PaymentController.php
│   │   │   ├── 📁 Operator
│   │   │   │   └── 🐘 CheckInController.php
│   │   │   ├── 🐘 Controller.php
│   │   │   └── 🐘 DashboardController.php
│   │   ├── 📁 Middleware
│   │   │   └── 🐘 EnsureUserHasRole.php
│   │   └── 📁 Requests
│   │       └── 📁 Auth
│   │           └── 🐘 LoginRequest.php
│   ├── 📁 Models
│   │   ├── 🐘 ActivityLog.php
│   │   ├── 🐘 Booking.php
│   │   ├── 🐘 BookingItem.php
│   │   ├── 🐘 Branch.php
│   │   ├── 🐘 Court.php
│   │   ├── 🐘 CourtPriceRule.php
│   │   ├── 🐘 Facility.php
│   │   ├── 🐘 Payment.php
│   │   ├── 🐘 Sport.php
│   │   └── 🐘 User.php
│   └── 📁 Providers
│       └── 🐘 AppServiceProvider.php
├── 📁 bootstrap
│   ├── 🐘 app.php
│   └── 🐘 providers.php
├── 📁 cmd
│   └── 🐹 main.go
├── 📁 config
│   ├── 🐘 app.php
│   ├── 🐘 auth.php
│   ├── 🐘 cache.php
│   ├── 🐘 database.php
│   ├── 🐘 filesystems.php
│   ├── 🐘 logging.php
│   ├── 🐘 mail.php
│   ├── 🐘 queue.php
│   ├── 🐘 services.php
│   └── 🐘 session.php
├── 📁 configs
│   └── ⚙️ config.yaml
├── 📁 database
│   ├── 📁 factories
│   │   └── 🐘 UserFactory.php
│   ├── 📁 migrations
│   │   ├── 🐘 0001_01_01_000001_create_cache_table.php
│   │   ├── 🐘 0001_01_01_000002_create_jobs_table.php
│   │   ├── 🐘 2026_01_01_000001_create_branches_table.php
│   │   ├── 🐘 2026_01_01_000002_create_users_table.php
│   │   ├── 🐘 2026_01_01_000003_create_sports_table.php
│   │   ├── 🐘 2026_01_01_000004_create_courts_table.php
│   │   ├── 🐘 2026_01_01_000005_create_facilities_and_pivot_tables.php
│   │   ├── 🐘 2026_01_01_000006_create_court_price_rules_table.php
│   │   ├── 🐘 2026_01_01_000007_create_bookings_table.php
│   │   ├── 🐘 2026_01_01_000008_create_booking_items_table.php
│   │   ├── 🐘 2026_01_01_000009_create_payments_table.php
│   │   ├── 🐘 2026_01_01_000010_create_activity_logs_table.php
│   │   └── 🐘 2026_10_14_173634_create_sessions_table.php
│   ├── 📁 seeders
│   │   └── 🐘 DatabaseSeeder.php
│   ├── ⚙️ .gitignore
│   └── 📄 database.sqlite
├── 📁 internal
│   ├── 📁 model
│   ├── 📁 repository
│   └── 📁 service
├── 📁 pkg
│   └── 📁 utils
├── 📁 public
│   ├── ⚙️ .htaccess
│   ├── 📄 favicon.ico
│   ├── 🐘 index.php
│   └── 📄 robots.txt
├── 📁 resources
│   ├── 📁 css
│   │   └── 🎨 app.css
│   ├── 📁 js
│   │   └── 📄 app.js
│   └── 📁 views
│       ├── 📁 admin
│       │   ├── 📁 branches
│       │   │   ├── 🐘 create.blade.php
│       │   │   ├── 🐘 edit.blade.php
│       │   │   └── 🐘 index.blade.php
│       │   ├── 📁 courts
│       │   │   ├── 🐘 create.blade.php
│       │   │   ├── 🐘 edit.blade.php
│       │   │   └── 🐘 index.blade.php
│       │   ├── 📁 orders
│       │   │   ├── 🐘 index.blade.php
│       │   │   └── 🐘 show.blade.php
│       │   ├── 📁 price-rules
│       │   │   ├── 🐘 create.blade.php
│       │   │   └── 🐘 index.blade.php
│       │   ├── 📁 reports
│       │   │   └── 🐘 revenue.blade.php
│       │   └── 🐘 dashboard.blade.php
│       ├── 📁 auth
│       │   ├── 📁 passwords
│       │   │   ├── 🐘 email.blade.php
│       │   │   └── 🐘 reset.blade.php
│       │   ├── 🐘 confirm-password.blade.php
│       │   ├── 🐘 login.blade.php
│       │   ├── 🐘 register.blade.php
│       │   └── 🐘 verify-email.blade.php
│       ├── 📁 components
│       │   └── 🐘 alert.blade.php
│       ├── 📁 customer
│       │   ├── 📁 bookings
│       │   │   ├── 🐘 checkout.blade.php
│       │   │   ├── 🐘 index.blade.php
│       │   │   └── 🐘 show.blade.php
│       │   ├── 📁 payments
│       │   │   └── 🐘 upload-proof.blade.php
│       │   ├── 🐘 dashboard.blade.php
│       │   └── 🐘 profile.blade.php
│       ├── 📁 layouts
│       │   ├── 🐘 admin.blade.php
│       │   ├── 🐘 app.blade.php
│       │   ├── 🐘 customer.blade.php
│       │   ├── 🐘 operator.blade.php
│       │   └── 🐘 public.blade.php
│       ├── 📁 operator
│       │   ├── 🐘 checkin.blade.php
│       │   ├── 🐘 dashboard.blade.php
│       │   ├── 🐘 schedule.blade.php
│       │   └── 🐘 walk-in.blade.php
│       ├── 📁 partials
│       │   ├── 🐘 admin-sidebar.blade.php
│       │   └── 🐘 public-navbar.blade.php
│       └── 📁 public
│           ├── 📁 courts
│           │   ├── 🐘 index.blade.php
│           │   └── 🐘 show.blade.php
│           └── 🐘 home.blade.php
├── 📁 routes
│   ├── 🐘 console.php
│   └── 🐘 web.php
├── 📁 storage
│   ├── 📁 app
│   │   ├── 📁 private
│   │   │   └── ⚙️ .gitignore
│   │   ├── 📁 public
│   │   │   └── ⚙️ .gitignore
│   │   └── ⚙️ .gitignore
│   ├── 📁 framework
│   │   ├── 📁 sessions
│   │   │   └── ⚙️ .gitignore
│   │   ├── 📁 testing
│   │   │   └── ⚙️ .gitignore
│   │   ├── 📁 views
│   │   │   ├── ⚙️ .gitignore
│   │   │   ├── 🐘 0bbd76f0ded5e7441f7ff2139137d937.php
│   │   │   ├── 🐘 0e108b3b79b5faa7576d856e1735371b.php
│   │   │   ├── 🐘 0fcf37209116cf6403579404717c1bb5.php
│   │   │   ├── 🐘 2242e605992a848eac0e3743cdfa2000.php
│   │   │   ├── 🐘 26e87331ed83b7cf20a8ce7e2e8d8fd8.php
│   │   │   ├── 🐘 27fec9dc67cd3cd45d87d096bee6c1a6.php
│   │   │   ├── 🐘 2c3eefacc3d8d4a41d1e70069e687a4d.php
│   │   │   ├── 🐘 32be2b83821ced4b4562b57c5663d574.php
│   │   │   ├── 🐘 3fa86964ffae597a9e6d663f5cf1278a.php
│   │   │   ├── 🐘 4087ffe826d7f1ea813ee2799d51dda8.php
│   │   │   ├── 🐘 4524a89db3e8f023d6334da41dd77485.php
│   │   │   ├── 🐘 46c32c4b49d2d773e9a602ff229f8dd1.php
│   │   │   ├── 🐘 4a1f2e00f99178388b08087f7f55fa12.php
│   │   │   ├── 🐘 4df71f4a990a7a6d66891677c64bb71e.php
│   │   │   ├── 🐘 4f621342b1adf733335b2642fadf080e.php
│   │   │   ├── 🐘 5079e69d51c4f925f73e12839d56050e.php
│   │   │   ├── 🐘 51704eecfa8b42e4f966bab9a902b50e.php
│   │   │   ├── 🐘 55e627258669d4940d00709f3312a6af.php
│   │   │   ├── 🐘 5a2de2af4956234bcc2ac4ff7ffe1a0e.php
│   │   │   ├── 🐘 5e7bddb58beb9e409ccd667abc7d023e.php
│   │   │   ├── 🐘 5feaf81eec0dee851d6c9f6fab11d8a0.php
│   │   │   ├── 🐘 61bf235468792c287c954947ad440763.php
│   │   │   ├── 🐘 65c671581a35d3a6894da0876751e0bb.php
│   │   │   ├── 🐘 6d7ed74993776ea98b2c2098f32e6c28.php
│   │   │   ├── 🐘 760e7850049f27969c2c6a024b84e9f5.php
│   │   │   ├── 🐘 77754325315a5091513daf57ba51bd92.php
│   │   │   ├── 🐘 7b73d1aead848627a0063704c4fd70f7.php
│   │   │   ├── 🐘 8eb41ace1fede7e281e86823cfd77359.php
│   │   │   ├── 🐘 923ccc41383a909f950d3817ec30f14c.php
│   │   │   ├── 🐘 9bde8b059ab8103ec5634101a47c929a.php
│   │   │   ├── 🐘 9c86333a907e37963b82d1bc92fa05a8.php
│   │   │   ├── 🐘 9d25359d12fb747a31a747555899a3fe.php
│   │   │   ├── 🐘 a1a746a1c45164d21bb13db43c6d8480.php
│   │   │   ├── 🐘 a712e73ad224ec899ae393651a63f128.php
│   │   │   ├── 🐘 aa9367f04e1be83343d46fb6a3381850.php
│   │   │   ├── 🐘 ad3a5d1ac7d9a85f6979b0b548570a98.php
│   │   │   ├── 🐘 b33a72be6b4bb05c653152b595cedf69.php
│   │   │   ├── 🐘 b54a8540076c45a79c7f01ff490a487d.php
│   │   │   ├── 🐘 b91a0e46ab2e147252e9bda4efef71c1.php
│   │   │   ├── 🐘 c49f6810f2c11068d0242aa954e1fb8e.php
│   │   │   ├── 🐘 c6d281202a46ca354345844a24570407.php
│   │   │   ├── 🐘 cc94a427c11a81957d00969784011224.php
│   │   │   ├── 🐘 cce28411e91f9266c13b9d714ae10cfd.php
│   │   │   ├── 🐘 cdbbdb2d8a76a40b8b0a192d355b81ef.php
│   │   │   ├── 🐘 d63ab02af8c1b018fea9f16ef693e002.php
│   │   │   ├── 🐘 dc3f569546474fcf32f278b34b71d4eb.php
│   │   │   ├── 🐘 e3f181c1b5e496c93f9a603224355592.php
│   │   │   ├── 🐘 f7035b7acaaac666b770151ec3dc88fa.php
│   │   │   ├── 🐘 f782b32a6706b3c4aceb229ba4419f99.php
│   │   │   ├── 🐘 fde1597bf4aae4a648e92727e792d4d4.php
│   │   │   └── 🐘 ff7ba27ebcf115ac29ff7e343ec6eca7.php
│   │   └── ⚙️ .gitignore
│   └── 📁 logs
│       └── ⚙️ .gitignore
├── 📁 tests
│   ├── 📁 Feature
│   │   ├── 🐘 AuthTest.php
│   │   └── 🐘 ExampleTest.php
│   ├── 📁 Unit
│   │   └── 🐘 ExampleTest.php
│   └── 🐘 TestCase.php
├── ⚙️ .editorconfig
├── ⚙️ .env.example
├── ⚙️ .gitattributes
├── ⚙️ .gitignore
├── ⚙️ .npmrc
├── 📝 AGENTS.md
├── 📝 ARCHITECTURE.md
├── 📝 CLAUDE.md
├── 📝 DATABASE.md
├── 📝 FEATURE.md
├── 📝 PROJECT.md
├── 📝 README.md
├── 📝 USER_FLOWS.md
├── 📄 artisan
├── ⚙️ composer.json
├── 📄 go.mod
├── ⚙️ package.json
├── ⚙️ phpunit.xml
└── 📄 vite.config.js
```