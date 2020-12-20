@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h3>Users</h3>
                <div class="mb-3">사용자 정보</div>
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
                        <td scope="row" class="text-center" style="width: 48px;">이름</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-center" style="width: 48px;">이메일</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-center" style="width: 48px;">전화번호</td>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-center" style="width: 48px;">성별</td>
                        <td>{{ $user->gender }}</td>
                    </tr>
                    <tr>
                        <td scope="row" class="text-center" style="width: 48px;">나이</td>
                        <td>{{ Carbon\Carbon::parse($user->dob)->age }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
