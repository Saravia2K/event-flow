<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Obtener eventos del usuario categorizados
        $participations = $user->participations()
            ->with([
                'event' => function ($query) {
                    $query->withCount('participants', 'comments');
                }
            ])
            ->get()
            ->groupBy('participation_status');

        return view('participants.profile', [
            'user' => $user,
            'pendingEvents' => $participations->get('pending', collect()),
            'confirmedEvents' => $participations->get('confirmed', collect()),
            'rejectedEvents' => $participations->get('rejected', collect())
        ]);
    }
}
