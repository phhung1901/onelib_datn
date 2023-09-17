<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->prefix('admin')->namespace('App\Http\Controllers\Admin')->group(function (){
    Route::crud('user', 'UserCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('document', 'DocumentCrudController');
    Route::crud('download', 'DownloadCrudController');
    Route::crud('tag', 'TagCrudController');
    Route::crud('rating', 'RatingCrudController');
    Route::crud('comment', 'CommentCrudController');
    Route::crud('payment', 'PaymentCrudController');
    Route::crud('report', 'ReportCrudController');

    Route::get('ajax-user-options', ['App\Http\Controllers\Admin\DownloadCrudController', 'userOptions']);
    Route::get('comment/ajax-user-options', ['App\Http\Controllers\Admin\CommentCrudController', 'userOptions']);
    Route::get('ajax-document-options', ['App\Http\Controllers\Admin\CommentCrudController', 'documentOptions']);
    Route::get('charts/datatable-document', 'Charts\DatatableDocumentChartController@response')->name('charts.datatable-document.index');
    Route::get('charts/total-document', 'Charts\TotalDocumentChartController@response')->name('charts.total-document.index');
    Route::get('charts/payment', 'Charts\PaymentChartController@response')->name('charts.payment.index');
});

//Route::group([
//    'prefix' => config('backpack.base.route_prefix', 'admin'),
//    'middleware' =>
//        ['web', 'super_admin', 'check.admin.role'],
//    'namespace' => 'App\Http\Controllers\Admin',
//], function () { // custom admin routes
//    Route::crud('user', 'UserCrudController');
//    Route::crud('category', 'CategoryCrudController');
//    Route::crud('document', 'DocumentCrudController');
//    Route::crud('download', 'DownloadCrudController');
//    Route::crud('tag', 'TagCrudController');
//    Route::crud('rating', 'RatingCrudController');
//    Route::crud('comment', 'CommentCrudController');
//    Route::crud('payment', 'PaymentCrudController');
//    Route::crud('report', 'ReportCrudController');
//
//    Route::get('ajax-user-options', ['App\Http\Controllers\Admin\DownloadCrudController', 'userOptions']);
//    Route::get('comment/ajax-user-options', ['App\Http\Controllers\Admin\CommentCrudController', 'userOptions']);
//    Route::get('ajax-document-options', ['App\Http\Controllers\Admin\CommentCrudController', 'documentOptions']);
//    Route::get('charts/datatable-document', 'Charts\DatatableDocumentChartController@response')->name('charts.datatable-document.index');
//    Route::get('charts/total-document', 'Charts\TotalDocumentChartController@response')->name('charts.total-document.index');
//    Route::get('charts/payment', 'Charts\PaymentChartController@response')->name('charts.payment.index');
//}); // this should be the absolute last line of this file
