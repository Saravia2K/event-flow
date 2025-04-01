<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventPdf;
use App\Models\Notification;
use App\Models\Participant;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Storage;

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

    public function show(Event $event)
    {
        $event->load([
            'comments.user',
            'participants.user',
        ]);

        $userParticipation = $event->participants
            ->where('user_id', Auth::id())
            ->first();

        $canComment = $userParticipation && $userParticipation->participation_status === 'confirmed';

        return view('participants.event', compact('event', 'userParticipation', 'canComment'));
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
            $event = Event::findOrFail($id);
            return view("organizer.events.form", compact("event"));
        } catch (ModelNotFoundException $e) {
            return to_route("organizer.events");
        }
    }

    function showOrganizerDetails(Event $event)
    {
        $event->load(['participants.user', 'comments.user']);

        return view('organizer.events.details', compact('event'));
    }

    public function generatePdf(Event $event)
    {
        $event->load(['participants.user']);

        $pdfGenerator = App::make("dompdf.wrapper");
        // Generar el PDF
        $pdf = $pdfGenerator->loadView('organizer.events.pdf', [
            'event' => $event,
            'date' => now()->format('d/m/Y')
        ]);

        // Guardar en storage
        $filename = 'event_report_' . $event->id . '_' . now()->format('Ymd_His') . '.pdf';
        $path = 'reports/events/' . $filename;
        Storage::put($path, $pdf->output());

        // Opcional: Guardar registro en DB
        EventPdf::create([
            'event_id' => $event->id,
            'file_path' => $path,
            'generated_by' => Auth::id()
        ]);

        return $pdf->download($filename);
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

        $this->notifyParticipantsAboutUpdate(Event::find($id)->first());

        return to_route("organizer.events")->with("alert", [
            'success' => $updated,
            'message' => 'Evento actualizado con éxito'
        ]);
    }

    protected function notifyParticipantsAboutUpdate(Event $event)
    {
        $participants = $event->participants()
            ->where('participation_status', 'confirmed')
            ->with('user')
            ->get();

        foreach ($participants as $participant) {
            Notification::create([
                'user_id' => $participant->user_id,
                'event_id' => $event->id,
                'title' => 'Evento actualizado: ' . $event->title,
                'message' => 'El evento al que estás registrado ha sido modificado. Revisa los nuevos detalles.',
                'is_read' => false
            ]);
        }
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
    }

    public function participate(Request $request, Event $event)
    {
        // Verificar si ya participa
        $existing = Participant::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($existing) {
            return back()->with('error', 'Ya has solicitado participar');
        }

        // Crear nueva participación
        Participant::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'participation_status' => 'pending' // O 'confirmed' según tu lógica
        ]);

        return back()->with('success', 'Solicitud enviada correctamente');
    }
}
