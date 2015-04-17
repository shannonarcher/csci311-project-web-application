<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');
Route::post('/login', 'WelcomeController@login');
Route::get('/logout', 'WelcomeController@logout');

Route::get('/dashboard', 'DashboardController@index');

Route::get('/users', 'UserController@index');
Route::get('/users/{id}/profile', 'UserController@profile');

Route::get('/projects', 'ProjectController@index');
Route::get('/projects/{id}/dashboard', 'ProjectController@dashboard');
Route::get('/projects/{id}/tasks', 'ProjectController@tasks');
Route::get('/projects/{id}/tasks/{t_id}', 'ProjectController@task');