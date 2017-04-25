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
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\UnreachableUrl;

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index');

Route::get('auth/facebook', 'Auth\FacebookController@redirectToFacebook')->middleware('guest');
Route::get('auth/facebook/callback', 'Auth\FacebookController@handleCallback')->middleware('guest');

Route::resource('events', 'EventsController');
Route::resource('places', 'PlacesController');

Route::get('/test', function () {
    \App\Models\Event::chunk(200, function ($events) {
        $events->filter(function ($event) {
            return !$event->hasMedia();
        })->filter(function ($event) {
            return !is_null($event->extra_info) && (array_key_exists('cover', $event->extra_info)
                && array_key_exists('source', $event->extra_info['cover']));
        })->each(function($event) {
            try {
                $event->addMediaFromUrl($event->extra_info['cover']['source']);
            } catch (UnreachableUrl $e) {
                echo $event->id . "<br>";
            }
        });
    });
    
});
