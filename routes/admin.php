<?php

use Illuminate\Support\Facades\Route;







Route::namespace('Admin')->middleware('auth:admin')->group(function () {

    Route::get('/','DashboardController@Index')->name('admin.dashboard');

    // TODO :: url of Language
    Route::prefix('languages')->group(function (){
        Route::get('/','LanguageController@GetLang')->name('admin.show_lang');
        Route::get('add_lang','LanguageController@AddLang')->name('admin.add_lang');
        Route::post('store_lang','LanguageController@StoreLang')->name('admin.store_lang');
        Route::get('edit_lang/{id}','LanguageController@EditLang')->name('admin.edit_lang');
        Route::post('update_lang/{id}','LanguageController@UpdateLang')->name('admin.update_lang');
        Route::get('delete_lang/{id}','LanguageController@DeleteLang')->name('admin.delete_lang');
    });


    // TODO :: url of Categories
    Route::prefix('main_categories')->group(function (){
        Route::get('/','MainCategoryController@GetMainCategory')->name('admin.show_all_category');
        Route::get('add_category','MainCategoryController@AddCategory')->name('admin.add_category');
        Route::post('store_category','MainCategoryController@StoreCategory')->name('admin.store_category');
        Route::get('edit_category/{id}','MainCategoryController@EditCategory')->name('admin.edit_category');
        Route::post('update_category/{id}','MainCategoryController@UpdateCategory')->name('admin.update_category');
        Route::get('delete_category/{id}','MainCategoryController@DeleteCategory')->name('admin.delete_category');
        Route::get('change_active/{id}','MainCategoryController@ChangeStatusOfActive')->name('admin.change_active');

    });

    // TODO :: url of Vendors
    Route::prefix('vendors')->group(function (){
        Route::get('/','VendorController@GetVendors')->name('admin.show_all_vendors');
        Route::get('add_vendor','VendorController@AddVendor')->name('admin.add_vendor');
        Route::post('store_vendor','VendorController@StoreVendor')->name('admin.store_vendor');
        Route::get('edit_vendor/{id}','VendorController@EditVendor')->name('admin.edit_vendor');
        Route::post('update_vendor/{id}','VendorController@UpdateVendor')->name('admin.update_vendor');
        Route::get('delete_vendor/{id}','VendorController@DeleteVendor')->name('admin.delete_vendor');
        Route::get('change_active/{id}','VendorController@ChangeStatusOfActive')->name('admin.change_status_active');

    });

});



Route::namespace('Admin')->middleware('guest:admin')->group(function (){
    Route::get('login','AuthController@GetLogin')->name('get.admin.login');
    Route::post('admin_login','AuthController@Login')->name('admin.login');
});

