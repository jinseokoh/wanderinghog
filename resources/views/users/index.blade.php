@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>회원리스트</h3>
                    {{ request()->getQueryString() }}
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a class="btn btn-sm @if (empty(request()->query('gender'))) btn-dark @else btn-outline-dark @endif" href="/admin/users">전체</a>
                        <a class="btn btn-sm @if (request()->query('gender')=='M') btn-dark @else btn-outline-dark @endif" href="/admin/users?gender=M">남성</a>
                        <a class="btn btn-sm @if (request()->query('gender')=='F') btn-dark @else btn-outline-dark @endif" href="/admin/users?gender=F">여성</a>
                    </div>
                    <div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">아바타</th>
                        <th scope="col">사진</th>
                        <th scope="col">인증</th>
                        <th scope="col">아이디</th>
                        <th scope="col">이름</th>
                        <th scope="col">성별</th>
                        <th scope="col">나이</th>
                        <th scope="col">이메일</th>
                        <th scope="col">연락처</th>
                        <th scope="col">상태</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">
                                <a href="/admin{{ $user->path() }}?{{ request()->getQueryString() }}">{{ $user->id }}</a>
                            </th>
                            <td>
                                <img src="{{ $user->avatar }}" style="width: 100px; height: 100px;" />
                            </td>
                            <td>
                                <div>
                                    @forelse ($user->photos() as $photo)
                                        <img src="{{ $photo['image'] }}" style="width: 50px; height: 50px;" />
                                    @empty
                                        <p>No Photos</p>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($user->image())
                                        <img src="{{ $user->image()['image'] }}" style="width: 50px; height: 50px;" />
                                    @endif
                                </div>
                                <div>@if($user->profession_verified_at)<span class="badge badge-success">확인됨</span>@endif</div>
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->gender }}</td>
                            <td>{{ $user->age }}</td>
                            <td>
                                <div>{{ $user->email }}</div>
                                <div>@if($user->email_verified_at)<span class="badge badge-success">확인됨</span>@endif</div>
                            </td>
                            <td>
                                <div>{{ $user->phone }}</div>
                                <div>@if($user->phone_verified_at)<span class="badge badge-success">확인됨</span>@endif</div>
                            </td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge badge-pill badge-success">활성</span>
                                @else
                                    <span class="badge badge-pill badge-light">비활성</span>
                                @endif
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
                <div>{{ $users->appends($_GET)->links() }}</div>
            </div>
        </div>
    </div>
@endsection
