<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function () {
        Route::get('sign-in', 'showLoginForm')->name('login');
        Route::post('sign-in', 'login');
        Route::get('logout', 'logout')->middleware('auth')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('sign-up', 'showRegistrationForm')->name('register');
        Route::post('sign-up', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });

    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::middleware('auth')->name('user.')->group(function () {
    //authorization
    Route::namespace('User')->controller('AuthorizationController')->group(function () {
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('go2fa.verify');
    });

    Route::middleware(['check.status'])->group(function () {

        Route::get('name-check', 'User\UserController@pageNameCheck')->name('page.name.check');
        Route::get('user-profile-data', 'User\UserController@userData')->name('data');
        Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');
        Route::post('user-page-update', 'User\UserController@userPageUpdate')->name('page.update');
        Route::post('profile-cover-image', 'User\UserController@profileCoverImage')->name('profile.cover.image');

        Route::middleware('registration.complete')->namespace('User')->group(function () {

            Route::controller('UserController')->group(function () {
                Route::get('dashboard', 'home')->name('home');

                Route::get('get/earnings', 'getEarnings')->name('get.earnings');
                Route::get('get-statistics', 'getStatistics')->name('get.statistics');

                //post-react-manage//
                Route::post('post/like', 'getPostLike')->name('post.like');
                Route::post('post/comment/{postID}', 'storeComment')->name('post.comment');
                Route::post('post/comment/delete/{commentID}', 'deleteComment')->name('post.comment.delete');

                //gallery-photo-react-manage//
                Route::post('gallery/like', 'getGalleryLike')->name('gallery.like');
                Route::post('gallery/comment/{galleryID}', 'storeComment')->name('gallery.comment');
                Route::post('gallery/comment/delete/{commentID}', 'deleteComment')->name('gallery.comment.delete');

                //membership-enable
                Route::get('membership', 'membership')->name('membership');
                Route::post('download/{id}', 'gallaryImageDownload')->name('gallery.download.image');


                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form', 'kycForm')->name('kyc.form');
                Route::get('kyc-data', 'kycData')->name('kyc.data');
                Route::post('kyc-submit', 'kycSubmit')->name('kyc.submit');

                //Report
                Route::any('payment/history', 'paymentHistory')->name('payment.history');

                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions', 'transactions')->name('transactions');

                Route::get('attachment-download/{fil_hash}', 'attachmentDownload')->name('attachment.download');

                Route::get('account-setting', 'accountSetting')->name('account.setting');
                Route::post('account-setting/store', 'accountSettingStore')->name('account.setting.store');
                Route::post('account-deactivate', 'accountDeactivate')->name('account.deactivate');
            });

            //Profile setting
            Route::controller('ProfileController')->group(function () {
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
            });

            //Donation setting
            Route::controller('ManageDonationController')->prefix('donation')->name('donation.')->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('setting', 'setting')->name('setting');
                Route::post('setting/store', 'store')->name('setting.store');
            });

            //Post Manage
            Route::controller('ManagePostController')->prefix('post')->name('post.')->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('store/{id?}', 'store')->name('store');
                Route::post('delete/{id}', 'delete')->name('delete');
                Route::get('pin-unpinned/{id}', 'changePinStatus')->name('pin.unpinned');
            });

            //Gallery Manage
            Route::controller('ManageGalleryController')->prefix('gallery')->name('gallery.')->group(function () {
                Route::get('index', 'index')->name('index');
                Route::post('store/{id?}', 'store')->name('store');
                Route::post('delete/{id}', 'delete')->name('delete');
                Route::get('pin-unpinned/{id}', 'changePinStatus')->name('pin.unpinned');
                Route::post('status/{id}', 'changedStatus')->name('status');
            });

            //Manage Membership
            Route::controller('ManageMembershipController')->prefix('membership')->name('membership.')->group(function () {
                Route::get('index', 'index')->name('index');
                Route::get('level', 'level')->name('level');
                Route::get('new-level', 'newLevel')->name('new.level');
                Route::get('edit-level/{id}', 'editLevel')->name('edit.level');
                Route::post('level/store/{id?}', 'levelStore')->name('level.store');
                Route::get('setting', 'setting')->name('setting');
                Route::post('setting/update/{id}', 'settingUpdate')->name('setting.update');
                Route::post('status', 'membershipStatus')->name('status');
                Route::get('mine', 'myMembership')->name('my.membership');
                Route::post('activation/status/{id}', 'status')->name('activation.status');
                Route::post('enable', 'isEnable')->name('enable');
                Route::post('level/status/{id}', 'membershipLevelStatus')->name('level.status');
            });

            Route::controller('ExploreCreatorController')->name('explore.')->prefix('explore')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('followers', 'followers')->name('followers');
                Route::get('following', 'following')->name('following');
                Route::post('toggle/follow', 'toggleFollow')->name('toggle.follow');
                Route::get('all/creators', 'creators')->name('creators');
            });

            //Set Goal
            Route::controller('GoalSettingController')->prefix('goal')->name('goal.')->group(function () {
                Route::get('index', 'index')->name('index');
                Route::post('store/{id?}', 'store')->name('store');
                Route::post('status/{id}/{flag}', 'statusCancelEnable')->name('status');
                Route::post('complete/{id}', 'complete')->name('complete');
                Route::get('gift/history', 'giftLog')->name('gift.history');
            });

            // Withdraw
            Route::controller('WithdrawController')->prefix('payout')->name('withdraw')->group(function () {
                Route::middleware('kyc')->group(function () {
                    Route::get('/', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                });
                Route::get('history', 'withdrawLog')->name('.history');
            });
        });

        // Payment
        Route::middleware('registration.complete')->prefix('deposit')->name('deposit.')->controller('Gateway\PaymentController')->group(function () {
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
        });
    });
});
