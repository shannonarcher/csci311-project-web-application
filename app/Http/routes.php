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

Route::get('/users/add', 'UserController@add');
Route::post('/users/add', 'UserController@create');

Route::get('/users/{id}/profile', 'UserController@profile');
Route::get('/users/{id}/profile/edit', 'UserController@edit');
Route::post('/users/{id}/profile/edit', 'UserController@save');

Route::get('/projects', 'ProjectController@index');

Route::get('/projects/add', 'ProjectController@add');
Route::post('/projects/add', 'ProjectController@create');

Route::get('/projects/{id}/dashboard', 'ProjectController@dashboard');
Route::get('/projects/{id}/dashboard/edit', 'ProjectController@edit');
Route::post('/projects/{id}/dashboard/edit', 'ProjectController@save');

Route::get('/projects/{id}/tasks', 'ProjectController@tasks');
Route::get('/projects/{id}/tasks/add', 'ProjectController@addTask');
Route::post('/projects/{id}/tasks/create', 'ProjectController@createTask');

Route::get('/projects/{id}/tasks/{t_id}', 'ProjectController@task');
Route::get('/projects/{id}/tasks/{t_id}/edit', 'ProjectController@editTask');
Route::post('/projects/{id}/tasks/{t_id}/save', 'ProjectController@saveTask');

Route::get('/projects/{id}/attachUser/{u_id}', 'ProjectController@attachUser');
Route::get('/projects/{id}/detachUser/{u_id}', 'ProjectController@detachUser');

Route::get('/projects/{id}/promote/{u_id}', 'ProjectController@promoteUser');
Route::get('/projects/{id}/demote/{u_id}', 'ProjectController@demoteUser');

Route::get('/projects/{id}/gannt', 'ChartController@gannt');