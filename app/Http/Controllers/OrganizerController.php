<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventComment;
use App\Models\Participant;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    function showDashboard()
    {
        $total_confirmed_requests = Participant::whereRelation("event", "created_by", Auth::id())
            ->whereIn("participation_status", ["confirmed", "rejected"])
            ->count();

        $total_reports_generated = Report::where("generated_by", Auth::id())->count();

        $total_comments = EventComment::whereRelation("event", "created_by", Auth::id())
            ->count();

        $events = Event::where('created_by', Auth::id()) // Solo eventos del usuario logueado
            ->withCount([
                'participants' => function ($query) {
                    $query->where('participation_status', 'confirmed');
                }
            ])
            ->get();

        $eventStatusStats = Event::where('created_by', Auth::id())
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $usersWithEvents = User::whereRelation("participations", "participation_status", "confirmed")
            ->withCount([
                'participations' => function ($query) {
                    $query->where('participation_status', 'confirmed');
                }
            ])
            ->orderBy('participations_count', 'desc')
            ->limit(10) // Opcional: limita a los 10 usuarios más activos
            ->get();

        $participationData = Participant::where('participation_status', 'confirmed')
            ->select(['created_at'])
            ->orderBy('created_at')
            ->get();

        return view("organizer.index", compact(
            "total_confirmed_requests",
            "total_reports_generated",
            "total_comments",
            "events",
            "eventStatusStats",
            "usersWithEvents",
            "participationData"
        ));
    }
}
