<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


    //command to check if a reserved  book is available and notify the user
Schedule::command('app:notify-reserved-users')->everyTenSeconds()->appendOutputTo(storage_path('logs/schedule.log'));
Schedule::command('app:update-user-limits')->everyTenSeconds()->appendOutputTo(storage_path('logs/schedule.log'));

    //every three minutes it will check (as there is a limit of 3 minutes in the uesr model)
Schedule::command('app:membership-expire-event')->cron('*/3 * * * *')->appendOutputTo(storage_path('logs/schedule.log'));
