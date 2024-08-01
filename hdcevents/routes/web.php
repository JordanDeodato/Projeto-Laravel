<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\EventController;

Route::controller(EventController::class)
    ->middleware('auth')
    ->group(function () {
        Route::get('/dashboard', 'dashboard');
        Route::get('/', 'getEvents');
        Route::get('/events/create', 'create');
        Route::post('/events', 'createNewEvent');
        Route::get('/events/edit/{event}', 'edit');
        Route::put('/events/update/{id}', 'updateEvent');
        Route::get('/events/{event}', 'show');
        Route::delete('/events/{id}', 'deleteEvent');
        Route::post('/events/join/{id}', 'joinEvent');
        Route::delete('/events/leave/{id}', 'leaveEvent');
    });

