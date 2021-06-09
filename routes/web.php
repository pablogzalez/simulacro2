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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/usuarios', 'UserController@index')->name('users.index');

Route::get('usuarios/nuevo', 'UserController@create')->name('users.create');
Route::post('/usuarios', 'UserController@store')->name('users.store');
Route::get('/usuarios/{user}/editar', 'UserController@edit')->name('users.edit');
Route::put('/usuarios/{user}', 'UserController@update')->name('users.update');
Route::delete('/usuarios/{id}', 'UserController@destroy')->name('users.destroy');
Route::get('/usuarios/papelera', 'UserController@index')->name('users.trashed');
Route::get('usuarios/{user}', 'UserController@show')->where('user', '[0-9]+')->name('users.show');
Route::patch('/usuarios/{user}/papelera', 'UserController@trash')->name('users.trash');


Route::get('/editar-perfil/', 'ProfileController@edit');
Route::put('/editar-perfil/', 'ProfileController@update');

Route::get('/profesiones/', 'ProfessionController@index')->name('professions.index');
Route::delete('/profesiones/{profession}', 'ProfessionController@destroy')->name('professions.destroy');

Route::get('/habilidades/', 'SkillController@index')->name('skills.index');

Route::get('/saludo/{name}/{nickname?}', 'WelcomeUserController');
