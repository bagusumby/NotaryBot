# Quick Response Feature Documentation

## Overview
Fitur Quick Response memungkinkan admin untuk mengelola chips/tombol quick reply yang muncul di chatbot untuk membantu user memilih pertanyaan dengan cepat.

## Fitur Utama

### 1. **CRUD Quick Response**
- **Create**: Tambah quick response baru dengan title dan value
- **Read**: Lihat daftar semua quick responses (dikelompokkan berdasarkan type)
- **Update**: Edit quick response yang sudah ada
- **Delete**: Hapus quick response yang tidak diperlukan

### 2. **Dua Tipe Quick Response**
- **Welcome**: Ditampilkan saat user baru memulai chat (loadWelcomeMessage())
- **General**: Ditampilkan setelah bot memberikan respon

### 3. **Saran Pertanyaan Populer**
Fitur ini menampilkan saran berdasarkan intent yang paling sering ditanyakan (berdasarkan `usage_count` dari tabel `intents`). Admin dapat langsung menggunakan saran ini untuk membuat quick response baru.

### 4. **Pengaturan Order dan Status**
- **Order**: Menentukan urutan tampilan chips (angka kecil ditampilkan lebih dulu)
- **Is Active**: Toggle untuk mengaktifkan/nonaktifkan quick response

## Struktur Database

### Tabel: `quick_responses`
```sql
- id (bigint, PK)
- title (string) - Text yang ditampilkan pada button chip
- value (string) - Text yang dikirim ke bot saat chip diklik
- type (enum: 'welcome', 'general') - Tipe quick response
- order (integer) - Urutan tampilan
- is_active (boolean) - Status aktif/nonaktif
- created_at, updated_at (timestamp)
```

## File yang Dibuat/Dimodifikasi

### 1. Migration
- `database/migrations/2026_01_05_103417_create_quick_responses_table.php`

### 2. Model
- `app/Models/QuickResponse.php`

### 3. Controller
- `app/Http/Controllers/QuickResponseController.php`
  - `index()` - Tampilkan daftar quick responses + saran intent populer
  - `create()` - Form tambah quick response
  - `store()` - Simpan quick response baru
  - `edit()` - Form edit quick response
  - `update()` - Update quick response
  - `destroy()` - Hapus quick response
  - `getQuickResponses()` - API endpoint untuk chatbot

### 4. Views
- `resources/views/quick-responses/index.blade.php` - Halaman utama dengan tab Welcome/General
- `resources/views/quick-responses/create.blade.php` - Form tambah
- `resources/views/quick-responses/edit.blade.php` - Form edit

### 5. Routes
```php
// Protected routes (admin only)
Route::resource('quick-responses', QuickResponseController::class);

// Public API endpoint
Route::get('/api/quick-responses', [QuickResponseController::class, 'getQuickResponses']);
```

### 6. Sidebar Menu
- Ditambahkan link menu "Quick Response" di `resources/views/layouts/dashboard.blade.php`
- Hanya tampil untuk user dengan role `superadmin`

### 7. Chatbot Integration
File: `resources/views/landing.blade.php`

**Fungsi baru yang ditambahkan:**
```javascript
function loadQuickResponses(type = 'general') {
    // Load quick responses dari API dan tampilkan sebagai chips
}
```

**Integration points:**
1. Saat welcome message: `loadQuickResponses('welcome')`
2. Setelah bot response: `loadQuickResponses('general')`

## API Endpoint

### GET `/api/quick-responses`
Mengambil daftar quick responses yang aktif

**Query Parameters:**
- `type` (optional): 'welcome' atau 'general' (default: 'general')

**Response:**
```json
{
    "quickResponses": [
        {
            "title": "Layanan Notaris",
            "value": "Apa saja layanan notaris yang tersedia?"
        },
        {
            "title": "Buat Janji",
            "value": "Bagaimana cara membuat janji temu?"
        }
    ]
}
```

## Seeder
File: `database/seeders/QuickResponseSeeder.php`

Menyediakan sample data untuk quick responses:
- 4 welcome chips
- 4 general chips

**Menjalankan seeder:**
```bash
php artisan db:seed --class=QuickResponseSeeder
```

## Cara Menggunakan

### Untuk Admin:
1. Login ke dashboard admin
2. Klik menu "Quick Response" di sidebar
3. Lihat saran pertanyaan yang sering ditanyakan di bagian atas
4. Klik "Tambah Quick Response" untuk membuat baru
5. Atau klik "Gunakan" pada saran untuk auto-fill form
6. Atur order dan status aktif/nonaktif sesuai kebutuhan

### Untuk User (Chatbot):
1. Saat mulai chat, chips welcome akan muncul
2. Klik chip untuk mengirim pertanyaan
3. Setelah bot merespon, chips general akan muncul
4. Chips membantu user untuk bertanya lebih lanjut dengan cepat

## Benefits

1. **User Experience**: User tidak perlu mengetik, cukup klik chips
2. **Admin Insight**: Saran otomatis berdasarkan pertanyaan populer
3. **Flexible**: Admin bisa atur kapan chips ditampilkan (welcome/general)
4. **Easy Management**: CRUD interface yang user-friendly
5. **Performance**: API endpoint cepat, hanya load chips yang aktif

## Future Enhancements (Optional)

1. Analytics untuk tracking klik pada setiap chips
2. A/B testing untuk berbagai variasi chips
3. Auto-disable chips yang jarang diklik
4. Multi-language support untuk chips
5. Dynamic chips based on user context/session
