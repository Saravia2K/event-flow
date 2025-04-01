<?php

use App\Http\Controllers\EventsController;
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

        Route::prefix("eventos")->group(function () {
            #region Posts
            Route::post("/", [EventsController::class, "create"])
                ->name("organizer.events.create");
            #endregion

            #region Gets
            Route::get("/", [EventsController::class, "showOrganizerEventsPage"])
                ->name("organizer.events");

            Route::get("/agregar", [EventsController::class, "showOrganizerCreateForm"])
                ->name("organizer.events.create-form");

            Route::get("/editar/{id}", [EventsController::class, "showOrganizerEditForm"])
                ->name("organizer.events.edit-form")
                ->whereNumber("id");
            #endregion

            #region Patch/Puts
            Route::patch("/", [EventsController::class, "update"])
                ->name("organizer.events.update");
            #endregion

            #region Deletes
            Route::delete('/delete', [EventsController::class, "delete"])
                ->name("organizer.events.delete");
            #endregion
        });
    });

    /**
     * Participants routes
     */
    Route::get("/", [EventsController::class, "catalog"])->name("index");
});