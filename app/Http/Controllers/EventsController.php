<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventsController extends Controller
{
    private $formFieldsValidation = [
        'title' => ['string', 'required', 'max:255'],
        'description' => ['string', 'required'],
        'start_date' => ['date', 'required', "after:today"],
        'end_date' => ['date', 'required', 'after:start_date'],
        'status' => ['string', 'required', "in:active,inactive,finished"]
    ];

    function catalog()
    {
        $events = Event::where('status', 'active')
            ->where('start_date', '>=', now()) // Opcional: solo futuros
            ->orderBy('start_date')
            ->get();

        return view('participants.index', compact('events'));
    }

    function showOrganizerEventsPage()
    {
        $events = Event::where([
            "created_by" => Auth::id()
        ])->get();
        return view("organizer.events.index", [
            'events' => $events
        ]);
    }

    function showOrganizerCreateForm()
    {
        return view("organizer.events.form");
    }

    function showOrganizerEditForm(string $id)
    {
        try {
            $event = $event = Event::findOrFail($id);
            return view("organizer.events.form", compact("event"));
        } catch (ModelNotFoundException $e) {
            return to_route("organizer.events");
        }
    }

    function create(Request $request)
    {
        $validated_data = $request->validate($this->formFieldsValidation);
        $validated_data['created_by'] = Auth::id();

        Event::create($validated_data);

        return to_route("organizer.events");
    }

    function update(Request $request)
    {
        $validated = $request->validate(
            array_merge(['id' => 'numeric|required'], $this->formFieldsValidation)
        );

        $id = $validated["id"];
        unset($validated["id"]);
        $updated = Event::where("id", $id)->update($validated);

        return to_route("organizer.events")->with("alert", [
            'success' => $updated,
            'message' => 'Evento actualizado con éxito'
        ]);
    }

    function delete(Request $request)
    {
        $validated = $request->validate([
            'id' => 'numeric'
        ]);

        $event = Event::findOrFail($validated["id"]);
        $deleted = $event->delete();

        return to_route("organizer.events")->with("alert", [
            'success' => $deleted,
            'message' => 'Evento eliminado con éxito'
        ]);
        ;
    }
}
