<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // ახალი home მეთოდი - დაამატე აქ
    public function home()
    {
        $featured_events = Event::with(['organizer', 'categories'])
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->where('is_featured', true) // თუ გაქვს is_featured ველი
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();

        // თუ is_featured ველი არ გაქვს, გამოიყენე ეს:
        // $featured_events = Event::with(['organizer', 'categories'])
        //     ->where('status', 'published')
        //     ->where('start_date', '>', now())
        //     ->latest('created_at')
        //     ->take(6)
        //     ->get();

        return view('welcome', compact('featured_events'));
    }

    public function index(Request $request)
    {
        $query = Event::with(['organizer', 'categories'])
            ->where('status', 'published');

        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query->latest('start_date')->paginate(12);
        $categories = Category::withCount('events')->get();

        return view('events.index', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'published' && (!auth()->check() || auth()->id() !== $event->user_id)) {
            abort(404);
        }

        $event->load(['organizer', 'categories']);
        $related_events = Event::where('status', 'published')
            ->where('id', '!=', $event->id)
            ->whereHas('categories', function($q) use ($event) {
                $q->whereIn('categories.id', $event->categories->pluck('id'));
            })
            ->take(4)
            ->get();

        return view('events.show', compact('event', 'related_events'));
    }
}