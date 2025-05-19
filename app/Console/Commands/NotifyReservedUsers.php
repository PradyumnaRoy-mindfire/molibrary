<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Book;
use App\Models\Borrow;
use App\Notifications\BookAvailableNotification;
use Illuminate\Console\Scheduling\Schedule;

class NotifyReservedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-reserved-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 
        $reservedBorrows = Borrow::where('type', 'reserve')
            ->where('notified', false)
            ->with(['book', 'user'])
            ->get();

        foreach ($reservedBorrows as $borrow) {
            if ($borrow->book && $borrow->book->total_copies > 0) {
                // Sending mail notification with borrow_id
                $borrow->notified = true;
                $borrow->save();
                $borrow->user->notify(new BookAvailableNotification($borrow));
            }
        }

        $this->info('Reserved users notified successfully!');

        return 0;
    }
}
