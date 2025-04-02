<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventComment;
use App\Models\Participant;
use App\Models\Report;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    function showDashboard()
    {
        $total_events = Event::where("created_by", Auth::id())
            ->count();

        $total_confirmed_requests = Participant::whereRelation("event", "created_by", Auth::id())
            ->whereIn("participation_status", ["confirmed", "rejected"])
            ->count();

        $total_reports_generated = Report::where("generated_by", Auth::id())->count();

        $total_comments = EventComment::whereRelation("event", "created_by", Auth::id())
            ->count();

        return view("organizer.index", compact(
            "total_events",
            "total_confirmed_requests",
            "total_reports_generated",
            "total_comments"
        ));
    }
}
