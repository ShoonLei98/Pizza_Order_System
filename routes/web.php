<?php

use Laravel\Jetstream\Rules\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\Environment\Runtime;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PizzaController;
use App\Http\Middleware\AdminCheckMiddleWare;
use App\Http\Middleware\UserCheckMiddleWare;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        //return view('dashboard');
        if(Auth::check()){
            if(Auth::user()->role == 'admin'){
                return redirect()->route('admin#profile');
            }
            elseif(Auth::user()->role == 'user'){
                return redirect()->route('user#index');
            }
        }
    })->name('dashboard');


});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => [AdminCheckMiddleWare::class]], function(){
    Route::get('/', 'AdminController@index')->name('admin#index');
    Route::get('profile','AdminController@profile')->name('admin#profile')->middleware(AdminCheckMiddleWare::class);
    Route::post('updateProfile/{id}', 'AdminController@updateProfile')->name('admin#updateProfile');
    Route::get('changePasswordPage', 'AdminController@changePasswordPage')->name('admin#changePasswordPage');
    Route::post('changePassword/{id}', 'Admincontroller@changePassword')->name('admin#changePassword');

    Route::get('category','CategoryController@category')->name('admin#category');
    Route::get('addCategory','CategoryController@addCategory')->name('category#addCategory');
    Route::post('createCategory','CategoryController@createCategory')->name('category#createCategory');
    Route::get('categoryDelete/{id}', 'CategoryController@deleteCategory')->name('admin#deleteCategory');
    Route::get('editCategory/{id}', 'CategoryController@editCategory')->name('admin#editCategory');
    Route::post('updateCategory', 'CategoryController@updateCategory')->name('admin#updateCategory');
    Route::get('categorySearch', 'CategoryController@searchCategory')->name('admin#searchCategory');
    Route::get('categoryItem/{id}', 'CategoryController@categoryItem')->name('admin#categoryItem');
    Route::get('category/download', 'CategoryController@categoryDownload')->name('admin#categoryDownload');

    Route::get('pizza','PizzaController@pizza')->name('admin#pizza');
    Route::get('createPizza', 'PizzaController@createPizza')->name('admin#createPizza');
    Route::post('createPizza', 'PizzaController@insertPizza')->name('admin#insertPizza');
    Route::get('deletePizza/{id}', 'PizzaController@deletePizza')->name('admin#deletePizza');
    Route::get('pizzaInfo/{id}', 'PizzaController@pizzaInfo')->name('admin#pizzaInfo');
    Route::get('editPizza/{id}', 'PizzaController@editPizza')->name('admin#editPizza');
    Route::post('editPizza/{id}', 'PizzaController@updatePizza')->name('admin#updatePizza');
    Route::get('searchPizza', 'PizzaController@searchPizza')->name('admin#searchPizza');
    Route::get('pizza/download', 'PizzaController@downloadPizza')->name('admin#downloadPizza');

    Route::get('userList', 'UserController@userList')->name('admin#userList');
    Route::get('adminList', 'UserController@adminList')->name('admin#adminList');
    Route::get('searchUser', 'UserController@searchUser')->name('admin#searchUser');
    Route::get('deleteUser/{id}', 'UserController@deleteUser')->name('admin#deleteUser');
    Route::get('searchAdmin', 'UserController@searchAdmin')->name('admin#searchAdmin');
    Route::get('admin/editAdminPage/{id}', 'UserController@editAdmin')->name('admin#editAdmin');
    Route::get('admin/updateAdmin/{id}', 'UserController@updateAdmin')->name('admin#updateAdmin');
    Route::get('deleteAdmin/{id}', 'UserController@deleteAdmin')->name('admin#deleteAdmin');

    Route::get('contact/List', 'ContactController@contactList')->name('admin#contactList');
    Route::get('contact/search', 'ContactController@searchContact')->name('admin#searchContact');

    Route::get('order/list', 'OrderController@orderList')->name('admin#orderList');
    Route::get('order/search', 'OrderController@searchOrder')->name('admin#searchOrder');
});

Route::group(['prefix' => 'user', 'middleware' => [UserCheckMiddleWare::class]], function(){
    Route::get('/', 'UserController@index')->name('user#index');

    Route::get('pizza/details/{id}', 'UserController@pizzaDetails')->name('user#pizzaDetails');

    Route::post('createContact', 'Admin\ContactController@createContact')->name('user#createContact');

    Route::get('category/search/{id}', 'UserController@categorySearch')->name('user#categorySearch');
    Route::get('search/item', 'UserController@searchItem')->name('user#searchItem');
    Route::get('search/pizzaItem', 'UserController@searchPizzaItem')->name('user#searchPizzaItem');

    Route::get('order', 'UserController@order')->name('user#order');
    Route::post('order', 'UserController@placeOrder')->name('user#placeOrder');
});
