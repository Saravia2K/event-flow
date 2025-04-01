<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function readAndRedirect(Notification $notification)
    {
        // Verificar que la notificación pertenece al usuario autenticado
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para acceder a esta notificación');
        }

        // Marcar como leída si no lo está
        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        // Redireccionar al evento relacionado
        if ($notification->event_id) {
            return redirect()->route('participant.event', $notification->event_id);
        }

        // Redirección alternativa si no hay evento
        return redirect()->route('index');
    }
}
