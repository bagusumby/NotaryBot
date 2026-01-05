@extends('layouts.app')

@section('title', 'Notary Services - Landing Page')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-shield-alt w-8 h-8 text-blue-600"></i>
                        <span class="text-xl text-gray-900">Notary Services</span>
                    </div>
                    <nav class="hidden md:flex gap-6">
                        <a href="#services" class="text-gray-600 hover:text-blue-600">Layanan</a>
                        <a href="#about" class="text-gray-600 hover:text-blue-600">Tentang</a>
                        <a href="#contact" class="text-gray-600 hover:text-blue-600">Kontak</a>
                    </nav>
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Admin Login
                    </a>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-blue-600 to-blue-800 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-5xl mb-6">Layanan Notaris Terpercaya</h1>
                    <p class="text-xl mb-8 text-blue-100 max-w-2xl mx-auto">
                        Solusi lengkap untuk kebutuhan legalitas dan notaris Anda dengan pelayanan profesional dan
                        terpercaya
                    </p>
                    <a href="{{ route('booking') }}"
                        class="bg-white text-blue-600 px-8 py-3 rounded-lg hover:bg-gray-100 inline-flex items-center gap-2">
                        <i class="fas fa-calendar w-5 h-5"></i>
                        Buat Janji Sekarang
                    </a>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl text-gray-900 mb-4">Layanan Kami</h2>
                    <p class="text-xl text-gray-600">Berbagai layanan notaris untuk memenuhi kebutuhan Anda</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <i class="fas fa-file-alt w-12 h-12 text-blue-600 mb-4"></i>
                        <h3 class="text-xl text-gray-900 mb-2">Akta Jual Beli</h3>
                        <p class="text-gray-600">Pembuatan akta jual beli properti yang sah dan terpercaya</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <i class="fas fa-shield-alt w-12 h-12 text-blue-600 mb-4"></i>
                        <h3 class="text-xl text-gray-900 mb-2">Akta Pendirian PT</h3>
                        <p class="text-gray-600">Pengurusan akta pendirian perusahaan dan legalitas usaha</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <i class="fas fa-file-alt w-12 h-12 text-blue-600 mb-4"></i>
                        <h3 class="text-xl text-gray-900 mb-2">Legalisasi Dokumen</h3>
                        <p class="text-gray-600">Legalisasi dan pengesahan berbagai dokumen penting</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <i class="fas fa-shield-alt w-12 h-12 text-blue-600 mb-4"></i>
                        <h3 class="text-xl text-gray-900 mb-2">Surat Kuasa</h3>
                        <p class="text-gray-600">Pembuatan surat kuasa untuk berbagai keperluan hukum</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl text-gray-900 mb-4">Mengapa Memilih Kami?</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shield-alt w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl text-gray-900 mb-2">Terpercaya</h3>
                        <p class="text-gray-600">Notaris bersertifikat dengan pengalaman lebih dari 10 tahun</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clock w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl text-gray-900 mb-2">Cepat & Efisien</h3>
                        <p class="text-gray-600">Proses cepat dengan sistem appointment yang mudah</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-alt w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl text-gray-900 mb-2">Profesional</h3>
                        <p class="text-gray-600">Dokumen legal yang sesuai standar hukum Indonesia</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-4xl text-gray-900 mb-4">Hubungi Kami</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    <div class="text-center">
                        <i class="fas fa-map-marker-alt w-8 h-8 text-blue-600 mx-auto mb-2"></i>
                        <p class="text-gray-600">Jl. Sudirman No. 123, Jakarta Pusat</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-phone w-8 h-8 text-blue-600 mx-auto mb-2"></i>
                        <p class="text-gray-600">+62 21 1234 5678</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-envelope w-8 h-8 text-blue-600 mx-auto mb-2"></i>
                        <p class="text-gray-600">info@notaryservices.com</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>&copy; 2025 Notary Services. All rights reserved.</p>
            </div>
        </footer>

        <!-- Chatbot Button -->
        <button onclick="toggleChatbot()"
            class="fixed bottom-6 right-6 bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 z-50">
            <i class="fas fa-comment w-6 h-6"></i>
        </button>

        <!-- Chatbot Popup -->
        <div id="chatbot-popup" class="hidden fixed bottom-24 right-6 w-96 bg-white rounded-xl shadow-2xl z-50">
            <div class="bg-blue-600 text-white p-4 rounded-t-xl flex justify-between items-center">
                <h3 class="text-lg font-semibold">Chatbot Notaris</h3>
                <button onclick="toggleChatbot()" class="hover:bg-blue-700 p-1 rounded">
                    <i class="fas fa-times w-5 h-5"></i>
                </button>
            </div>

            <!-- Registration Form -->
            <div id="registration-form" class="p-6">
                <h4 class="font-semibold text-gray-900 mb-4">Selamat Datang!</h4>
                <p class="text-sm text-gray-600 mb-4">Silakan isi data Anda untuk memulai percakapan dengan bot kami.</p>
                <form id="register-form" class="space-y-4">
                    <div>
                        <label for="user-name" class="block text-sm font-medium text-gray-700 mb-1">Nama <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="user-name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                            placeholder="Masukkan nama Anda">
                    </div>
                    <div>
                        <label for="user-email" class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" id="user-email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                            placeholder="contoh@email.com">
                    </div>
                    <div>
                        <label for="user-phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP/WA <span
                                class="text-red-500">*</span></label>
                        <input type="tel" id="user-phone" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                            placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label for="user-company" class="block text-sm font-medium text-gray-700 mb-1">Perusahaan <span
                                class="text-gray-400 text-xs">(Opsional)</span></label>
                        <input type="text" id="user-company"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                            placeholder="Nama perusahaan">
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Mulai Chat
                    </button>
                </form>
            </div>

            <!-- Chat Area (hidden initially) -->
            <div id="chat-area" class="hidden">
                <div class="h-96 overflow-y-auto p-4" id="chat-messages">
                    <!-- Messages will be added here -->
                </div>
                <div class="p-4 border-t">
                    <div class="flex gap-2">
                        <input type="text" id="chat-input" placeholder="Ketik pesan..."
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <button onclick="sendMessage()"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-paper-plane w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let isRegistered = false;
            let inactivityTimer = null;
            let followUpShown = false;
            let lastFollowUpTime = null; // Track when follow-up was shown
            let followUpTimeoutId = null; // Track the 5-minute timeout
            const FOLLOWUP_TIMEOUT = 1 * 60 * 1000; // 1 menit
            const FOLLOWUP_SESSION_TIMEOUT = 5 * 60 * 1000; // 5 menit after follow-up
            const CHAT_HISTORY_KEY = 'notarybot_chat_history';
            const CHAT_TIMESTAMP_KEY = 'notarybot_chat_timestamp';
            const SESSION_TIMEOUT = 15 * 60 * 1000; // 15 menit
            let sessionCheckInterval = null;

            // Auto-check user registration on page load
            document.addEventListener('DOMContentLoaded', () => {
                checkUserRegistrationOnLoad();
                startSessionCheck();
            });

            function startSessionCheck() {
                // Check session every minute
                if (sessionCheckInterval) {
                    clearInterval(sessionCheckInterval);
                }

                sessionCheckInterval = setInterval(() => {
                    const timestamp = localStorage.getItem(CHAT_TIMESTAMP_KEY);
                    const now = Date.now();

                    if (timestamp && (now - parseInt(timestamp)) > SESSION_TIMEOUT) {
                        if (isRegistered) {
                            handleSessionTimeout();
                        }
                    }
                }, 60000); // Check every 1 minute
            }

            function checkUserRegistrationOnLoad() {
                fetch('{{ route('chatbot.checkUser') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.registered) {
                            isRegistered = true;
                        }
                    })
                    .catch(err => {
                        console.error('Check user error:', err);
                    });
            }

            function toggleChatbot() {
                const popup = document.getElementById('chatbot-popup');
                popup.classList.toggle('hidden');

                // Load chat state when opening
                if (!popup.classList.contains('hidden')) {
                    // Check session timeout first
                    const timestamp = localStorage.getItem(CHAT_TIMESTAMP_KEY);
                    const now = Date.now();

                    if (timestamp && (now - parseInt(timestamp)) > SESSION_TIMEOUT) {
                        handleSessionTimeout();
                        return;
                    }

                    if (isRegistered) {
                        showChatArea();
                        loadChatHistory();

                        // Jika tidak ada history, load welcome message
                        const history = getChatHistory();
                        if (!history || history.length === 0) {
                            loadWelcomeMessage();
                        } else {
                            startInactivityTimer();
                        }
                    } else {
                        checkUserRegistration();
                    }
                }
            }

            function checkUserRegistration() {
                fetch('{{ route('chatbot.checkUser') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.registered) {
                            isRegistered = true;
                            showChatArea();
                            loadChatHistory();

                            // Jika tidak ada history, load welcome message
                            const history = getChatHistory();
                            if (!history || history.length === 0) {
                                loadWelcomeMessage();
                            } else {
                                startInactivityTimer();
                            }
                        } else {
                            showRegistrationForm();
                        }
                    })
                    .catch(err => {
                        console.error('Check user error:', err);
                        showRegistrationForm();
                    });
            }

            function getChatHistory() {
                const timestamp = localStorage.getItem(CHAT_TIMESTAMP_KEY);
                const now = Date.now();

                // Clear history jika sudah lebih dari 15 menit
                if (timestamp && (now - parseInt(timestamp)) > SESSION_TIMEOUT) {
                    handleSessionTimeout();
                    return [];
                }

                const history = localStorage.getItem(CHAT_HISTORY_KEY);
                return history ? JSON.parse(history) : [];
            }

            function handleSessionTimeout() {
                // Clear localStorage
                clearChatHistory();

                // Reset registration status
                isRegistered = false;

                // Clear follow-up state
                followUpShown = false;
                lastFollowUpTime = null;
                if (inactivityTimer) {
                    clearTimeout(inactivityTimer);
                    inactivityTimer = null;
                }
                if (followUpTimeoutId) {
                    clearTimeout(followUpTimeoutId);
                    followUpTimeoutId = null;
                }

                // Show registration form again
                showRegistrationForm();

                // Optional: Show notification
                const messagesContainer = document.getElementById('chat-messages');
                if (messagesContainer) {
                    messagesContainer.innerHTML = `
            <div class="flex justify-center mb-4">
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-clock mr-2"></i>Session Anda telah berakhir. Silakan daftar ulang.
                </div>
            </div>
        `;
                }
            }

            function saveChatHistory(message, sender, type = 'text') {
                const history = getChatHistory();
                history.push({
                    message,
                    sender,
                    type,
                    timestamp: Date.now()
                });
                localStorage.setItem(CHAT_HISTORY_KEY, JSON.stringify(history));
                updateChatTimestamp();
            }

            function updateChatTimestamp() {
                localStorage.setItem(CHAT_TIMESTAMP_KEY, Date.now().toString());
            }

            function clearChatHistory() {
                localStorage.removeItem(CHAT_HISTORY_KEY);
                localStorage.removeItem(CHAT_TIMESTAMP_KEY);
                const messagesContainer = document.getElementById('chat-messages');
                if (messagesContainer) {
                    messagesContainer.innerHTML = '';
                }
            }

            function loadChatHistory() {
                const history = getChatHistory();
                const messagesContainer = document.getElementById('chat-messages');

                console.log('Loading chat history:', history); // Debug log

                if (history.length > 0) {
                    messagesContainer.innerHTML = ''; // Clear first
                    history.forEach(item => {
                        if (item.type === 'text') {
                            addMessageToDOM(item.message, item.sender);
                        } else if (item.type === 'review-buttons') {
                            console.log('Restoring review buttons'); // Debug log
                            showReviewButtons();
                        }
                    });
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }

            function loadWelcomeMessage() {
                fetch('{{ route('chatbot.welcome') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(msg => {
                                if (msg.type === 'text' && msg.text && msg.text.length > 0) {
                                    msg.text.forEach(text => {
                                        addBotMessage(text);
                                    });
                                } else if (msg.type === 'quickReplies') {
                                    addBotMessage(msg.title);
                                    addQuickReplies(msg.replies);
                                } else if (msg.type === 'card') {
                                    addCard(msg);
                                }
                            });
                        }
                        updateChatTimestamp();
                        startInactivityTimer();
                    })
                    .catch(err => {
                        console.error('Welcome error:', err);
                        addBotMessage('Halo! Ada yang bisa saya bantu?');
                        updateChatTimestamp();
                        startInactivityTimer();
                    });
            }

            function checkUserRegistration() {
                fetch('{{ route('chatbot.checkUser') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.registered) {
                            isRegistered = true;
                            showChatArea();
                            startInactivityTimer();
                        } else {
                            showRegistrationForm();
                        }
                    })
                    .catch(err => {
                        console.error('Check user error:', err);
                        showRegistrationForm();
                    });
            }

            function showRegistrationForm() {
                document.getElementById('registration-form').classList.remove('hidden');
                document.getElementById('chat-area').classList.add('hidden');
            }

            function showChatArea() {
                document.getElementById('registration-form').classList.add('hidden');
                document.getElementById('chat-area').classList.remove('hidden');
            }

            function startInactivityTimer() {
                clearTimeout(inactivityTimer);

                // Don't reset followUpShown if already shown
                // followUpShown = false; // REMOVED to prevent duplicate

                inactivityTimer = setTimeout(() => {
                    if (!followUpShown) {
                        showFollowUpMessage();
                        followUpShown = true;
                        lastFollowUpTime = Date.now();

                        // Start 5-minute countdown after follow-up shown
                        startFollowUpSessionTimeout();
                    }
                }, FOLLOWUP_TIMEOUT);
            }

            function resetInactivityTimer() {
                // Clear the 5-minute timeout if user is active again
                if (followUpTimeoutId) {
                    clearTimeout(followUpTimeoutId);
                    followUpTimeoutId = null;
                }

                // Reset follow-up flag when user is active again
                followUpShown = false;
                lastFollowUpTime = null;
                startInactivityTimer();
            }

            function showFollowUpMessage() {
                // Remove old review buttons first
                removeReviewButtons();

                addBotMessage('Apakah ada pertanyaan lain?');
                showReviewButtons();
                // Save review buttons state to localStorage
                saveChatHistory('review-buttons', 'bot', 'review-buttons');
            }

            function removeReviewButtons() {
                // Remove from DOM
                const existingButtons = document.querySelectorAll('.review-buttons');
                existingButtons.forEach(btn => btn.remove());

                // Remove from localStorage history
                const history = getChatHistory();
                const filteredHistory = history.filter(item => item.type !== 'review-buttons');
                localStorage.setItem(CHAT_HISTORY_KEY, JSON.stringify(filteredHistory));
            }

            function startFollowUpSessionTimeout() {
                // Clear any existing timeout first
                if (followUpTimeoutId) {
                    clearTimeout(followUpTimeoutId);
                }

                // After follow-up shown, wait 5 minutes then force session timeout
                followUpTimeoutId = setTimeout(() => {
                    // If user hasn't interacted for 5 minutes after follow-up, timeout
                    if (followUpShown) {
                        handleSessionTimeout();
                    }
                }, FOLLOWUP_SESSION_TIMEOUT);
            }

            function showReviewButtons() {
                const messagesContainer = document.getElementById('chat-messages');

                // Remove any existing buttons first to prevent duplicates
                const existingButtons = document.querySelectorAll('.review-buttons');
                existingButtons.forEach(btn => btn.remove());

                const reviewContainer = document.createElement('div');
                reviewContainer.className = 'review-buttons';
                reviewContainer.innerHTML = `
        <div style="margin-top: 10px;">
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Bagaimana pengalaman Anda?</p>
            <div style="display: flex; gap: 10px;">
                <button onclick="submitReview('positive')" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm">
                    👍 Baik
                </button>
                <button onclick="submitReview('negative')" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                    👎 Kurang Baik
                </button>
            </div>
        </div>
    `;
                messagesContainer.appendChild(reviewContainer);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function submitReview(rating) {
                fetch('{{ route('chatbot.review') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            rating
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Hapus review buttons
                            const reviewButtons = document.querySelector('.review-buttons');
                            if (reviewButtons) {
                                reviewButtons.remove();
                            }

                            // Remove review buttons from localStorage
                            const history = getChatHistory();
                            const filteredHistory = history.filter(item => item.type !== 'review-buttons');
                            localStorage.setItem(CHAT_HISTORY_KEY, JSON.stringify(filteredHistory));

                            addBotMessage('Terima kasih atas feedback Anda! 🙏');
                            clearTimeout(inactivityTimer);
                        }
                    })
                    .catch(err => {
                        console.error('Review error:', err);
                    });
            }

            document.getElementById('register-form')?.addEventListener('submit', async (e) => {
                e.preventDefault();

                const name = document.getElementById('user-name').value.trim();
                const email = document.getElementById('user-email').value.trim();
                const phone = document.getElementById('user-phone').value.trim();
                const company = document.getElementById('user-company').value.trim();

                if (!name || !email || !phone) return;

                try {
                    const response = await fetch('{{ route('chatbot.register') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name,
                            email,
                            phone,
                            company
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        isRegistered = true;
                        showChatArea();

                        // Clear history untuk user baru
                        clearChatHistory();

                        // Trigger welcome message from Dialogflow
                        const welcomeResponse = await fetch('{{ route('chatbot.welcome') }}');
                        const welcomeData = await welcomeResponse.json();

                        if (welcomeData.messages && welcomeData.messages.length > 0) {
                            welcomeData.messages.forEach(msg => {
                                if (msg.type === 'text' && msg.text && msg.text.length > 0) {
                                    msg.text.forEach(text => {
                                        addBotMessage(text);
                                    });
                                } else if (msg.type === 'quickReplies') {
                                    addBotMessage(msg.title);
                                    addQuickReplies(msg.replies);
                                } else if (msg.type === 'card') {
                                    addCard(msg);
                                }
                            });
                            // Start inactivity timer after welcome message
                            startInactivityTimer();
                        }
                    } else {
                        alert(data.message || 'Registrasi gagal. Silakan coba lagi.');
                    }
                } catch (err) {
                    console.error('Registration error:', err);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });

            function sendMessage() {
                const input = document.getElementById('chat-input');
                const message = input.value.trim();
                if (!message) return;

                // Check session timeout before sending
                const timestamp = localStorage.getItem(CHAT_TIMESTAMP_KEY);
                const now = Date.now();

                if (timestamp && (now - parseInt(timestamp)) > SESSION_TIMEOUT) {
                    handleSessionTimeout();
                    return;
                }

                // Reset inactivity timer on user interaction
                resetInactivityTimer();

                // Remove old review buttons when user sends new message
                removeReviewButtons();

                const messagesContainer = document.getElementById('chat-messages');

                // Add user message
                const userMessage = document.createElement('div');
                userMessage.className = 'bg-blue-600 text-white p-3 rounded-lg mb-2 ml-auto max-w-[80%]';
                userMessage.innerHTML = `<p class="text-sm">${escapeHtml(message)}</p>`;
                messagesContainer.appendChild(userMessage);

                // Save user message to localStorage
                saveChatHistory(message, 'user', 'text');

                input.value = '';
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // Show typing indicator
                const typingIndicator = document.createElement('div');
                typingIndicator.className = 'bg-gray-100 p-3 rounded-lg mb-2 typing-indicator';
                typingIndicator.innerHTML = '<p class="text-sm text-gray-800">Bot sedang mengetik...</p>';
                messagesContainer.appendChild(typingIndicator);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // Send to Dialogflow
                fetch('{{ route('chatbot.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Remove typing indicator
                        typingIndicator.remove();

                        if (data.error) {
                            console.error('Bot error:', data.error);
                            addBotMessage('Maaf, terjadi kesalahan. Silakan coba lagi.');
                            return;
                        }

                        // Handle payload messages (rich content)
                        if (data.payload && data.payload.length > 0) {
                            data.payload.forEach(msg => {
                                if (msg.type === 'text' && msg.text && msg.text.length > 0) {
                                    msg.text.forEach(text => {
                                        addBotMessage(text);
                                    });
                                } else if (msg.type === 'quickReplies') {
                                    addBotMessage(msg.title);
                                    addQuickReplies(msg.replies);
                                } else if (msg.type === 'card') {
                                    addCard(msg);
                                }
                            });
                        } else if (data.reply) {
                            addBotMessage(data.reply);
                        }

                        // Update timestamp and reset timer after bot response
                        updateChatTimestamp();
                        resetInactivityTimer();
                    })
                    .catch(err => {
                        console.error('Fetch error:', err);
                        typingIndicator.remove();
                        addBotMessage('Maaf, terjadi kesalahan koneksi. Silakan coba lagi.');
                    });
            }

            function addBotMessage(text) {
                addMessageToDOM(text, 'bot');
                saveChatHistory(text, 'bot', 'text');
            }

            function addMessageToDOM(text, sender) {
                const messagesContainer = document.getElementById('chat-messages');
                const messageDiv = document.createElement('div');

                if (sender === 'bot') {
                    messageDiv.className = 'bg-gray-100 p-3 rounded-lg mb-2 max-w-[80%]';
                    messageDiv.innerHTML = `<p class="text-sm text-gray-800">${escapeHtml(text)}</p>`;
                } else {
                    messageDiv.className = 'bg-blue-600 text-white p-3 rounded-lg mb-2 ml-auto max-w-[80%]';
                    messageDiv.innerHTML = `<p class="text-sm">${escapeHtml(text)}</p>`;
                }

                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function addQuickReplies(replies) {
                const messagesContainer = document.getElementById('chat-messages');
                const quickRepliesContainer = document.createElement('div');
                quickRepliesContainer.className = 'flex flex-wrap gap-2 mb-2';

                replies.forEach(reply => {
                    const button = document.createElement('button');
                    button.className =
                        'px-3 py-2 bg-white border border-blue-600 text-blue-600 rounded-lg text-sm hover:bg-blue-50 transition-colors';
                    button.textContent = reply;
                    button.onclick = () => {
                        quickRepliesContainer.remove();
                        document.getElementById('chat-input').value = reply;
                        sendMessage();
                    };
                    quickRepliesContainer.appendChild(button);
                });

                messagesContainer.appendChild(quickRepliesContainer);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function addCard(card) {
                const messagesContainer = document.getElementById('chat-messages');
                const cardElement = document.createElement('div');
                cardElement.className = 'bg-white border border-gray-200 rounded-lg p-4 mb-2 max-w-[80%]';

                let cardHTML = '';
                if (card.imageUri) {
                    cardHTML +=
                        `<img src="${escapeHtml(card.imageUri)}" class="w-full rounded-lg mb-2" alt="${escapeHtml(card.title)}">`;
                }
                if (card.title) {
                    cardHTML += `<h4 class="font-semibold text-gray-900 mb-1">${escapeHtml(card.title)}</h4>`;
                }
                if (card.subtitle) {
                    cardHTML += `<p class="text-sm text-gray-600 mb-2">${escapeHtml(card.subtitle)}</p>`;
                }
                if (card.buttons && card.buttons.length > 0) {
                    cardHTML += '<div class="flex flex-col gap-2 mt-2">';
                    card.buttons.forEach(button => {
                        cardHTML +=
                            `<button onclick="handleCardButton('${escapeHtml(button.postback)}')" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">${escapeHtml(button.text)}</button>`;
                    });
                    cardHTML += '</div>';
                }

                cardElement.innerHTML = cardHTML;
                messagesContainer.appendChild(cardElement);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function handleCardButton(postback) {
                document.getElementById('chat-input').value = postback;
                sendMessage();
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            document.getElementById('chat-input')?.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });
        </script>
    @endpush
@endsection
