@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Quotes</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="mx-4 mt-2 caption">접속중: <strong>{{ Auth::user()->name }}</strong></div>
                        You are logged in!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
