<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class CleanupExpiredEvents extends Command
{
    protected $signature = 'events:cleanup-expired';
    protected $description = 'გასული ღონისძიებების სტატუსის განახლება';

    public function handle()
    {
        $this->info('იწყება გასული ღონისძიებების განახლება...');

        $updated = Event::where('status', 'published')
            ->where('end_date', '<', Carbon::now())
            ->update(['status' => 'completed']);

        $this->info("განახლდა {$updated} ღონისძიება");
        
        $deleted = Event::onlyTrashed()
            ->where('deleted_at', '<', Carbon::now()->subDays(30))
            ->forceDelete();

        $this->info("სამუდამოდ წაიშალა {$deleted} ღონისძიება");

        return Command::SUCCESS;
    }
}