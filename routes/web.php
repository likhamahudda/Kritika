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

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\UserDetailsController;
use App\Http\Controllers\Web\SignatureController;
use App\Http\Controllers\Web\AssignmentController;
use App\Http\Controllers\Web\FolderController;
use App\Http\Controllers\Web\UserTemplateController;
use App\Http\Controllers\Web\BillingController; 

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\Web\SellerController;
use App\Http\Controllers\BuyerController;


use Illuminate\Support\FacadeUserTemplateControllers\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/404', function () {

    $data['title'] = '404';
    
    return view('users.404', $data);
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('web.index');
});

Route::prefix('user/')->group(function () {
    Route::controller(UserController::class)->group(function () {
        //Route::get('', 'login')->name('user.login');
        Route::get('loginUser', 'login')->name('user.login');
        Route::get('registerUser', 'signup')->name('user.signup');
        Route::post('do_login', 'do_login')->name('user.do_login');
        Route::post('do_register', 'do_register')->name('user.do_register');
        Route::get('verify_email', 'verify_email')->name('user.verify_email');
        Route::get('verify_page', 'verify_page')->name('user.verify_page');
        Route::get('forget-password', 'forgetPassword')->name('user.forget.password');
        Route::post('send-link', 'sendLink')->name('user.sendLink');
        Route::get('reset-password/{token}/{user_type}', 'resetPassword')->name('user.password.reset'); 
        Route::post('password-update', 'updatePassword')->name('user.password.update');  

        Route::controller(UserDetailsController::class)->group(function () {
            Route::get('editprofile', 'edit_profile')->name('user.editprofile');
            Route::post('updateProfile', 'updateProfile')->name('user.updateProfile');
            Route::get('change_password', 'change_password')->name('user.change_password');
            Route::post('update_password', 'update_password')->name('user.update_password');
         
        });
    });
});

Route::group(['middleware' => 'user_auth'], function () {

    Route::prefix('user/')->group(function () {
        Route::get('', [UserController::class, 'index'])->name('user.index');
        Route::get('logout', [UserController::class, 'logout'])->name('user.logout');
        // Route::controller(UserDetailsController::class)->group(function () {
        //     Route::get('editprofile', 'edit_profile')->name('user.editprofile');
        //     Route::post('updateProfile', 'updateProfile')->name('user.updateProfile');
        //     Route::post('change_password', 'change_password')->name('user.change_password'); 
        // }); 
    });
 
  
    Route::controller(ProductsController::class)->prefix('seller')->group(function () {
        Route::get('products', 'product_list')->name('products.product_list');
        Route::post('addProduct', 'addProduct')->name('products.addProduct');
        Route::get('/product/{id}/edit', 'update')->name('product.update');
        Route::post('/delete/{id}', 'delete')->name('products.delete');
        Route::post('manage', 'manage')->name('products.manage'); 
    });

    Route::controller(BuyerController::class)->prefix('buyer')->group(function () {
        Route::get('request_for_quote', 'create_rfq')->name('buyer.create_rfq');
        Route::post('/get_product_seller/{id}', 'get_product_seller')->name('buyer.get_product_seller');
        Route::post('send_rfq_seller', 'send_rfq_seller')->name('buyer.send_rfq_seller');
        Route::get('request-quote-detail/{id}', 'request_quote_detail')->name('buyer.request_quote_detail');
        Route::get('quote_seller/{id}', 'quote_seller')->name('product.quote_seller');
        Route::post('get_message_chat', 'get_message_chat')->name('buyer.get_message_chat');

        
    });
    Route::controller(BuyerController::class)->prefix('seller')->group(function () {
        Route::get('seller-quote-detail/{id}', 'seller_quote_detail')->name('seller.seller_quote_detail');
        Route::post('seller_quote_send', 'seller_quote_send')->name('seller.seller_quote_send');
        Route::post('request_quote_filter_list', 'request_quote_filter_list')->name('seller.request_quote_filter_list');
});
 

});

 





