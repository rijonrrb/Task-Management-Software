<?php

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  WEB ROUTES                                                  ║
 * ║  Purpose: Define all web (browser) routes for the app        ║
 * ║  Learning: Route groups, middleware, resource routes          ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RedisDemoController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// PUBLIC ROUTES (no login required)
// ──────────────────────────────────────────────

/**
 * Home page — redirects to dashboard if logged in, login page if not
 */
Route::get('/', function () {
    return auth()->check()
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

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// ──────────────────────────────────────────────
// AUTHENTICATED ROUTES (login required)
// ──────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::prefix('redis-demo')->name('redis.')->group(function () {
        Route::get('/', [RedisDemoController::class, 'index'])->name('demo');
        Route::get('/info', [RedisDemoController::class, 'info'])->name('info');
        Route::post('/strings', [RedisDemoController::class, 'strings'])->name('strings');
        Route::post('/cache', [RedisDemoController::class, 'cacheDemo'])->name('cache');
        Route::post('/lists', [RedisDemoController::class, 'lists'])->name('lists');
        Route::post('/hashes', [RedisDemoController::class, 'hashes'])->name('hashes');
        Route::post('/counter', [RedisDemoController::class, 'counter'])->name('counter');
        Route::post('/flush', [RedisDemoController::class, 'flush'])->name('flush');
    });
});

