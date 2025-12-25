<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Participant;
use App\Http\Requests\BookingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Event $event)
    {
        if (!$event->isAvailable()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'სამწუხაროდ, ბილეთები გაყიდულია');
        }

        return view('bookings.create', compact('event'));
    }

    public function store(BookingRequest $request)
    {
        $event = Event::findOrFail($request->event_id);

        if ($event->available_seats < $request->quantity) {
            return back()->with('error', 'არასაკმარისი ბილეთები');
        }

        DB::beginTransaction();

        try {
            // შევქმნათ დაჯავშნა
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'event_id' => $event->id,
                'quantity' => $request->quantity,
                'total_price' => $event->price * $request->quantity,
                'status' => 'confirmed',
            ]);

            // QR კოდი დაჯავშნისთვის
            $qrContent = route('bookings.show', $booking->id);
            $qrCode = 'qr_codes/booking_' . $booking->id . '.svg';
            Storage::disk('public')->put($qrCode, QrCode::size(300)->generate($qrContent));
            $booking->update(['qr_code' => $qrCode]);

            // შევქმნათ მონაწილეები
            foreach ($request->participants as $participantData) {
                $participant = Participant::create([
                    'booking_id' => $booking->id,
                    'first_name' => $participantData['first_name'],
                    'last_name' => $participantData['last_name'],
                    'email' => $participantData['email'],
                    'phone' => $participantData['phone'] ?? null,
                    'qr_code' => 'temp_' . uniqid(),
                ]);

                // QR კოდი თითოეული მონაწილისთვის
                $participantQrContent = route('participants.checkin', $participant->id);
                $participantQr = 'qr_codes/participant_' . $participant->id . '.svg';
                Storage::disk('public')->put($participantQr, QrCode::size(300)->generate($participantQrContent));
                $participant->update(['qr_code' => $participantQr]);
            }

            // განახლდეს თავისუფალი ადგილები
            $event->decrement('available_seats', $request->quantity);

            DB::commit();

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'დაჯავშნა წარმატებით განხორციელდა!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'დაფიქსირდა შეცდომა: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $booking->load(['event', 'participants']);
        return view('bookings.show', compact('booking'));
    }

    public function myBookings()
    {
        $bookings = Booking::with('event')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.my-bookings', compact('bookings'));
    }
}