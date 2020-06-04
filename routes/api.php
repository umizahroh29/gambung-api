<?php

use Illuminate\Http\Request;

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

Route::post('/login', 'Api\AuthController@login')->name('login.api');

Route::prefix('/product')->group(function () {
    Route::resource('/images', 'Api\ProductImageController');
    Route::resource('/sizes', 'Api\ProductDetailController');
    Route::post('/search', 'Api\ProductController@search');
});

Route::prefix('/category')->group(function () {
    Route::resource('/status', 'Api\StatusCategoryController');
    Route::post('/search', 'Api\CategoryController@search');
});

Route::prefix('/cart')->group(function () {
    Route::post('/checkout', 'Api\CartController@checkout');
});

Route::prefix('/jicash')->group(function () {
    Route::post('/topup', 'Api\JiCashHistoryController@store');
    Route::get('/history', 'Api\JiCashHistoryController@index');
});

Route::post('/upload-proof', 'Api\TransactionController@upload_proof')->name('upload.proof');

Route::prefix('/transaction')->group(function () {
    Route::post('/cancel', 'Api\TransactionController@cancel')->name('transaction.cancel');
    Route::post('/accept', 'Api\TransactionController@accept')->name('transaction.accept');
});

Route::prefix('/voucher')->group(function () {
    Route::post('/search', 'Api\VoucherController@search')->name('voucher.search');
});

Route::resources([
    '/pesanan' => 'Api\PesananController',
    '/users' => 'Api\UsersController',
    '/category' => 'Api\CategoryController',
    '/product' => 'Api\ProductController',
    '/voucher' => 'Api\VoucherController',
    '/wishlist' => 'Api\WishlistController',
    '/cart' => 'Api\CartController',
    '/transaction' => 'Api\TransactionController',
    '/store' => 'Api\StoreController',
    '/checkout' => 'Api\CheckoutController',
    '/payment-method' => 'Api\PaymentMethodController',
    '/jicash' => 'Api\JiCashController'
]);

Route::middleware('auth:api')->group(function () {
    Route::get('/logout', 'Api\AuthController@logout')->name('logout');
    Route::get('/profile', 'Api\AuthController@profile')->name('profile.api');
    Route::get('/dashboard', 'Api\DashboardSellerController')->name('dashboard.seller.api');
    Route::post('/update-password', 'Api\AuthController@update_password')->name('profile.update.password');
    Route::post('/check-password', 'Api\AuthController@check_password')->name('profile.check.password');

    Route::prefix('/transaction')->group(function () {
        Route::post('/delivery_confirmation', 'Api\TransactionController@delivery_confirmation')->name('transaction.delivery.confirmation');
    });
});
