<?php

use App\Jobs\FetchArticles;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new FetchArticles)->hourly();
Schedule::command('email:daily-summary')->dailyAt('07:00');
