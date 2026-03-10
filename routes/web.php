<?php

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  WEB ROUTES                                                  ║
 * ║  Purpose: Define all web (browser) routes for the app        ║
 * ║  Learning: Route groups, middleware, resource routes          ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CareerPathController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedisDemoController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// PUBLIC ROUTES (no login required)
// ──────────────────────────────────────────────

/**
 * Home page — redirects to dashboard if logged in, login page if not
 */
Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    return Auth::guard('web')->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// ──────────────────────────────────────────────
// GUEST ROUTES (only accessible when NOT logged in)
// ──────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login (with brute force protection)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('brute.force');
});

// ──────────────────────────────────────────────
// AUTHENTICATED ROUTES (login required)
// ──────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * Task CRUD — Resource Route
     *
     * This single line creates ALL these routes:
     * GET    /tasks           → index   (list all)
     * GET    /tasks/create    → create  (show form)
     * POST   /tasks           → store   (save new)
     * GET    /tasks/{task}    → show    (view one)
     * GET    /tasks/{task}/edit → edit  (show edit form)
     * PUT    /tasks/{task}    → update  (save changes)
     * DELETE /tasks/{task}    → destroy (delete)
     */
    Route::resource('tasks', TaskController::class);

    /**
     * Categories — Using only the routes we need
     */
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    /**
     * Redis Demo — Interactive Redis Playground
     * All Redis demo routes under /redis-demo
     */
    // Route::prefix('redis-demo')->name('redis.')->group(function () {
    //     Route::get('/', [RedisDemoController::class, 'index'])->name('demo');
    //     Route::get('/info', [RedisDemoController::class, 'info'])->name('info');
    //     Route::post('/strings', [RedisDemoController::class, 'strings'])->name('strings');
    //     Route::post('/cache', [RedisDemoController::class, 'cacheDemo'])->name('cache');
    //     Route::post('/lists', [RedisDemoController::class, 'lists'])->name('lists');
    //     Route::post('/hashes', [RedisDemoController::class, 'hashes'])->name('hashes');
    //     Route::post('/counter', [RedisDemoController::class, 'counter'])->name('counter');
    //     Route::post('/flush', [RedisDemoController::class, 'flush'])->name('flush');
    // });

    /**
     * Career Path — Skill Roadmap & Learning Paths
     */
    Route::prefix('career-path')->name('career-path.')->group(function () {
        Route::get('/', [CareerPathController::class, 'index'])->name('index');
        Route::get('/create', [CareerPathController::class, 'create'])->name('create');
        Route::post('/', [CareerPathController::class, 'store'])->name('store');
        Route::get('/{careerPath}', [CareerPathController::class, 'show'])->name('show');
        Route::get('/{careerPath}/edit', [CareerPathController::class, 'edit'])->name('edit');
        Route::put('/{careerPath}', [CareerPathController::class, 'update'])->name('update');
        Route::delete('/{careerPath}', [CareerPathController::class, 'destroy'])->name('destroy');

        // Nested Task routes
        Route::post('/{careerPath}/tasks', [CareerPathController::class, 'storeTask'])->name('tasks.store');
        Route::get('/{careerPath}/tasks/{task}', [CareerPathController::class, 'showTask'])->name('task.show');
        Route::get('/{careerPath}/tasks/{task}/edit', [CareerPathController::class, 'editTask'])->name('task.edit');
        Route::put('/{careerPath}/tasks/{task}', [CareerPathController::class, 'updateTask'])->name('task.update');
        Route::delete('/{careerPath}/tasks/{task}', [CareerPathController::class, 'destroyTask'])->name('tasks.destroy');
    });

    /**
     * Support Tickets (User-facing)
     */
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [SupportTicketController::class, 'index'])->name('index');
        Route::get('/create', [SupportTicketController::class, 'create'])->name('create');
        Route::post('/', [SupportTicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [SupportTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('reply');
    });
});

// ──────────────────────────────────────────────
// PUBLIC CUSTOM PAGES (must be last to avoid route conflicts)
// ──────────────────────────────────────────────
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

