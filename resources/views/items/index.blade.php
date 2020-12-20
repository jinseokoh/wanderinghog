@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>견적문의</h3>
                </div>
                <div class="mb-3">견적문의 리스트입니다.</div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">제목</th>
                        <th scope="col">출발희망일</th>
                        <th scope="col">사용자</th>
                        <th scope="col">여행객</th>
                        <th scope="col">요청일</th>
                        <th scope="col">상태</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($quotes as $quote)
                        <tr>
                            <th scope="row">{{ $quote->id }}</th>
                            <td>
                                <a href="/admin{{ $quote->path() }}">
                                    {{ $quote->product->title }}
                                </a>
                            </td>
                            <td>
                                <div class="body">{{ $quote->travel_date }}</div>
                            </td>
                            <td>
                                <span>{{ $quote->user->name }}</span>
                                (<span>{{ $quote->user->real_name }}</span>)
                            </td>
                            <td>
                                <span>{{ $quote->companions->count() }}</span>
                            </td>
                            <td>
                                <span>{{ $quote->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <span>{{ __('quotes.'.Str::slug($quote->status)) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th scope="row"></th>
                            <td colspan="6">
                                아이템이 없습니다.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
