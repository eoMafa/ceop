<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('notificacoes:parcelas-vencidas')->dailyAt('08:00');
Schedule::command('notificacoes:estoque-baixo')->dailyAt('08:00');
Schedule::command('notificacoes:aniversarios')->dailyAt('07:00');