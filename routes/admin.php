<?php

use App\Http\Controllers\Admin\AiController as AdminAiController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CustomPageController as AdminPageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SecurityController as AdminSecurityController;
use App\Http\Controllers\Admin\SeoController as AdminSeoController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSettingController;
use App\Http\Controllers\Admin\SupportTicketController as AdminTicketController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post')->middleware('brute.force:admin');
    });

    Route::middleware(['auth:admin', 'admin.active'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
            Route::get('/{user}/tasks', [AdminUserController::class, 'tasks'])->name('tasks');
            Route::post('/{user}/toggle-ban', [AdminUserController::class, 'toggleBan'])->name('toggle-ban');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', [AdminTicketController::class, 'index'])->name('index');
            Route::get('/{ticket}', [AdminTicketController::class, 'show'])->name('show');
            Route::post('/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('reply');
            Route::put('/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('status');
            Route::put('/{ticket}/assign', [AdminTicketController::class, 'assign'])->name('assign');
        });

        Route::resource('pages', AdminPageController::class)->except(['show']);

        Route::prefix('seo')->name('seo.')->group(function () {
            Route::get('/', [AdminSeoController::class, 'index'])->name('index');
            Route::put('/global', [AdminSeoController::class, 'updateGlobal'])->name('global');
            Route::post('/page', [AdminSeoController::class, 'storePage'])->name('page.store');
            Route::put('/page/{seoSetting}', [AdminSeoController::class, 'updatePage'])->name('page.update');
        });

        Route::prefix('security')->name('security.')->group(function () {
            Route::get('/', [AdminSecurityController::class, 'index'])->name('index');
            Route::put('/', [AdminSecurityController::class, 'update'])->name('update');
            Route::post('/block-ip', [AdminSecurityController::class, 'blockIp'])->name('block-ip');
            Route::post('/unlock-user/{user}', [AdminSecurityController::class, 'unlockUser'])->name('unlock-user');
            Route::delete('/clear-attempts', [AdminSecurityController::class, 'clearLoginAttempts'])->name('clear-attempts');
        });

        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

        Route::prefix('ai')->name('ai.')->group(function () {
            Route::get('/settings', [AdminAiController::class, 'settings'])->name('settings');
            Route::put('/settings', [AdminAiController::class, 'updateSettings'])->name('settings.update');
            Route::get('/prompts', [AdminAiController::class, 'prompts'])->name('prompts.index');
            Route::get('/prompts/{prompt}/edit', [AdminAiController::class, 'editPrompt'])->name('prompts.edit');
            Route::put('/prompts/{prompt}', [AdminAiController::class, 'updatePrompt'])->name('prompts.update');
            Route::post('/prompts/{prompt}/toggle', [AdminAiController::class, 'togglePrompt'])->name('prompts.toggle');
        });

        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'password'])->name('profile.password');
    });
});
