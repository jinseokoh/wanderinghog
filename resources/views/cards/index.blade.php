@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>신원인증</h3>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a class="btn btn-sm @if (empty(request()->query('type'))) btn-dark @else btn-outline-dark @endif" href="/admin/cards">전체</a>
                        <a class="btn btn-sm @if (request()->query('type')=='C') btn-dark @else btn-outline-dark @endif" href="/admin/cards?type=C">완료</a>
                        <a class="btn btn-sm @if (request()->query('type')=='P') btn-dark @else btn-outline-dark @endif" href="/admin/cards?type=P">대기</a>
                    </div>
                    <div>
                    </div>
                </div>
                <profession-select
                    query_string="{{ 'query=abc' }}"
                    :users="{{ json_encode($userResource->getCollection()) }}"
                >
                </profession-select>
                <div>{{ $userResource->appends($_GET)->links() }}</div>
            </div>
        </div>
    </div>
@endsection
