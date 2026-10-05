<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Build something great.');
})->purpose('Display an inspiring quote');
