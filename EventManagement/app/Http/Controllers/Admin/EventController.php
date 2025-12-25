<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['organizer', 'categories'])
            ->latest()
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(EventRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['available_seats'] = $data['total_seats'];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);
        $event->categories()->attach($request->categories);

        return redirect()->route('admin.events.index')
            ->with('success', 'ღონისძიება წარმატებით დაემატა');
    }

    public function show(Event $event)
    {
        $event->load(['organizer', 'categories', 'bookings.user']);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(EventRequest $request, Event $event)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);
        $event->categories()->sync($request->categories);

        return redirect()->route('admin.events.index')
            ->with('success', 'ღონისძიება წარმატებით განახლდა');
    }

    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'ღონისძიება წარმატებით წაიშალა');
    }
}