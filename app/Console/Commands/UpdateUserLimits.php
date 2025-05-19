<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateUserLimits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-user-limits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user book limits based on active memberships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $affectedUsers = $this->getAffectedUsers();
        
        $this->info("Found {$affectedUsers->count()} users with recent membership changes.");
        
        foreach ($affectedUsers as $user) {
            $this->updateUserBookLimit($user);
        }
        
        $this->info('User book limits updated successfully.');
        
        return 0;
    }

    private function getAffectedUsers()
    {
        $oneHourAgo = Carbon::now()->subHour();
        $now = Carbon::now();
        
        // Finding the  users with memberships that ended in the last hour
        $usersWithEndedMemberships = User::whereHas('memberships', function ($query) use ($oneHourAgo, $now) {
            $query->where('end_date', '>=', $oneHourAgo)
                  ->where('end_date', '<=', $now);
        });
        
        // Finding the users with membership that started at the last hour
        $usersWithStartedMemberships = User::whereHas('memberships', function ($query) use ($oneHourAgo, $now) {
            $query->where('start_date', '>=', $oneHourAgo)
                  ->where('start_date', '<=', $now);
        });
        
        // Combining and get unique users
        return $usersWithEndedMemberships->union($usersWithStartedMemberships)->get();
    }

    private function updateUserBookLimit(User $user)
    {
        $user->refresh();
        
        // Calculating max book limit from active memberships
        $max_limit = $user->activeMemberships->max(function ($membership) {
            return $membership->plan->max_books_limit;
        }) ?? 0; 
        
        // Calculating pending borrows count
        $pending_borrow_count = $user->borrows->filter(function ($borrow) {
            return ($borrow->type === 'borrow' && ($borrow->status === 'pending' || $borrow->status === 'borrowed')) ||
                  ($borrow->type === 'return' && $borrow->status === 'pending');
        })->count();
        
        $user->book_limit = $max_limit - $pending_borrow_count;
        $user->save();
        
        $this->info("Updated user #{$user->id}: max_limit={$max_limit}, pending_borrows={$pending_borrow_count}, new_limit={$user->book_limit}");
    }
}
