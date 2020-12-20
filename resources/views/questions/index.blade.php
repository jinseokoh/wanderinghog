@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>가입질문</h3>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::daily()->value || empty(request()->query('slug'))) btn-dark @else btn-outline-dark @endif" href="/admin/questions?slug=daily">일상</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::memory()->value) btn-dark @else btn-outline-dark @endif" href="/admin/questions?slug=memory">추억</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::food()->value) btn-dark @else btn-outline-dark @endif" href="/admin/questions?slug=food">음식</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::tmi()->value) btn-dark @else btn-outline-dark @endif" href="/admin/questions?slug=tmi">TMI 대잔치</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::value()->value) btn-dark @else btn-outline-dark @endif" href="/admin/questions?slug=value">가치관</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::charm()->value) btn-dark @else btn-outline-dark @endif" href="/admin/questions?slug=charm">매력·능력</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::social()->value) btn-dark @else btn-outline-dark @endif" href="/admin/questions?slug=social">소셜·데이팅</a>
                    </div>
                    <div>
                        <a class="btn btn-sm btn-primary" href="/admin/questions/create?{{ request()->getQueryString() }}">생성</a>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">분류</th>
                        <th scope="col">질문</th>
                        <th scope="col">사용빈도</th>
                        <th scope="col">메뉴</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($questions as $question)
                        <tr>
                            <th scope="row"><a href="/admin{{ $question->path() }}?{{ request()->getQueryString() }}">{{ $question->id }}</a></th>
                            <td>
                                <div> {{ $question->parent->name }}</div>
                            </td>
                            <td>
                                <div>{{ $question->name }}</div>
                            </td>
                            <td>
                                <div>{{ $question->answers_count }}</div>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="/admin/questions/{{ $question->id }}/edit?{{ request()->getQueryString() }}">수정</a>
                                <a class="btn btn-sm btn-danger disabled" href="">삭제</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th scope="row"></th>
                            <td colspan="5">
                                아이템이 없습니다.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div>
                    {{ $questions->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
