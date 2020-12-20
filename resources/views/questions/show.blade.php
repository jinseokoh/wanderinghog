@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>질문 상세정보</h3>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div></div>
                    <div>
                        <a class="btn btn-sm btn-outline-dark" href="/admin/questions?{{ request()->getQueryString() }}">리스트</a>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th colspan="2">
                            <div>항목별 내용</div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">분류</th>
                        <td>{{ __('questions.'.(new \App\Enums\QuestionEnum($question->parent->slug))->value) }}</td>
                    </tr>
                    <tr>
                        <th scope="row">질문</th>
                        <td>{{ $question->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">답변 리스트</th>
                        <td>
                            <div class="row">
                                @forelse ($question->answers as $answer)
                                    <ul>
                                        <li><a href="/admin/users/{{ $answer->user->id }}">{{ $answer->user->username }}</a> ({{ $answer->user->gender }}/{{ $answer->user->age }}) - {{ $answer->body }}</li>
                                    </ul>
                                @empty
                                    <ul><li>no records</li></ul>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
