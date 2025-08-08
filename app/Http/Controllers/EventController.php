<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index()
    {
        Request::validate([
            'starts_at' => ['nullable', 'date:Y-m-d'],
            'ends_at' => ['nullable', 'date:Y-m-d'],
        ]);

        return Inertia::render('Events/Index', [
            'starts_at' => Request::get('starts_at'),
            'ends_at' => Request::get('ends_at'),
            'events' => Event::isBetween(Request::get('starts_at'), Request::get('ends_at'))->orderByDate()->get()
        ]);
    }

    public function store()
    {
        // Utilisation d'une méthode privée pour réutiliser les règles de validation
        $data = Request::validate($this->validationRules());

        // Utilisation d'une méthode privée pour parser les dates
        Event::create([
            ...$data,
            'starts_at' => $this->parseDate($data['starts_at']),
            'ends_at' => $this->parseDate($data['ends_at']),
        ]);

        return Redirect::back();
    }

    public function update(Event $event)
    {
        // Même logique de validation que pour store(), mais réutilisable
        $data = Request::validate($this->validationRules());

        // Réutilisation du parsing de date
        $event->update([
            ...$data,
            'starts_at' => $this->parseDate($data['starts_at']),
            'ends_at' => $this->parseDate($data['ends_at']),
        ]);

        return Redirect::back();
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return Redirect::back();
    }

    // Ajout : méthode privée pour centraliser les règles de validation
    private function validationRules(): array
    {
        return [
            'title' => ['required', 'max:255'],
            'starts_at' => ['required', 'date:Y-m-d H:i'],
            'ends_at' => ['required', 'date:Y-m-d H:i'],
        ];
    }

    // Ajout : méthode privée pour parser une date au format Carbon
    private function parseDate(?string $date): ?Carbon
    {
        return $date ? Carbon::createFromFormat('Y-m-d H:i', $date) : null;
    }
}
