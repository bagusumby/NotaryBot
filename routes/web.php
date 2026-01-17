<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BotTrainingController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\IntentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UnansweredQuestionController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\QuickResponseController;

// Public routes
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/booking', [AppointmentController::class, 'booking'])->name('booking');

// Chatbot routes
Route::post('/chatbot/register', [ChatbotController::class, 'register'])->name('chatbot.register');
Route::get('/chatbot/check-user', [ChatbotController::class, 'checkUser'])->name('chatbot.checkUser');
Route::get('/chatbot/welcome', [ChatbotController::class, 'welcome'])->name('chatbot.welcome');
Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');
Route::post('/chatbot/review', [ChatbotController::class, 'submitReview'])->name('chatbot.review');

// API untuk quick responses
Route::get('/api/quick-responses', [QuickResponseController::class, 'getQuickResponses'])->name('api.quick-responses');

// Auth routes - Using Laravel Auth
Route::get('/login', function() {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('login');
})->name('login')->middleware('guest');

Route::post('/login', function(\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (auth()->attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->middleware('guest');

Route::post('/logout', function(\Illuminate\Http\Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');

// Protected routes - Admin Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedule-management', [ScheduleController::class, 'index'])->name('schedule-management');
    Route::post('/schedule-management/update-status', [ScheduleController::class, 'updateStatus'])->name('schedule-management.update.status');
    
    // Appointments CRUD
    Route::get('/appointments/available-slots', [AppointmentController::class, 'getAvailableSlots'])->name('appointments.getSlots');
    Route::resource('appointments', AppointmentController::class);
    
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/appointments', [ReportsController::class, 'appointments'])->name('reports.appointments');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
    Route::get('/unanswered-questions', [UnansweredQuestionController::class, 'index'])->name('unanswered-questions');
    
    // Admin only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management');
        Route::resource('users', UserController::class);
        Route::get('/bot-training', function() {
            return redirect()->route('intents.index');
        })->name('bot-training');
        
        // Intent/Bot Training routes
        Route::resource('intents', IntentController::class);
        Route::post('intents/{intent}/sync', [IntentController::class, 'sync'])->name('intents.sync');
        Route::post('intents-import', [IntentController::class, 'import'])->name('intents.import');
        
        // Employee Schedule routes
        Route::resource('employee-schedules', EmployeeScheduleController::class);
        
        // System Settings routes
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
    // Intent/Bot Training routes
    Route::resource('intents', IntentController::class);
    Route::post('intents/{intent}/sync', [IntentController::class, 'sync'])->name('intents.sync');
    Route::post('intents-import', [IntentController::class, 'import'])->name('intents.import');
    Route::post('intents/{intent}/mark-solved', [IntentController::class, 'markQuestionSolved'])->name('intents.mark-solved');
    
    // Quick Response routes
    Route::resource('quick-responses', QuickResponseController::class);
    Route::post('quick-responses/update-order', [QuickResponseController::class, 'updateOrder'])->name('quick-responses.update-order');
    Route::post('quick-responses/{id}/toggle', [QuickResponseController::class, 'toggle'])->name('quick-responses.toggle');
});

