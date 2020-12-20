# ROCK SOLID SEVER

Built on top of <img src="https://laravel.com/img/logotype.min.svg" width="60">

<img src="http://cdn.amusetravel.com/assets/images/rock.png" width="200">

## Major features

- Model observers to sync MySQL data to Elasticsearch
- Laravel SQS queues
  - Laravel
  - Elastic
  - Dynamo
- 구글 Vision API 사용하여 학생증/명함내 대학교/전화번호 인식
- 구글 Places API 사용하여 venue 의 공개사진을 가져와서 저장
- 구글 Geocode API 사용하여 appointment 의 지역 분류
- S3/CloudFront 를 이용한 Model 별 이미지 관리
- 알리고 서비스를 사용하여 문자알림
- Pusher 를 이용한 비동기 broadcast (변경 가능)

## System requirements

- `uuid` : 한번에 하나의 디바이스에서만 사용가능하도록 하기 위한 요구사항
    - 모바일에서 첫번째 실행시 `uuid` 를 생성한다.
    - login, social login, register API 를 보낼때 그 `uuid` 를 request 페이로드에 포함한다.
    - 이후 모든 API 호출시 `auth:api` 미들웨어로 request 헤더의 `uuid` 값을 비교하여, 그 값이 변경되었는지 감지한다.

## Dependencies

- [aws/aws-sdk-php](https://github.com/aws/aws-sdk-php)
- [tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- [league/flysystem-aws-s3-v3](https://github.com/thephpleague/flysystem-aws-s3-v3)
- [league/flysystem-cached-adapter](https://github.com/thephpleague/flysystem-cached-adapter)
- [jenssegers/optimus](https://github.com/jenssegers/optimus)
- [kalnoy/nestedset](https://github.com/kalnoy/nestedset)
- [google/cloud-vision](https://github.com/googleapis/google-cloud-php-vision)
- [guzzlehttp/guzzle](https://github.com/guzzle/guzzle)
- [itsnubix/aws-sns-sms-channel](https://github.com/itsnubix/aws-sns-sms-channel)
- [laravel/slack-notification-channel](https://github.com/laravel/slack-notification-channel)
- [laravel/ui](https://github.com/laravel/ui)
- [multicaret/laravel-acquaintances](https://github.com/multicaret/laravel-acquaintances)
- [pusher/pusher-php-server](https://github.com/pusher/pusher-php-server)
- [skagarwal/google-places-api](https://github.com/SachinAgarwal1337/google-places-api)
- [spatie/enum](https://github.com/spatie/enum)
- [spatie/geocoder](https://github.com/spatie/geocoder)
- [spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary)
- [spatie/laravel-searchable](https://github.com/spatie/laravel-searchable)
- [spatie/laravel-tags](https://github.com/spatie/laravel-tags)

## Dev dependencies

- [barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper)

## Prerequisites

> 서버환경에 따라 s3 에 파일을 저장하지 않는 경우, 아래의 명령을 실행해야만 한다.

```bash
php artisan storage:link
```
> 개발 기간동안에는 DB 스키마가 자주 바뀌므로, 새로운 commit 을 받았을 때마다 아래의 명령어들을 실행이 필요하다.
```bash
composer dump-autoload
php artisan optimize
php artisan migrate:fresh
php artisan db:seed
```

## Copyright

The source code of this repo is the intellectual property of Amuse Co.
