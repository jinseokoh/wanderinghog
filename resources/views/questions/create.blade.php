@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>가입질문 생성</h3>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>상품을 입력하는 관리자에게 고객문의 이메일과 슬랙메시지가 동시에 전달됩니다.</div>
                    <div>
                        <a class="btn btn-sm btn-outline-dark" href="/admin/questions?{{ request()->getQueryString() }}">리스트</a>
                    </div>
                </div>
                <form id="model" method="POST" action="/admin/questions">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header">가입질문</div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="slug" class="text-secondary">분류</label>
                                <select id="slug" name="slug" class="custom-select d-block w-100" required>
                                    <option value="{{ \App\Enums\QuestionEnum::daily() }}" {{ request()->query('slug') == \App\Enums\QuestionEnum::daily() || old('slug') == \App\Enums\QuestionEnum::daily() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::daily()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::memory() }}" {{ request()->query('slug') == \App\Enums\QuestionEnum::memory() || old('slug') == \App\Enums\QuestionEnum::memory() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::memory()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::food() }}" {{ request()->query('slug') == \App\Enums\QuestionEnum::food() || old('slug') == \App\Enums\QuestionEnum::food() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::food()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::tmi() }}" {{ request()->query('slug') == \App\Enums\QuestionEnum::tmi() || old('slug') == \App\Enums\QuestionEnum::tmi() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::tmi()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::value() }}" {{ request()->query('slug') == \App\Enums\QuestionEnum::value() || old('slug') == \App\Enums\QuestionEnum::value() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::value()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::charm() }}" {{ request()->query('slug') == \App\Enums\QuestionEnum::charm() || old('slug') == \App\Enums\QuestionEnum::charm() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::charm()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::social() }}" {{ request()->query('slug') == \App\Enums\QuestionEnum::social() || old('slug') == \App\Enums\QuestionEnum::social() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::social()->value) }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name" class="text-secondary">새로운 질문</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="새로운 질문">
                            </div>

                            <hr class="mb-3">

                            @if (count($errors))
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <button class="btn btn-primary btn-lg btn-block" type="submit">생성</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
