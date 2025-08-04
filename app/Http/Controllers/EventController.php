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
        $data = Request::validate([
            'title' => ['required', 'max:255'],
            'starts_at' => ['required', 'date:Y-m-d H:i']
        ]);

        Event::create([
            ...$data,
            'starts_at' => Carbon::createFromFormat('Y-m-d H:i', $data['starts_at'])
        ]);

        return Redirect::back();
    }

    public function update(Event $event)
    {
        $data = Request::validate([
            'title' => ['required', 'max:255'],
            'starts_at' => ['required', 'date:Y-m-d H:i']
        ]);

        $event->update([
            ...$data,
            'starts_at' => Carbon::createFromFormat('Y-m-d H:i', $data['starts_at'])
        ]);

        return Redirect::back();
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return Redirect::back();
    }
}
