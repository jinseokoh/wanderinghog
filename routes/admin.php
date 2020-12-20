<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* views */

Route::prefix('/admin')->namespace('Admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->middleware('admin')->group(function () {
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login');
        Route::post('/logout','LoginController@logout')->name('logout');

        Route::get('/register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('/register', 'RegisterController@register');

        Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');

        // Route::get('/email/verify', 'VerificationController@show')->name('admin.verification.notice');
        // Route::get('/email/verify/{id}', 'VerificationController@verify')->name('admin.verification.verify');
        // Route::get('/email/resend', 'VerificationController@resend')->name('admin.verification.resend');
    });

    Route::namespace('Dashboard')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    });

    Route::namespace('Comments')->group(function () {
        Route::get('/comments', 'CommentController@index');
    });

    Route::namespace('Users')->group(function () {
        Route::get('/users', 'UserController@index');
        Route::get('/users/create', 'UserController@create');
        Route::get('/users/{id}', 'UserController@show');
        Route::get('/users/{id}/edit', 'UserController@edit');
    });

    Route::namespace('Cards')->group(function () {
        Route::get('/cards', 'CardController@index');
        Route::get('/cards/create', 'CardController@create');
        Route::get('/cards/{id}', 'CardController@show');
        Route::get('/cards/{id}/edit', 'CardController@edit');
        Route::post('/cards/{id}', 'CardController@store');
    });

    Route::namespace('Venues')->group(function () {
        Route::get('/venues', 'VenueController@index');
        Route::get('/venues/create', 'VenueController@create');
        Route::get('/venues/{id}', 'VenueController@show');
        Route::get('/venues/{id}/edit', 'VenueController@edit');
        Route::post('/venues/{id}', 'VenueController@store');
    });

    Route::namespace('Questions')->group(function () {
        Route::get('/questions', 'QuestionController@index');
        Route::get('/questions/create', 'QuestionController@create')->name('questions.create');
        Route::get('/questions/{id}', 'QuestionController@show')->name('questions.show');
        Route::get('/questions/{id}/edit', 'QuestionController@edit')->name('questions.edit');

        Route::post('/questions', 'QuestionController@store')->name('questions.store');
        Route::put('/questions/{id}', 'QuestionController@update')->name('questions.update');

        Route::put('/questions/{id}/deposit', 'QuestionController@deposit');
        Route::put('/questions/{id}/balance', 'QuestionController@balance');
        Route::put('/questions/{id}/complete', 'QuestionController@complete');
    });

    Route::namespace('Admins')->group(function () {
        Route::get('/admins/{id}', 'AdminController@show')->name('admins.edit');
        Route::put('/admins/{id}', 'AdminController@update')->name('admins.update');
    });
});

/* apis */

Route::prefix('/admin/api')->namespace('Admin')->group(function () {
    Route::namespace('Api')->group(function () {
        Route::namespace('Dropzone')->group(function () {
            Route::get('/dropzone', 'DropzoneController@index');
            Route::post('/dropzone', 'DropzoneController@store');
            Route::delete('/dropzone', 'DropzoneController@destroy');
        });

        Route::namespace('Admins')->group(function () {
//            Route::post('/admins/{id}/avatar', 'AvatarController@store');
//            Route::get('/admins/{id}/notifications', 'NotificationController@index');
//            Route::delete('/admins/{id}/notifications/{notification}', 'NotificationController@destroy');
        });

        Route::namespace('Comments')->group(function () {
            Route::put('/comments/{id}', 'CommentController@toggle');
        });

        Route::namespace('Products')->group(function () {
//            Route::get('/products', 'ProductController@index');
//
//            Route::get('/products/{product}/programs', 'ProgramController@index');
//            Route::get('/products/{product}/programs/{program}', 'ProgramController@show');
//            Route::post('/products/{product}/programs', 'ProgramController@store');
//            Route::put('/products/{product}/programs/{program}', 'ProgramController@update');
//            Route::delete('/products/{product}/programs/{program}', 'ProgramController@destroy');
//
//            Route::get('/products/{product}/maps', 'MapController@index');
//            Route::get('/products/{product}/maps/{map}', 'MapController@show');
//            Route::post('/products/{product}/maps', 'MapController@store');
//            Route::put('/products/{product}/maps/{map}', 'MapController@update');
//            Route::delete('/products/{product}/maps/{map}', 'MapController@destroy');
//
//            Route::get('/products/{product}/reminders', 'ReminderController@index');
//            Route::get('/products/{product}/reminders/{reminder}', 'ReminderController@show');
//            Route::post('/products/{product}/reminders', 'ReminderController@store');
//            Route::put('/products/{product}/reminders/{reminder}', 'ReminderController@update');
//            Route::delete('/products/{product}/reminders/{reminder}', 'ReminderController@destroy');
        });

        Route::namespace('Questions')->group(function () {
            // Route::put('/questions/{quote}', 'QuoteController@update');
            // Route::delete('/quotes/{quote}', 'QuoteController@delete');
        });

        Route::namespace('Programs')->group(function () {
            Route::get('/programs/{program}/items/{item}', 'ItemController@show');
            Route::post('/programs/{program}/items', 'ItemController@store');
            Route::put('/programs/{program}/items/{item}', 'ItemController@update');
            Route::delete('/programs/{program}/items/{item}', 'ItemController@destroy');
        });

        Route::namespace('Items')->group(function () {
            Route::post('/items/{id}/media', 'MediaController@store');
            Route::delete('/items/{id}/media', 'MediaController@destroy');
        });

        Route::namespace('Maps')->group(function () {
            Route::get('/maps/{map}/venues/{venue}', 'VenueController@show');
            Route::post('/maps/{map}/venues', 'VenueController@store');
            Route::put('/maps/{map}/venues/{venue}', 'VenueController@update');
            Route::delete('/maps/{map}/venues/{venue}', 'VenueController@destroy');
        });

//        Route::post('/testimonials', 'Admin\Api\TestimonialController@store');
//        Route::put('/testimonials/{id}', 'Admin\Api\TestimonialController@update');
    });
});
