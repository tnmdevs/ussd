<?php

Route::group(['namespace' => 'TNM\USSD\Http', 'prefix' => 'ussd'], function () {
    Route::post('/', ['uses' => 'Controller']);
});
