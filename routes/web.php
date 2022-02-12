<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'DashboardController@index')->name('/');
Route::get('view/{id}', 'DashboardController@view')->name('view');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//add forms
Route::get('addForms', 'FormController@create')->name('addForms');
Route::post('addForms', 'FormController@create')->name('addForms');
Route::get('viewForm/{form_id}', 'FormController@view')->name('viewForm');
Route::get('editForm/{form_id}', 'FormController@edit')->name('editForm');
Route::post('editForm', 'FormController@edit')->name('editForm');
Route::get('deleteForm/{id}', 'FormController@deleteForm')->name('deleteForm');

//add more rows
Route::get("addOption","FormController@addOptions")->name('addOption');

Route::get("optionRows","FormController@optionRows")->name('optionRows');

//add form fields
Route::get('addFormFields/{form_id}', 'FormController@fields')->name('addFormFields');
Route::post('addFormFields', 'FormController@fields')->name('addFormFields');
Route::get('getFields/{form_id}', 'FormController@fieldDetail')->name('getFields');
Route::get('deleteField/{id}', 'FormController@deleteField')->name('deleteField');
Route::get('updateList/{id}', 'FormController@updateList')->name('updateList');
Route::get('updateStatus/{id}', 'FormController@updateStatus')->name('updateStatus');
