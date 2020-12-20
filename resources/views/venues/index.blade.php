@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>장소(Venue)리스트</h3>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::daily()->value || empty(request()->query('slug'))) btn-dark @else btn-outline-dark @endif" href="/admin/venues?slug=daily">일상</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::memory()->value) btn-dark @else btn-outline-dark @endif" href="/admin/venues?slug=memory">추억</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::food()->value) btn-dark @else btn-outline-dark @endif" href="/admin/venues?slug=food">음식</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::tmi()->value) btn-dark @else btn-outline-dark @endif" href="/admin/venues?slug=tmi">TMI 대잔치</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::value()->value) btn-dark @else btn-outline-dark @endif" href="/admin/venues?slug=value">가치관</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::charm()->value) btn-dark @else btn-outline-dark @endif" href="/admin/venues?slug=charm">매력·능력</a>
                        <a class="btn btn-sm @if (request()->query('slug')==\App\Enums\QuestionEnum::social()->value) btn-dark @else btn-outline-dark @endif" href="/admin/venues?slug=social">소셜·데이팅</a>
                    </div>
                    <div>
                        <a class="btn btn-sm btn-primary" href="/admin/venues/create?{{ request()->getQueryString() }}">생성</a>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">제목</th>
                        <th scope="col">구글 Photo Ref</th>
                        <th scope="col">이미지 갯수</th>
                        <th scope="col">메뉴</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($venues as $venue)
                        <tr>
                            <th scope="row"><a href="/admin{{ $venue->path() }}?{{ request()->getQueryString() }}">{{ $venue->id }}</a></th>
                            <td>
                                <div> {{ $venue->title }}</div>
                            </td>
                            <td>
                                <div>{{ $venue->photo_refs ? count($venue->photo_refs) : 'n/a' }}</div>
                            </td>
                            <td>
                                <div>{{ count($venue->images()) }}</div>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="/admin/venues/{{ $venue->id }}/edit?{{ request()->getQueryString() }}">수정</a>
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
                    {{ $venues->appends($_GET)->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
