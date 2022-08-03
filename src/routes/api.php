<?php

use Illuminate\Support\Facades\Route;
use TNM\USSD\Http\Controller;

Route::post('/{adapter?}', Controller::class);
