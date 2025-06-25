<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProductWishController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenAuthenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Brand-List
Route::get('brand-list',[BrandController::class,'BrandList']);
//Category-List
Route::get('category-list',[CategoryController::class,'CategoryList']);
//Policy
Route::get('policy/{type}',[PolicyController::class,'PolicyByType']);

// get Product
Route::get('products-by-category/{category_id}',[ProductController::class,'ListProductByCategory']);
Route::get('products-by-remark/{remark}',[ProductController::class,'ListProductByRemark']);
Route::get('products-by-brand/{brand_id}',[ProductController::class,'ListProductByBrand']);

//product details
Route::get('product-details/{product_id}',[ProductController::class,'ProductDetailsById']);

//search
Route::get('product-list/{search}',[ProductController::class,'ListProductBySearch']);

//slider
Route::get('product-slider-list',[ProductController::class,'ListProductSlider']);

//Review
Route::get('review-list/{product_id}',[ProductReviewController::class,'ListReviewByProduct']);
Route::post('create-review',[ProductReviewController::class,'CreateReview'])->middleware([TokenAuthenticate::class]);

//Auth
Route::get ('user-login/{userEmail}',[UserController::class,'UserLogin'])->name('login');
Route::get ('verify-login/{userEmail}/{otp}',[UserController::class,'VerifyLogin']);
Route::get('logout',[UserController::class,'UserLogout']);

//User-profile

Route::post('create-profile',action: [ProfileController::class,'CreateProfile'])->middleware([TokenAuthenticate::class]);
Route::get('get-profile',action: [ProfileController::class,'GetProfile'])->middleware([TokenAuthenticate::class]);

//wishList

Route::get('create-wishlist/{product_id}',[ProductWishController::class,'CreateWishList'])->middleware([TokenAuthenticate::class]);
Route::get('get-wishlist',[ProductWishController::class,'GetWishList'])->middleware([TokenAuthenticate::class]);
Route::get('delete-wishlist/{product_id}',[ProductWishController::class,'DeleteWishList'])->middleware([TokenAuthenticate::class]);

//Product Cart

Route::post('create-cart',[CartController::class,'CreateCart'])->middleware([TokenAuthenticate::class]);
Route::get('get-cart',[CartController::class,'GetCart'])->middleware([TokenAuthenticate::class]);
Route::get('delete-cart/{product_id}',[CartController::class,'DeleteCart'])->middleware([TokenAuthenticate::class]);

//Invoice Create

Route::post('create-invoice',[InvoiceController::class,'CreateInvoice'])->middleware([TokenAuthenticate::class]);
Route::get('get-invoice',[InvoiceController::class,'GetInvoiceList'])->middleware([TokenAuthenticate::class]);
Route::get('invoice-product-list/{invoice_id}',[InvoiceController::class,'InvoiceProductList'])->middleware([TokenAuthenticate::class]);