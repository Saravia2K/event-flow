<?php

use App\Http\Controllers\EventCommentController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\Authenticated;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\EventComment;
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

            Route::get("/{event}/detalles", [EventsController::class, "showOrganizerDetails"])
                ->name("organizer.events.details");

            Route::get("/{event}/reporte", [EventsController::class, "generatePdf"])
                ->name("organizer.events.report");

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

        Route::prefix("solicitudes")->group(function () {
            Route::get("/", [ParticipantController::class, "requests"])
                ->name("organizer.requests");

            Route::post('/participants/{participation}/update-status', [ParticipantController::class, 'updateStatus'])
                ->name('organizer.requests.participants.update-status');
        });

        Route::prefix("reportes")->group(function () {
            Route::get("/", [ReportController::class, "index"])
                ->name("organizer.reports");
        });
    });

    /**
     * Participants routes
     */
    Route::get("/", [EventsController::class, "catalog"])
        ->name("index");

    Route::get("/perfil", [ParticipantController::class, "show"])
        ->name("participant.profile");

    /**
     * Events routes
     */
    Route::prefix("evento")->group(function () {
        Route::get("/{event}", [EventsController::class, "show"])
            ->name("participant.event");

        Route::post("/{event}/participar", [EventsController::class, "participate"])
            ->name("participant.event.participate");

        Route::post("/{event}/comentar", [EventCommentController::class, "store"])
            ->name("participant.event.comment");
    });

    /**
     * Comments routes
     */
    Route::prefix('comentario')->group(function () {
        Route::delete("/{comment}", [EventCommentController::class, "destroy"])
            ->name("comment.destroy");
    });

    /**
     * Notifications routes
     */
    Route::prefix("notificaciones")->group(function () {
        Route::get('/{notification}/read-and-redirect', [NotificationController::class, "readAndRedirect"])
            ->name("notifications.read");
    });
});