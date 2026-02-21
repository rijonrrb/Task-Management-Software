<?php

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  API ROUTES                                                  ║
 * ║  Purpose: JSON API endpoints for Vue.js AJAX calls           ║
 * ║  Learning: API routes, JSON responses, stateless auth        ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * API routes are prefixed with /api/ automatically.
 * They use the 'api' middleware group (no session/CSRF, but throttled).
 *
 * For our Vue.js task status updates, we use the web middleware
 * so we can share the same auth session.
 */

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/**
 * Task API — Used by Vue.js components for AJAX updates
 *
 * PATCH /api/tasks/{task}/status
 * Updates only the status of a task (called from TaskBoard.vue)
 *
 * We use 'web' middleware here so the auth session is available.
 * In a real SPA, you'd use Laravel Sanctum for API token auth.
 */
Route::middleware(['web', 'auth'])->group(function () {
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
         ->name('api.tasks.status');
});
