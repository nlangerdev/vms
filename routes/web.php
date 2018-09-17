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
    $Api = (object)array(
        'Reviews' => app('App\Http\Controllers\VisualGroup\TestimonialController')->testimonials(),
    );
    return view('Websites.VisualGroup.Pages.welcome');
});


// LESS Controller 
Route::get('/Resources/LESS', ['uses' =>'VisualGroup\LessController@Less']);
