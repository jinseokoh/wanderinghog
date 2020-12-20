<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    // SES bounce 지원 및 cloudfront 는 us-east-1 사용
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_SNS_REGION', 'us-east-1'),
    ],

    // 서울 리젼 SMS 발송기능 없음
    'sns' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_SNS_REGION', 'us-east-1'),
        'version' => 'latest',
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('CLIENT_URL').env('FACEBOOK_REDIRECT'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('CLIENT_URL').env('GOOGLE_REDIRECT'),
    ],

    'naver' => [
        'client_id' => env('NAVER_CLIENT_ID'),
        'client_secret' => env('NAVER_CLIENT_SECRET'),
        'redirect' => env('CLIENT_URL').env('NAVER_REDIRECT'),
    ],

    'kakao' => [
        'client_id' => env('KAKAO_CLIENT_ID'),
        'client_secret' => env('KAKAO_CLIENT_SECRET'),
        'redirect' => env('CLIENT_URL').env('KAKAO_REDIRECT'),
    ],

    'google_api' => [
        'key' => env('GOOGLE_API_KEY'),
    ],

    'google_cloud' => [
        'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
        'credentials' => env('GOOGLE_CLOUD_CREDENTIALS_PATH'),
    ],

    'aligo' => [
        'app_id' => env('ALIGO_APP_ID'),
        'app_key' => env('ALIGO_APP_KEY'),
        'sms_from' => env('ALIGO_REGISTERED_PHONE'),
        'kakao_key' => env('ALIGO_KAKAO_CHANNEL_KEY'),
    ],

];
