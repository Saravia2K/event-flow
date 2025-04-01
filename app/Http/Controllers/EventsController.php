<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventsController extends Controller
{
    function showOrganizerEventsPage()
    {
        $events = Event::where([
            "created_by" => Auth::id()
        ])->get();
        return view("organizer.events.index", [
            'events' => $events
        ]);
    }

    function showOrganizerCreateEventPage()
    {
        return view("organizer.events.form");
    }

    function create(Request $request)
    {
        $validated_data = $request->validate([
            'title' => ['string', 'required', 'max:255'],
            'description' => ['string', 'required'],
            'start_date' => ['date', 'required', "after:today"],
            'end_date' => ['date', 'required', 'after:start_date'],
            'status' => ['string', 'required', Rule::in(["active", "inactive", "finished"])]
        ]);
        $validated_data['created_by'] = Auth::id();

        Event::create($validated_data);

        return to_route("organizer.events");
    }

    function delete(Request $request)
    {
        $validated = $request->validate([
            'id' => 'numeric'
        ]);

        $event = Event::findOrFail($validated["id"]);
        $event->delete();

        return to_route("organizer.events");
    }
}
