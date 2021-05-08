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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {

    Route::resources([
        'evaluation' => 'EvaluationController',
    ]);

    Route::get('groups', 'GroupController@get');
    Route::get('tasks/{group}', 'TaskController@get');
    Route::get('steps/{task}', 'StepController@get');
    Route::get('evaluations', 'EvaluationController@get');

    Route::post('evaluations/get/', 'EvaluationController@getOne');

    Route::post('export/{id}','EvaluationController@export');
    Route::post('steps/find/', 'StepController@find');
    Route::post('/step/associate/', 'StepController@associate');

    Route::delete('groups', 'GroupController@deleteMany');
    Route::delete('tasks', 'TaskController@deleteMany');
    Route::post('steps', 'StepController@deleteMany');

    Route::get('dump', 'DumpController@run')->name('dump');
    Route::get('users', 'UserController@get')->name('users.get');
    Route::post('users', 'UserController@set')->name('users.set');

    Route::post('clone', 'EvaluationController@clone')->name('eval.clone');
});

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::resource('task', 'TaskController');
    Route::post('task/find/', 'TaskController@find')->name('task.find');
    Route::get('task/find/{string}', 'TaskController@find')->name('task.find.get');

    Route::resource('group', 'GroupController');
    Route::post('group/find/', 'GroupController@find')->name('group.find');
    Route::get('group/find/{string}', 'GroupController@find')->name('group.find.get');

    Route::resource('step', 'StepController');
    Route::post('step/find/', 'StepController@find')->name('step.find');
    Route::get('step/find/{string}', 'StepController@find')->name('step.find.get');
});
