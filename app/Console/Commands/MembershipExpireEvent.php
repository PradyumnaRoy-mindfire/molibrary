<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\MembershipExpireNotification;
use Illuminate\Console\Command;

class MembershipExpireEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:membership-expire-event';

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
        $users = User::membershipExpiringSoon()->get();

        foreach ($users as $user) {
            $user->notify(new MembershipExpireNotification($user->membership));
        }
    }
}
