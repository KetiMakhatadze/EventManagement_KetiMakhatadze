<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function checkIn($id)
    {
        $participant = Participant::with(['booking.event'])->findOrFail($id);

        if ($participant->checked_in) {
            return view('participants.already-checked', compact('participant'));
        }

        $participant->update([
            'checked_in' => true,
            'checked_in_at' => now(),
        ]);

        return view('participants.checked-in', compact('participant'));
    }

    public function list(Request $request)
    {
        $this->middleware('organizer');

        $query = Participant::with(['booking.event', 'booking.user']);

        if ($request->has('event_id')) {
            $query->whereHas('booking', function($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        if ($request->has('checked_in')) {
            $query->where('checked_in', $request->checked_in);
        }

        $participants = $query->latest()->paginate(50);

        return view('participants.list', compact('participants'));
    }
}