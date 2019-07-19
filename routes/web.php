<?php
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.index', 'uses' => 'ProfileController@index']);
	Route::get('profile/gantiPassword', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});


Route::group([
	'middleware' => ['auth', 'supervisor']
], function () {
    Route::get('activity/any-data', 'ActivityController@anyData');
    Route::get('activity/request-autocomplete-activity', 'ActivityController@autocomplete_activity')->name('activity.autocomplete.activity');
    Route::get('activity/request-autocomplete-sub-activity', 'ActivityController@autocomplete_sub_activity')->name('activity.autocomplete.subactivity');
    Route::get('activity/request-autocomplete-satuan', 'ActivityController@autocomplete_satuan')->name('activity.autocomplete.satuan');
    Route::resource('activity', 'ActivityController');

    Route::resource('assignment', 'AssignmentController');
});

Route::group([
    'middleware' => ['auth', 'admin'],
], function () {
    Route::get('user/request-autocomplete-jabatan', 'UserController@autocomplete_jabatan')->name('user.autocomplete.jabatan');
    Route::resource('user', 'UserController');
});

Route::group([
    'middleware' => ['auth', 'user'],
], function () {
    Route::resource('myactivity', 'MyActivityController');
});
