<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Notificaciones diarias de garantías próximas a vencer (8:00 AM)
Schedule::command('notifications:warranty-expiring --days=30')->dailyAt('08:00');
