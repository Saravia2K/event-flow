<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Notification;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Obtener eventos del usuario categorizados
        $participations = $user->participations()
            ->with(['event'])
            ->get()
            ->groupBy('participation_status');

        return view('participants.profile', [
            'user' => $user,
            'pendingEvents' => $participations->get('pending', collect()),
            'confirmedEvents' => $participations->get('confirmed', collect()),
            'rejectedEvents' => $participations->get('rejected', collect())
        ]);
    }

    public function requests()
    {
        $pendingParticipants = Participant::with(['user', 'event'])
            ->where('participation_status', 'pending')
            ->whereRelation("event", "created_by", Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $processedParticipants = Participant::with(['user', 'event'])
            ->whereRelation("event", "created_by", Auth::id())
            ->whereIn('participation_status', ['confirmed', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view("organizer.requests.index", compact('pendingParticipants', 'processedParticipants'));
    }

    public function updateStatus(Request $request, Participant $participation)
    {
        $request->validate([
            'status' => 'required|in:confirmed,rejected,pending'
        ]);

        $participation->update([
            'participation_status' => $request->status,
            'processed_by' => Auth::id()
        ]);

        // Crear notificación para el usuario
        $this->createNotification(
            $participation->user_id,
            $participation->event_id,
            $request->status
        );

        return back()->with('success', 'Estado actualizado correctamente');
    }

    protected function createNotification($userId, $eventId, $status)
    {
        $statusMessages = [
            'confirmed' => '¡Felicidades! Tu solicitud para el evento ha sido aprobada.',
            'rejected' => 'Lamentamos informarte que tu solicitud para el evento ha sido rechazada.',
            'pending' => 'Tu solicitud para el evento está siendo revisada.'
        ];

        Notification::create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'title' => 'Actualización de participación',
            'message' => $statusMessages[$status],
            'is_read' => 0
        ]);
    }

    public function cancelParticipation(Event $event)
    {
        $participation = Participant::where("event_id", $event->id)
            ->where("user_id", Auth::id());

        $participation->delete();

        return back();
    }
}
