<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventComment;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventCommentController extends Controller
{
    public function store(Request $request, Event $event)
    {
        // Verificar si el usuario puede comentar
        $participation = Participant::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$participation || $participation->participation_status !== 'confirmed') {
            return back()->with('error', 'No tienes permiso para comentar en este evento');
        }

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        $event->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Comentario agregado correctamente');
    }

    public function destroy(EventComment $comment)
    {
        // Verificar que el usuario es el dueño del comentario o es admin
        if (Auth::id() !== $comment->user_id && !Auth::user()->is_admin) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comentario eliminado correctamente');
    }
}
