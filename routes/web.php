<?php

use App\Http\Controllers\BogController;
use App\Http\Controllers\BogPaymentCallbackController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/bog/payment', [BogController::class, 'createOrder'])->name('pay');

Route::post('/bog/payment/card/charge', [BogController::class, 'chargeSavedCard'])->name('charge');

Route::post('/bog/payment/subscription', [BogController::class, 'subscription'])->name('subscription');

Route::post('/bog/payment/subscription/charge', [BogController::class, 'chargeSubscription'])->name('chargeSubscription');

Route::post('/bog/payment/refund', [BogController::class, 'refund'])->name('refund');

Route::post('/bog/payment/callback', BogPaymentCallbackController::class)->withoutMiddleware(VerifyCsrfToken::class);

Route::get('/bog/payment/processing/{transaction_id}', function () {
    echo 'Order is processing. Please check your orders page.';
})->name('payment.processing');

Route::get('/bog/payment/success', function () {
    echo 'success';
})->name('payment.success');

Route::get('/bog/payment/fail', function () {
    echo 'fail';
})->name('payment.fail');

// Route::get('/', function () {
//     return base64_encode(env('BOG_CLIENT_ID').':'.env('BOG_SECRET_KEY'));
// });
