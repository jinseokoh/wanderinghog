<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Styles -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.css" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
</head>

<body>
<div id="app">
    @include ('layouts.nav')
    <div class="container-fluid" style="position: relative">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/dashboard')) ? 'active' : '' }}" href="/admin/dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                대쉬보드
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/questions')) ? 'active' : '' }}" href="/admin/questions">
                                <i class="fas fa-question"></i>
                                가입질문
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/cards')) ? 'active' : '' }}" href="/admin/cards">
                                <i class="fas fa-address-card"></i>
                                신원인증
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/users')) ? 'active' : '' }}" href="/admin/users">
                                <i class="fas fa-user-circle"></i>
                                회원리스트
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/rooms')) ? 'active' : '' }}" href="/admin/rooms">
                                <i class="fas fa-glass-cheers"></i>
                                모임리스트
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/experiences')) ? 'active' : '' }}" href="/admin/experiences">
                                <i class="fas fa-images"></i>
                                경험리스트
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/venues')) ? 'active' : '' }}" href="/admin/venues">
                                <i class="fas fa-mountain"></i>
                                장소리스트
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/invoices')) ? 'active' : '' }}" href="/admin/invoices">
                                <i class="fas fa-credit-card"></i>
                                결제내역
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/flags')) ? 'active' : '' }}" href="/admin/flags">
                                <i class="fas fa-skull-crossbones"></i>
                                신고내역
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                @yield('content')
            </main>
        </div>
    </div>
    <flash message="{{ session('flash') }}"></flash>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js" crossorigin="anonymous"></script>
<script>
    window.App = {!! json_encode([
                'user' => Auth::user(),
                'signedIn' => Auth::check()
            ]) !!};
</script>
<script src="{{ asset('js/app.js') }}"></script>

@yield('scripts')
</body>
</html>
