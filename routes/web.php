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

Route::get('/map', 'HomeController@map');

Route::get('auth/facebook', 'Auth\FacebookController@redirectToFacebook')->middleware('guest');
Route::get('auth/facebook/callback', 'Auth\FacebookController@handleCallback')->middleware('guest');

Route::post('/events/{event}/attend', 'EventAttendingController@store');
Route::resource('events', 'EventsController');
Route::resource('places', 'PlacesController');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
