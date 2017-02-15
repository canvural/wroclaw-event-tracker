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

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index');

Route::get('auth/facebook', 'Auth\FacebookController@redirectToFacebook')->middleware('guest');
Route::get('auth/facebook/callback', 'Auth\FacebookController@handleCallback')->middleware('guest');

Route::resource('event', 'EventController');

Route::get('/test', function () {
    /** @var \App\Models\Event $event */
    $events = App\Models\Event::all();
    
    foreach ($events as $event) {
        if (!isset($event->extra_info['cover'])) continue;
        
        $event->addMediaFromUrl($event->extra_info['cover']['source'])
            ->usingFileName($event->id)
            ->setFileName($event->facebook_id . '-banner')
            ->toMediaLibrary();
        
        sleep(2);
    }
});
