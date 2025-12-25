<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;

class GenerateEventReport extends Command
{
    protected $signature = 'events:generate-report {event_id?}';
    protected $description = 'ღონისძიების ანგარიშის გენერირება';

    public function handle()
    {
        $eventId = $this->argument('event_id');

        if (!$eventId) {
            $eventId = $this->ask('შეიყვანეთ ღონისძიების ID');
        }

        $event = Event::with(['bookings.participants', 'bookings.user'])->find($eventId);

        if (!$event) {
            $this->error('ღონისძიება ვერ მოიძებნა!');
            return Command::FAILURE;
        }

        $this->info("ანგარიში ღონისძიებისთვის: {$event->title}");
        $this->newLine();

        // სტატისტიკა
        $totalBookings = $event->bookings->count();
        $totalParticipants = $event->bookings->sum('quantity');
        $totalRevenue = $event->bookings->where('status', 'confirmed')->sum('total_price');
        $checkedIn = $event->bookings->flatMap->participants->where('checked_in', true)->count();

        $this->table(
            ['მაჩვენებელი', 'მნიშვნელობა'],
            [
                ['სულ დაჯავშნები', $totalBookings],
                ['სულ მონაწილეები', $totalParticipants],
                ['შემოსული', $checkedIn],
                ['შემოსავალი', number_format($totalRevenue, 2) . ' ₾'],
                ['დარჩენილი ადგილები', $event->available_seats],
            ]
        );

        // შევქმნათ CSV ფაილი
        if ($this->confirm('გსურთ CSV ფაილის შექმნა?')) {
            $filename = 'reports/event_' . $event->id . '_' . date('Y-m-d_H-i-s') . '.csv';
            
            $csv = "სახელი,გვარი,ელ.ფოსტა,ტელეფონი,დაჯავშნის ნომერი,შემოსული,შემოსვლის დრო\n";
            
            foreach ($event->bookings as $booking) {
                foreach ($booking->participants as $participant) {
                    $csv .= sprintf(
                        "%s,%s,%s,%s,%s,%s,%s\n",
                        $participant->first_name,
                        $participant->last_name,
                        $participant->email,
                        $participant->phone ?? '',
                        $booking->booking_number,
                        $participant->checked_in ? 'კი' : 'არა',
                        $participant->checked_in_at ?? ''
                    );
                }
            }

            Storage::put($filename, $csv);
            $this->info("ანგარიში შენახულია: storage/app/{$filename}");
        }

        return Command::SUCCESS;
    }
}