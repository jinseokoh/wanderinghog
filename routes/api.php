<?php

use App\Http\Controllers\Categories\ProfessionController;
use App\Http\Controllers\Categories\QuestionController;
use App\Http\Controllers\Categories\RegionController;
use App\Http\Controllers\Categories\RelationController;
use App\Http\Controllers\Categories\ThemeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// add default auth and email verification routes available in \Illuminate\Routing\Router
//
// * the followings are available options for Laravel 7
//   - register
//   - reset
//   - confirm
//   - verify

Auth::routes([
    'register' => true,
    'reset' => true,
    'confirm' => false,
    'verify' => true,
]);

Route::group(['middleware' => 'auth:api'], function () {

    Route::namespace('Categories')->group(function () {
        Route::get('/categories/regions', [RegionController::class, 'index']);
        Route::get('/categories/regions/tree', [RegionController::class, 'tree']);
        Route::get('/categories/questions', [QuestionController::class, 'index']);
        Route::get('/categories/questions/slugs', [QuestionController::class, 'slugs']);
        Route::get('/categories/themes', [ThemeController::class, 'index']);
        Route::get('/categories/themes/{theme}/images', [ThemeController::class, 'show']);
        Route::get('/categories/professions', [ProfessionController::class, 'index']);
        Route::get('/categories/relations', [RelationController::class, 'index']);
    });

    Route::namespace('Users')->group(function () {

        Route::post('/users/info/basic', 'BasicInfoController@store'); // 기본정보
        Route::post('/users/info/legal', 'SsnInfoController@store'); // 실명인증
        Route::put('/users/info/legal', 'SsnInfoController@update'); // 실명인증
        Route::get('/users/info/preset', 'SsnInfoController@index'); // 실명인증

        Route::put('/users/profile', 'ProfileController@update'); //
        Route::put('/users/preference', 'PreferenceController@update'); //
        Route::post('/users/token', 'DeviceTokenController@upsert'); //

        Route::post('/users/avatars', 'MediaAvatarController@store');
        Route::post('/users/photos', 'MediaPhotoController@store');
        Route::put('/users/photos/{id}', 'MediaPhotoController@update');
        Route::post('/users/photos/{id}', 'MediaPhotoController@reorder');
        Route::delete('/users/photos/{id}', 'MediaPhotoController@destroy');
        Route::post('/users/ocr/school', 'MediaOcrController@storeSchool');
        Route::post('/users/ocr/phone', 'MediaOcrController@storePhone');

        Route::get('/users/friends', 'FriendController@index'); // 친구 리스트
        Route::get('/users/friends/pending', 'FriendController@pending'); // 친구요청 리스트
        Route::get('/users/friends/block', 'FriendController@block'); // 친구차단 리스트
        Route::get('/users/friends/bookmark', 'FriendController@bookmark'); // 친구북마크 리스트
        Route::post('/users/friends/bookmark', 'FriendController@store');
        Route::delete('/users/friends/bookmark', 'FriendController@destroy');

        Route::post('/users/friendship/send', 'FriendshipController@send');
        Route::put('/users/friendship/accept', 'FriendshipController@accept');
        Route::put('/users/friendship/deny', 'FriendshipController@deny');
        Route::delete('/users/friendship/remove', 'FriendshipController@remove');
        Route::post('/users/friendship/block', 'FriendshipController@block');
        Route::put('/users/friendship/unblock', 'FriendshipController@unblock');

        Route::post('/users/invitation', 'AppInvitationController@index'); // App 초대
        Route::get('/users/invitation/kakaos', 'KakaoController@index'); // 받은 카톡친구 초대 리스트
        Route::post('/users/invitation/kakaos', 'KakaoController@upsert'); // 카톡친구 초대 리스트 저장

        Route::get('/users/{id}/appointments/host', 'AppointmentController@host')
            ->where('id', '[0-9]+');
        Route::get('/users/{id}/appointments/guest', 'AppointmentController@guest')
            ->where('id', '[0-9]+');

        Route::post('/users/{uid}/appointments/{aid}/like', 'LikeController@favorite') // 모임 좋아요.
        ->where('id', '[0-9]+');
        Route::delete('/users/{uid}/appointments/{aid}/like', 'LikeController@unfavorite')
            ->where('id', '[0-9]+');

        Route::get('/users/questions', 'QuestionController@index'); // 가입질문 랜덤 불러오기
        Route::post('/users/answers', 'AnswerController@store'); // 가입질문 답변 생성/수정
        Route::get('/users/{id}/answers', 'AnswerController@index') // 가입질문 답변 리스트
        ->where('id', '[0-9]+');
        Route::delete('/users/answers/{aid}', 'AnswerController@destroy'); // 가입질문 답변 삭제

        Route::post('/users/{id}/flags', 'FlagController@index'); // 신고

        Route::get('/user', 'UserController@index');
        Route::get('/users/me', 'UserController@index');
        Route::get('/users/kakao', 'UserController@kakao');
        Route::get('/users/{id}', 'UserController@show')
            ->where('id', '[0-9]+');
        Route::put('/users/me', 'UserController@update');
        Route::post('/users/provider', 'ProviderController@index');

    });

    Route::namespace('Appointments')->group(function () {
        Route::post('/appointments', 'AppointmentController@store'); //
        Route::delete('/appointments/{id}', 'AppointmentController@destroy'); //
        Route::put('/appointments/{id}', 'AppointmentController@update'); //
        Route::post('/appointments/{id}/parties', 'PartyController@store'); //
        Route::delete('/appointments/{aid}/parties/{pid}', 'PartyController@destroy');
        Route::post('/appointments/{aid}/parties/{pid}', 'DecisionController@index');
        Route::post('/appointments/{id}/flags', 'FlagController@index'); // 신고
    });

    Route::namespace('Venues')->group(function () {
        Route::get('/venues', 'VenueController@index'); //
        Route::post('/venues', 'VenueController@store'); //
    });

    Route::namespace('Rooms')->group(function () {
        Route::get('/rooms', 'RoomController@index');
        Route::get('/rooms/{room}', 'RoomController@show');
        Route::post('/rooms', 'RoomController@store');

        Route::get('/rooms/{room}/messages', 'ChatController@index');
        Route::post('/rooms/broadcast', 'ChatController@broadcast')->name('broadcast');
        Route::post('/rooms/{room}/chat', 'ChatController@chat')->name('chat');
        Route::post('/rooms/{room}/presence', 'ChatController@presence')->name('presence');
    });

});

Route::group(['middleware' => 'guest:api'], function () {

    Route::namespace('Auth')->group(function () {
        Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('/password/reset', 'ResetPasswordController@reset');
    });

//    Route::get('/geo', 'Geometries\GeoController@index');
//    Route::get('/keywords', 'Keywords\KeywordController@index');

});

Route::group([], function () {

    Route::namespace('Users')->group(function () {
        Route::get('/users/check', 'StatusController@index'); // 사용자명/이메일/전번 존재 검사
    });

    // refer tymon JWT refresh issues @ https://github.com/tymondesigns/jwt-auth/issues/1863
    Route::namespace('Auth')->group(function () {
        Route::get('/refresh', 'LoginController@refresh');
        Route::post('/social/{provider}', 'SocialLoginController@index');
    });

    Route::namespace('System')->group(function () {
        Route::get('/health', 'StatusController@index');
        Route::get('/test', 'StatusController@test');

//        Route::get('/logs/{log}', 'LogController@index');
//        Route::put('/logs/{log}', 'LogController@update');
    });

    Route::namespace('Answers')->group(function () {
        Route::get('/answers', 'AnswerController@index');
        Route::get('/answers/latest', 'AnswerController@latest');
        Route::get('/answers/{id}', 'AnswerController@show');
    });

    Route::namespace('Appointments')->group(function () {
        Route::get('/appointments', 'AppointmentController@index'); //
        Route::get('/appointments/stats', 'StatController@index'); //
        Route::get('/appointments/{id}', 'AppointmentController@show'); //
        Route::get('/appointments/{id}/parties', 'PartyController@index'); //
        Route::get('/appointments/{id}/parties/{pid}', 'PartyController@show'); //
        Route::get('/appointments/{id}/parties/{pid}/bio', 'PartyController@bio'); //
    });

});
