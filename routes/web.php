<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\BotTrainingController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\IntentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UnansweredQuestionController;

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

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - Admin Dashboard
Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/schedule-management', [ScheduleController::class, 'index'])->name('schedule-management');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
    Route::get('/unanswered-questions', [UnansweredQuestionController::class, 'index'])->name('unanswered-questions');
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management');
    Route::get('/bot-training', function() {
        return redirect()->route('intents.index');
    })->name('bot-training');
    
    // Intent/Bot Training routes
    Route::resource('intents', IntentController::class);
    Route::post('intents/{intent}/sync', [IntentController::class, 'sync'])->name('intents.sync');
    Route::post('intents-import', [IntentController::class, 'import'])->name('intents.import');
});

