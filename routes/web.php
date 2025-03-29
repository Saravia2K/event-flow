<?php

use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\Authenticated;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

Route::get("/logout", [LoginController::class, "logout"])->name("logout");

Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    /**
     * Login
     */
    Route::get("/iniciar-sesion", [LoginController::class, "showLoginForm"])
        ->name("login-form");
    Route::post("/login", [LoginController::class, "login"])
        ->name("login");

    /**
     * Register
     */
    Route::get("/registrarse", [RegisterController::class, "showRegistrationForm"])
        ->name("register-form");
    Route::post("/register", [RegisterController::class, "register"])
        ->name("register");
});

/**
 * Authenticated routes
 */
Route::middleware([Authenticated::class])->group(function () {
    /**
     * Organizer routes
     */
    Route::prefix("organizador")->group(function () {
        Route::get("/", [OrganizerController::class, "showDashboard"])
            ->name("organizer.dashboard");
    });

    /**
     * Participants routes
     */
    Route::get("/", function () {
        return "index";
    })->name("index");
});