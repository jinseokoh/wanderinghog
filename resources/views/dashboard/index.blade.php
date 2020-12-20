@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>대쉬보드</h3>
                </div>
                <div class="mb-3">시스템 현황</div>
                <div class="card mb-3">
                    <div class="card-header">회원수</div>
                    <div class="card-body">
                        {{ $users }} 명
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">가입질문수</div>
                    <div class="card-body">
                        {{ $questions }} 건
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
