<?php

Route::group(['namespace' => 'TNM\USSD\Http', 'prefix' => 'api/ussd'], function () {
    Route::post('/{adapter?}', ['uses' => 'Controller']);
});
