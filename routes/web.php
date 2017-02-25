<?php

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

use App\Models\Event;

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index');

Route::get('auth/facebook', 'Auth\FacebookController@redirectToFacebook')->middleware('guest');
Route::get('auth/facebook/callback', 'Auth\FacebookController@handleCallback')->middleware('guest');

Route::resource('events', 'EventsController');

Route::get('/test', function () {
    /** @var Event $event */
    $event = Event::find(18);
    
    return $event->getFirstMediaUrl();
});
