<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/notify', function () {

    Artisan::call('notify:desktop', [
        'title' => 'Laravel',
        'message' => 'Notification Triggered From Browser',
        '--type' => 'success',
        '--delay' => 2,
    ]);


    return redirect('/')
        ->with('success', 'Desktop Notification Sent Successfully!');
});
