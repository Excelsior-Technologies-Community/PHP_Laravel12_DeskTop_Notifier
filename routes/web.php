<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notify', function (Request $request) {

    Artisan::call('notify:desktop', [
        'title' => $request->input('title', 'Laravel Desktop Notifier'),
        'message' => $request->input(
            'message',
            'Notification Triggered From Browser'
        ),
        '--type' => $request->input('type', 'info'),
        '--delay' => (int) $request->input('delay', 3),
    ]);

    return redirect('/')
        ->with('success', 'Desktop Notification Sent Successfully!');

})->name('notify');