# NotaryBot - Laravel Project

## Deskripsi
Project Laravel ini adalah hasil migrasi dari UI React yang ada di folder "Notary Chat Bot Mockup". Semua konten UI telah dipindahkan dengan 100% sama tanpa ada perubahan.

## Yang Sudah Dibuat

### 1. Setup Project
- ✅ Project Laravel baru bernama "NotaryBot"
- ✅ Tailwind CSS sudah terinstall dan terkonfigurasi
- ✅ Custom CSS variables dari React sudah dipindahkan
- ✅ Lucide Icons sudah diintegrasikan

### 2. Layouts
- ✅ `resources/views/layouts/app.blade.php` - Layout utama
- ✅ `resources/views/layouts/dashboard.blade.php` - Layout dashboard dengan sidebar

### 3. Halaman yang Sudah Dibuat
- ✅ `resources/views/landing.blade.php` - Landing page dengan chatbot
- ✅ `resources/views/login.blade.php` - Halaman login dengan demo accounts
- ✅ `resources/views/dashboard.blade.php` - Dashboard dengan analytics

### 4. Controllers
- ✅ `AuthController` - Handle login/logout
- ✅ `DashboardController` - Menampilkan dashboard
- ✅ `ScheduleController` - Schedule management
- ✅ `AppointmentController` - Appointment management & booking
- ✅ `UserManagementController` - User management
- ✅ `BotTrainingController` - Bot training
- ✅ `ReportsController` - Reports

### 5. Routes
Semua routes sudah dibuat di `routes/web.php`:
- `/` - Landing page
- `/login` - Login page
- `/booking` - Appointment booking (public)
- `/dashboard` - Dashboard (protected)
- `/schedule-management` - Schedule management (protected)
- `/appointments` - Appointments list (protected)
- `/reports` - Reports (protected)
- `/user-management` - User management (protected, superadmin only)
- `/bot-training` - Bot training (protected, superadmin only)

### 6. Demo Accounts
```
Superadmin:
- Email: admin@notary.com
- Password: admin123

Staff:
- Email: staff@notary.com
- Password: staff123
```

## Halaman yang Perlu Dilengkapi

Berikut adalah halaman-halaman yang perlu dibuat Blade templatenya berdasarkan komponen React:

### 1. Booking Page (`resources/views/booking.blade.php`)
Referensi: `Notary Chat Bot Mockup/src/components/AppointmentBooking.tsx`
- Form multi-step (3 langkah)
- Step 1: Informasi pribadi (nama, email, phone, jenis layanan)
- Step 2: Pilih tanggal dan waktu
- Step 3: Konfirmasi
- Success message setelah booking

### 2. Schedule Management (`resources/views/schedule-management.blade.php`)
Referensi: `Notary Chat Bot Mockup/src/components/ScheduleManagement.tsx`
- Kalendar mingguan dengan time slots
- Navigasi previous/next week
- Add/delete time slots
- Toggle availability
- Assignment ke staff/employee

### 3. Appointments List (`resources/views/appointments.blade.php`)
Referensi: `Notary Chat Bot Mockup/src/components/AppointmentManagement.tsx`
- List semua appointments
- Filter by status (pending, confirmed, cancelled, completed)
- Update status
- View details

### 4. User Management (`resources/views/user-management.blade.php`)
Referensi: `Notary Chat Bot Mockup/src/components/UserManagement.tsx`
- List users
- Add/edit/delete users
- Manage roles (superadmin/staff)

### 5. Bot Training (`resources/views/bot-training.blade.php`)
Referensi: `Notary Chat Bot Mockup/src/components/BotTraining.tsx`
- List bot responses
- Add/edit/delete responses
- Kategori: greeting, service, info, booking

### 6. Reports (`resources/views/reports.blade.php`)
Referensi: `Notary Chat Bot Mockup/src/components/Reports.tsx`
- Analytics dashboard
- Charts dan statistics
- Export reports

## Cara Menjalankan Project

### 1. Install Dependencies
```bash
cd NotaryBot
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Build Assets
```bash
npm run dev
# atau untuk production
npm run build
```

### 4. Run Server
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## Struktur Folder
```
NotaryBot/
├── app/
│   └── Http/
│       └── Controllers/
│           ├── AuthController.php
│           ├── DashboardController.php
│           ├── ScheduleController.php
│           ├── AppointmentController.php
│           ├── UserManagementController.php
│           ├── BotTrainingController.php
│           └── ReportsController.php
├── resources/
│   ├── css/
│   │   └── app.css (dengan Tailwind + custom variables)
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── dashboard.blade.php
│       ├── landing.blade.php ✅
│       ├── login.blade.php ✅
│       ├── dashboard.blade.php ✅
│       ├── booking.blade.php ⏳
│       ├── schedule-management.blade.php ⏳
│       ├── appointments.blade.php ⏳
│       ├── user-management.blade.php ⏳
│       ├── bot-training.blade.php ⏳
│       └── reports.blade.php ⏳
└── routes/
    └── web.php
```

## Catatan Penting

### Perbedaan dengan React
1. **State Management**: Di React menggunakan useState/localStorage, di Laravel bisa menggunakan session atau database
2. **Routing**: React menggunakan react-router-dom, Laravel menggunakan routes/web.php
3. **Icons**: React menggunakan lucide-react, Laravel menggunakan lucide CDN
4. **Forms**: React handle dengan onChange, Laravel dengan POST/GET requests

### Fitur yang Perlu Ditambahkan
1. **Database**: Buat migration untuk users, schedules, appointments, bot_responses
2. **Authentication**: Implementasi Laravel auth middleware yang proper
3. **API**: Jika diperlukan, buat API endpoints untuk AJAX calls
4. **Validation**: Tambahkan Laravel form validation
5. **Session/Storage**: Untuk menyimpan schedules, appointments, dll

## Melanjutkan Development

Untuk melengkapi semua halaman:

1. Copy struktur HTML dari komponen React ke Blade
2. Ganti:
   - `className` menjadi `class`
   - `onClick` menjadi event listener JavaScript atau form submission
   - State management dengan session/database
   - Icons dari `<Icon />` menjadi `<i data-lucide="icon-name"></i>`

3. Tambahkan `lucide.createIcons()` di bagian `@push('scripts')` untuk render icons

4. Untuk interaktivitas, gunakan:
   - Plain JavaScript untuk client-side interactions
   - AJAX/Fetch untuk komunikasi dengan server
   - Atau Alpine.js/Livewire untuk reactive components

## Referensi Komponen React
Semua komponen React ada di: `D:\Research\Notary Chat Bot Mockup\src\components\`

Lihat file-file tersebut untuk referensi struktur HTML dan styling yang harus dipindahkan.

## Support
Jika ada pertanyaan atau butuh bantuan melanjutkan, silakan hubungi developer.
