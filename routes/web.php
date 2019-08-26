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

Route::view('/', 'index');

Route::prefix('api')->group(function () {
    Route::get('match', 'MatchController@matches')->name('matches');
    Route::get('match/{board}', 'MatchController@match')->name('match');
    Route::put('match/{board}', 'MatchController@move')->name('move');
    Route::post('match', 'MatchController@create')->name('create_match');
    Route::delete('match/{board}', 'MatchController@delete')->name('delete_match');
});