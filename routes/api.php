<?php

use App\Models\Category;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Laravel\Jetstream\Rules\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'auth:sanctum'],function(){
    Route::get('user', function(Request $request){
        return $request->user();
    });
});

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::group(['prefix' => 'category', 'namespace' => 'API', 'middleware' => 'auth:sanctum'], function(){
    Route::get('list', 'ApiController@categoryList');
    Route::post('create', 'ApiController@createCategory');
    Route::get('details/{id}', 'ApiController@details');
    Route::get('delete/{id}', 'ApiController@deleteCategory');
    Route::post('update', 'ApiController@updateCategory');
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('logout', 'AuthController@logout');
});

