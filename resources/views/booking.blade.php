@extends('layouts.app')

@section('title', 'Buat Appointment - Notary Services')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <a href="{{ route('landing') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left w-5 h-5"></i>
                    Back to Home
                </a>
            </div>
        </header>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8">
                <h1 class="text-3xl text-gray-900 mb-2">Buat Appointment</h1>
                <p class="text-gray-600">Isi form berikut untuk membuat janji temu dengan notaris kami</p>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-blue-600" id="step-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-600 text-white">
                            1
                        </div>
                        <span class="ml-2 hidden sm:inline">Personal Info</span>
                    </div>
                    <div class="flex-1 h-1 mx-4 bg-gray-200" id="progress-1"></div>
                    <div class="flex items-center text-gray-400" id="step-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200">
                            2
                        </div>
                        <span class="ml-2 hidden sm:inline">Schedule</span>
                    </div>
                    <div class="flex-1 h-1 mx-4 bg-gray-200" id="progress-2"></div>
                    <div class="flex items-center text-gray-400" id="step-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200">
                            3
                        </div>
                        <span class="ml-2 hidden sm:inline">Confirm</span>
                    </div>
                </div>
            </div>

            <form id="booking-form" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <!-- Step 1: Personal Information -->
                <div id="form-step-1" class="space-y-6">
                    <h2 class="text-xl text-gray-900 mb-4">Informasi Pribadi</h2>

                    <div>
                        <label class="block text-gray-700 mb-2">Nama Lengkap *</label>
                        <div class="relative">
                            <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="text" name="clientName" id="clientName"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                placeholder="Masukkan nama lengkap" required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Email *</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="email" name="clientEmail" id="clientEmail"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                placeholder="email@example.com" required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Nomor WhatsApp *</label>
                        <div class="relative">
                            <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="tel" name="clientPhone" id="clientPhone"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                placeholder="+62 812 3456 7890" required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Jenis Layanan *</label>
                        <div class="relative">
                            <i class="fas fa-file-alt absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <select name="serviceType" id="serviceType"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                required>
                                <option value="">Pilih jenis layanan</option>
                                <option value="Akta Jual Beli">Akta Jual Beli</option>
                                <option value="Akta Pendirian PT">Akta Pendirian PT</option>
                                <option value="Legalisasi Dokumen">Legalisasi Dokumen</option>
                                <option value="Surat Kuasa">Surat Kuasa</option>
                                <option value="Akta Hibah">Akta Hibah</option>
                                <option value="Akta Perjanjian">Akta Perjanjian</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" onclick="nextStep(2)"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                        Lanjut ke Jadwal
                    </button>
                </div>

                <!-- Step 2: Schedule Selection -->
                <div id="form-step-2" class="space-y-6 hidden">
                    <h2 class="text-xl text-gray-900 mb-4">Pilih Jadwal</h2>

                    <div>
                        <label class="block text-gray-700 mb-2">Pilih Tanggal *</label>
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="date" name="date" id="date"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Pilih Waktu *</label>
                        <div id="time-slots" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <!-- Time slots will be populated here via JavaScript -->
                            <div class="text-center py-8 text-gray-500 col-span-full">
                                Pilih tanggal terlebih dahulu
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" onclick="prevStep(1)"
                            class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300">
                            Kembali
                        </button>
                        <button type="button" onclick="nextStep(3)"
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                            Lanjut ke Konfirmasi
                        </button>
                    </div>
                </div>

                <!-- Step 3: Confirmation -->
                <div id="form-step-3" class="space-y-6 hidden">
                    <h2 class="text-xl text-gray-900 mb-4">Konfirmasi Booking</h2>

                    <div class="bg-gray-50 p-4 rounded-lg space-y-3" id="confirmation-details">
                        <!-- Confirmation details will be populated here -->
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Catatan (Optional)</label>
                        <textarea name="notes" id="notes"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                            rows="4" placeholder="Tambahkan catatan atau informasi tambahan..."></textarea>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-900">
                            Konfirmasi akan dikirim via WhatsApp dan email. Mohon pastikan nomor dan email Anda benar.
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <button type="button" onclick="prevStep(2)"
                            class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-lg hover:bg-gray-300">
                            Kembali
                        </button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
                            Konfirmasi Booking
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentStep = 1;
            const formData = {};

            function nextStep(step) {
                // Hide current step
                document.getElementById(`form-step-${currentStep}`).classList.add('hidden');

                // Show next step
                document.getElementById(`form-step-${step}`).classList.remove('hidden');

                // Update progress
                updateProgress(step);

                // Update confirmation if going to step 3
                if (step === 3) {
                    updateConfirmation();
                }

                currentStep = step;
                lucide.createIcons();
            }

            function prevStep(step) {
                // Hide current step
                document.getElementById(`form-step-${currentStep}`).classList.add('hidden');

                // Show previous step
                document.getElementById(`form-step-${step}`).classList.remove('hidden');

                // Update progress
                updateProgress(step);

                currentStep = step;
                lucide.createIcons();
            }

            function updateProgress(step) {
                for (let i = 1; i <= 3; i++) {
                    const stepEl = document.getElementById(`step-${i}`);
                    const progressEl = document.getElementById(`progress-${i}`);

                    if (i <= step) {
                        stepEl.classList.remove('text-gray-400');
                        stepEl.classList.add('text-blue-600');
                        stepEl.querySelector('div').classList.remove('bg-gray-200');
                        stepEl.querySelector('div').classList.add('bg-blue-600', 'text-white');
                    } else {
                        stepEl.classList.remove('text-blue-600');
                        stepEl.classList.add('text-gray-400');
                        stepEl.querySelector('div').classList.remove('bg-blue-600', 'text-white');
                        stepEl.querySelector('div').classList.add('bg-gray-200');
                    }

                    if (progressEl) {
                        if (i < step) {
                            progressEl.classList.remove('bg-gray-200');
                            progressEl.classList.add('bg-blue-600');
                        } else {
                            progressEl.classList.remove('bg-blue-600');
                            progressEl.classList.add('bg-gray-200');
                        }
                    }
                }
            }

            function updateConfirmation() {
                const name = document.getElementById('clientName').value;
                const email = document.getElementById('clientEmail').value;
                const phone = document.getElementById('clientPhone').value;
                const service = document.getElementById('serviceType').value;
                const date = document.getElementById('date').value;

                const confirmationHtml = `
        <div>
            <p class="text-sm text-gray-600">Nama</p>
            <p class="text-gray-900">${name}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Email</p>
            <p class="text-gray-900">${email}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">WhatsApp</p>
            <p class="text-gray-900">${phone}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Layanan</p>
            <p class="text-gray-900">${service}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Tanggal</p>
            <p class="text-gray-900">${new Date(date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
        </div>
    `;

                document.getElementById('confirmation-details').innerHTML = confirmationHtml;
            }

            // Handle form submission
            document.getElementById('booking-form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Show success message
                alert('Booking berhasil! Konfirmasi akan dikirim via WhatsApp dan Email.');

                // Redirect to home
                window.location.href = '{{ route('landing') }}';
            });

            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('date').min = tomorrow.toISOString().split('T')[0];

            // Set maximum date to 90 days from now
            const maxDate = new Date();
            maxDate.setDate(maxDate.getDate() + 90);
            document.getElementById('date').max = maxDate.toISOString().split('T')[0];
        </script>
    @endpush
@endsection
