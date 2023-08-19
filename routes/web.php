<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginFacebookController;
use App\Http\Controllers\Auth\LoginGoogleController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\DocumentController;
use App\Http\Controllers\Frontend\DownloadController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Controllers\Frontend\TagController;
use App\Http\Controllers\Frontend\UploadController;
use App\Http\Controllers\Frontend\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', [DocumentController::class, 'index'])->name('document.home.index');
Route::get('category/{slug}', [CategoryController::class, 'listDocument'])->name('document.category.list');
Route::get('tag/{slug}', [TagController::class, 'listDocument'])->name('document.tag.list');


Route::prefix('auth')->group(function () {
    Route::get('login', [LoginController::class, 'getLogin'])->name('frontend.auth.getLogin');
    Route::post('login/post', [LoginController::class, 'postLogin'])->name('frontend.auth.postLogin');
    Route::get('logout', [LoginController::class, 'logout'])->name('frontend.auth.logout');

    Route::get('register', [RegisterController::class, 'getRegister'])->name('frontend.auth.getRegister');
    Route::post('register/post', [RegisterController::class, 'postRegister'])->name('frontend.auth.postRegister');

    Route::get('/password/reset', [ForgotPasswordController::class, 'showResetForm'])
        ->name('frontend.auth.password.request');
    Route::post('/password/reset', [ForgotPasswordController::class, 'sendEmail'])
        ->name('frontend.auth.password.send_mail');
    Route::get('/password/reset/changepass/{token}', [PasswordController::class, 'showResetForm'])
        ->name('frontend.auth.password.get.change_pass');
    Route::post('/password/reset/changepass', [PasswordController::class, 'reset'])
        ->name('frontend.auth.password.post.change_pass');
});


// Login Facebook
Route::get('auth/facebook', function () {
    return Socialite::driver('facebook')->redirect();
})->name('frontend.login.facebook');
Route::get('auth/facebook/callback', [LoginFacebookController::class, 'index']);
Route::get('statics/policy', function () {
    return "Chinh sach rieng tu";
});
Route::get('statics/terms-of-use', function () {
    return "Dieu khoan dich vu";
});
// Google
Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('frontend.login.google');
Route::get('auth/google/callback', [LoginGoogleController::class, 'index']);
Route::get('/document/{slug}', [DocumentController::class, 'view'])->name('document.detail');

Route::prefix('/')
    ->middleware('auth')
    ->group(function () {
        Route::get('{id}/setting', [UserController::class, 'setting'])->name('frontend_v4.users.setting');
        Route::post('{id}/setting', [UserController::class, 'UpdateSetting'])->name('frontend_v4.users.postSetting');
        Route::post('{id}/setting/password', [UserController::class, 'changePass'])->name('frontend_v4.users.postChangePass');
        Route::get('{id}/profile', [UserController::class, 'profile'])->name('frontend_v4.users.profile');

        Route::get('/document/{slug}/like', [DocumentController::class, 'like'])->name('frontend_v4.document.like');
        Route::get('/document/{slug}/dislike', [DocumentController::class, 'dislike'])->name('frontend_v4.document.dislike');

        // Payment
        Route::get('/payment/{id}-{slug}', [PaymentController::class, 'getPayment'])->name('frontend_v4.payment.get');
        Route::post('/payment/{id}-{slug}', [PaymentController::class, 'postPayment'])->name('frontend_v4.payment.post');
        Route::post('/vnpay/payment', [PaymentController::class, 'VNPayRedirectPayment'])->name('frontend_v4.postVNPay');
        Route::get('/vnpay/payment/response', [PaymentController::class, 'VNPayGetResponse'])->name('frontend_v4.getVNPay');
        Route::post('/paypal/payment', [PaymentController::class, 'PaypalRedirectPayment'])->name('frontend_v4.redirectPaypal');
        Route::get('/paypal/payment/response', [PaymentController::class, 'PaypalGetResponse'])->name('frontend_v4.responsePaypal');

        // Report
        Route::post('/document/{slug}/report', [DocumentController::class, 'report'])->name('frontend_v4.document.report');
        Route::post('/document/{slug}/comment', [DocumentController::class, 'comment'])->name('frontend_v4.document.comment');

        // Document upload
        Route::get('upload', [UploadController::class, 'getUpload'])->name('frontend_v4.users.getUpload');
        Route::post('upload', [UploadController::class, 'postUpload'])->name('frontend_v4.users.postUpload');
        Route::get('{id}/document_upload', [UserController::class, 'documentUpload'])->name('frontend_v4.users.document_upload');
        Route::get('/document_upload/{id}/update', [DocumentController::class, 'edit'])->name('frontend_v4.users.edit_document');
        Route::post('/document_upload/{id}/update', [DocumentController::class, 'update'])->name('frontend_v4.users.update_document');
        Route::get('/document_upload/{id}/delete', [DocumentController::class, 'delete'])->name('frontend_v4.users.delete_document');
    });

Route::prefix('document')->group(function () {
    // Search
    Route::get('/search/list', [DocumentController::class, 'search'])->name('frontend_v4.document.search');

    // Download
    Route::get('/download/{id}-{slug}', [DownloadController::class, 'download'])->name('frontend_v4.document.download');
    Route::post('/vnpay/payment/{id}-{slug}', [DownloadController::class, 'VNPayRedirectPayment'])->name('frontend_v4.document.postVNPay');
    Route::get('/vnpay/payment/response/{id}-{slug}', [DownloadController::class, 'VNPayGetResponse'])->name('frontend_v4.document.getVNPay');
    Route::post('/paypal/payment/{id}-{slug}', [DownloadController::class, 'PaypalRedirectPayment'])->name('frontend_v4.document.redirectPaypal');
    Route::get('/paypal/payment/response/{id}-{slug}', [DownloadController::class, 'PaypalGetResponse'])->name('frontend_v4.document.responsePaypal');
});
