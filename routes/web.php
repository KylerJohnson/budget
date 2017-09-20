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

// Note: this route will hijack the show action on ExpenseController.
// At the moment, it doesn't seem to be an issue, as the action isn't
// used.
Route::get('expenses/{expense}/edit', 'ExpenseController@edit');
Route::get('expenses/{month}/{year}', 'ExpenseController@index');
Route::get('expenses/create/{month}/{year}', 'ExpenseController@create');

Route::get('income/create/{month}/{year}', 'IncomeController@create');

/*function($month, $year){
	return redirect()->action(
		'ExpenseController@index', ['month'=>$month, 'year'=>$year]
	);
})->where(['month' => '[0-9]+', 'year' => '[0-9]+']);
 */

Route::resource('expenses', 'ExpenseController');

Route::resource('income', 'IncomeController');

Route::resource('budget_settings', 'BudgetSettingsController');

Route::get('analytics', 'AnalyticsController@index');
