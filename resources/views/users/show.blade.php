@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div>
                    <h3>사용자 상세정보</h3>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <div></div>
                    <div>
                        <a class="btn btn-sm btn-primary" href="/admin/users?{{ request()->getQueryString() }}">리스트</a>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="4">
                                <div>항목별 내용</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row" class="text-center">사진</td>
                            <td>
                                <div>
                                    <img src="{{ $user->avatar }}" style="width: 100px; height: 100px;" />
                                    @forelse ($user->photos() as $photo)
                                        <img src="{{ $photo['image'] }}" style="width: 100px; height: 100px;" />
                                    @empty
                                        <p>No Photos</p>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($user->image())
                                        <img src="{{ $user->image()['image'] }}" style="width: 100px; height: 100px;" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center" style="min-width: 80px;">이름</td>
                            <td>{{ $user->name }} ({{ $user->gender === 'M' ? '남성' : '여성' }})</td>
                            <td>{{ $user->age }} 세</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">이메일</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->email_verified_at }}</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">전화번호</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->phone_verified_at }}</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">성별</td>
                            <td>{{ $user->gender }}</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">나이</td>
                            <td>{{ Carbon\Carbon::parse($user->dob)->age }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
