<?php

use App\Http\Controllers\Api\Admin\UrlShortnerController as AdminUrlShortnerController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\SuperAdmin\CompanyController;
use App\Http\Controllers\Api\SuperAdmin\UrlShortnerController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user', [AuthController::class, 'user']);
});

// Invitaiton Code
Route::post('/invitation/{code}/register', [InvitationController::class, 'register']);
Route::post('/invitation/{code}', [InvitationController::class, 'invitation']);

// Superadmin Routes
Route::prefix('superadmin')->middleware('auth:sanctum')->group(function () {
    // Clients
    Route::post('/clients/invite', [CompanyController::class, 'invite']);
    Route::post('/clients', [CompanyController::class, 'index']);

    // Short Urls
    Route::post('/urls/download', [UrlShortnerController::class, 'download']);
    Route::post('/urls', [UrlShortnerController::class, 'index']);
});

// Admin Routes
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    // Team Members
    Route::post('/team-members/invite', [UserController::class, 'invite']);
    Route::post('/team-members', [UserController::class, 'index']);

    // Short Urls
    Route::post('/urls/generate', [AdminUrlShortnerController::class, 'generate']);
    Route::post('/urls/download', [AdminUrlShortnerController::class, 'download']);
    Route::post('/urls', [AdminUrlShortnerController::class, 'index']);
});
