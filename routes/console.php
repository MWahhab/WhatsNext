<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command("archive:objectives", function () {
    $todayCarbon = \Carbon\Carbon::today();



})->purpose("Archive objectives assosciated with dates which have passed")->monthly();

Artisan::command("archive:clear", function () {
    DB::table("archived_objectives")->truncate();
})->purpose("Clear archive table after every quarter")->quarterly();
