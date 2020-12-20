@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>가입질문 수정</h3>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div></div>
                    <div>
                        <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.questions.show', $question->id) }}">보기</a>
                        <a class="btn btn-sm btn-outline-dark" href="/admin/questions?{{ request()->getQueryString() }}">리스트</a>
                    </div>
                </div>
                <form id="model" method="POST" action="{{ route('admin.questions.update', $question->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="card mb-3">
                        <div class="card-header">가입질문</div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="slug" class="text-secondary">분류</label>
                                <select id="slug" name="slug" class="custom-select d-block w-100" required>
                                    <option value="{{ \App\Enums\QuestionEnum::daily()->label }}" {{ old('slug', $question->parent->slug) == \App\Enums\QuestionEnum::daily() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::daily()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::memory()->label }}" {{ old('slug', $question->parent->slug) == \App\Enums\QuestionEnum::memory() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::memory()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::food()->label }}" {{ old('slug', $question->parent->slug) == \App\Enums\QuestionEnum::food() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::food()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::tmi()->label }}" {{ old('slug', $question->parent->slug) == \App\Enums\QuestionEnum::tmi() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::tmi()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::value()->label }}" {{ old('slug', $question->parent->slug) == \App\Enums\QuestionEnum::value() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::value()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::charm()->label }}" {{ old('slug', $question->parent->slug) == \App\Enums\QuestionEnum::charm() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::charm()->value) }}
                                    </option>
                                    <option value="{{ \App\Enums\QuestionEnum::social()->label }}" {{ old('slug', $question->parent->slug) == \App\Enums\QuestionEnum::social() ? "selected" : "" }}>
                                        {{ __('questions.'.\App\Enums\QuestionEnum::social()->value) }}
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name" class="text-secondary">새로운 질문</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $question->name) }}" class="form-control" placeholder="새로운 질문">
                            </div>

                            <hr class="mb-3">

                            @if (count($errors))
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <button class="btn btn-dark btn-lg btn-block" type="submit">수정</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
