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

Route::get("/",'Controller@accueil');
Route::get('/contact', 'Controller@contact'); 

Route::get("/goquizz/{id}",'QuizzController@show');
Route::get("/prequizz.php","QuizzController@redirectquizz");
Route::get("/admin.php","QuizzController@redirectadminquizz");
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get("/quizz/{id}/addquestion",'QuizzController@addquestion');
Route::get("/createimagewithword",'QuizzController@createimagewithword');
Route::post("/quizz/{id}/update_questions",'QuizzController@update_questions')->name("update_questions");
Route::post("/quizz/{id}/question/{num}",'QuizzController@show_question');
Route::get("/quizz/{id}/question/{num}",'QuizzController@show_question');
Route::get("/quizz/{id}/valide/{num_question}/{num_reponse}",'QuizzController@valide');
Route::resource('quizz', 'QuizzController');

