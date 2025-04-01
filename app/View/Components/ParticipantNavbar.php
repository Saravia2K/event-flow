<?php

namespace App\View\Components;

use App\Models\Notification;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ParticipantNavbar extends Component
{
    public $notifications;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Obtener notificaciones no leídas
        $this->notifications = Notification::with([
            'event',
        ])
            ->where('user_id', Auth::id())
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.participant-navbar');
    }
}
