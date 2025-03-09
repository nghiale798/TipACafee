<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayOSController;
use App\Http\Controllers\Gateway\PaymentController;

Route::post('/create-payment', [PaymentController::class, 'createPayment']);
Route::post('/payos-webhook', [PaymentController::class, 'handleWebhook']);
Route::post('/payment/payos', [PaymentController::class, 'payos'])->name('payment.payos');

Route::post('admin/language/update-all/{id}', [LanguageController::class, 'updateAllTranslations'])
    ->name('admin.language.update.all');

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});


Route::controller('SiteController')->group(function () {

    Route::get('/get-started', 'getStarted')->name('get.started');

    Route::get('about', 'about')->name('about');
    Route::get('contact', 'contact')->name('contact');
    Route::post('contact', 'contactSubmit');
    Route::get('change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

    Route::get('cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

    Route::get('/pages/{slug}', 'pages')->name('pages');

    Route::get('/', 'index')->name('home');
});


// Payment
Route::prefix('payment')->name('payment.')->controller('Gateway\PaymentController')->group(function () {
    Route::post('/index', 'payment')->name('index');
 //  Route::get('/capture', 'capture')->name('capture');
    Route::post('insert', 'depositInsert')->name('insert');
    Route::get('confirm', 'depositConfirm')->name('confirm');
    Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
    Route::post('manual', 'manualDepositUpdate')->name('manual.update');
});

Route::controller('FrontController')->group(function () {
    Route::get('{slug}/goal/widget', 'goalWidget')->name('goal.widget');
    Route::get('load-more/comments/{postID}', 'loadMoreComments')->name('load.more.comment');
    Route::get('photo-more/comments/{postID}', 'photoMoreComments')->name('photo.more.comment');
    Route::get('filter/post', 'filterPost')->name('filter.post');

    Route::get('/{slug}', 'homePage')->name('home.page');
    Route::get('/{slug}/membership', 'membershipPage')->name('membership.page');
    Route::get('/{slug}/post', 'postPage')->name('post.page');
    Route::get('/{slug}/gallery', 'galleryPage')->name('gallery.page');
    Route::get('/{link}/{slug}', 'postView')->name('post.view');
    Route::get('/{link}/{slug}/{id}', 'galleryPhotoView')->name('gallery.view');
});


