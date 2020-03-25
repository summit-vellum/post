<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the ArticleServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['namespace'=>'Module\Article\Http\Controllers'], function(){
//     Route::resource('article', 'ArticleController');
// });

Route::group(['middleware' => 'web'], function() {

    Route::resource('post/article-reco', 'Vellum\Controllers\ShortcodeController');
    Route::get('post/seo-score', '\Quill\SeoScore\Http\Controllers\SeoScoreController@index');

    // Route::resource('post', 'Vellum\Controllers\ResourceController');

    // Route::group(['prefix' => 'modal'], function(){
    //     Route::name('modal.')->group(function(){
    //         Route::resource('post', 'App\Http\Controllers\ResourceController');
    //     });
    // });

});
