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

Route::get('/', ['as'=>"landingpage", function () {
    return view('welcome');
}]);
Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.index', 'uses' => 'ProfileController@index']);
	Route::get('profile/gantiPassword', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
    Route::get('about', ['as' => 'about.index', 'uses' => 'AboutController@index']);
    Route::get('report/index', ['as' => 'report.index', 'uses' => 'ReportController@index']);
    Route::get('report/show_pelaporan/{id}', ['as' => 'report.show_pelaporan', 'uses' => 'ReportController@pelaporan']);
    Route::get('report/ckpt', ['as' => 'report.print_ckpt', 'uses' => 'ReportController@print_ckp']);
    Route::get('report/ckpr', ['as' => 'report.print_ckpr', 'uses' => 'ReportController@print_ckp']);
    Route::put('report/update/{id}', 'ReportController@update_pelaporan')->name('updatey');
});


Route::group([], function () {
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
    'middleware' => ['auth', 'pegawai'],
], function () {
    Route::put('myactivity/update/{id}', 'MyActivityController@update_realisasi_keterangan')->name('update_activity_tugas_realisasi');
    Route::put('myactivity/update-my-activity/{id}', 'MyActivityController@update_my_activity')->name('update_my_activity_realisasi');
    Route::resource('myactivity', 'MyActivityController');
});
