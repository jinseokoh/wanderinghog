@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <h3>견적상품 보기</h3>
                    <div>
                        <a class="btn btn-outline-dark" href="/admin/quotes">리스트</a>
                    </div>
                </div>
                <div class="mb-3">
                    {{ $quote->user->name }} ({{ $quote->user->real_name }}) 고객님이
                    {{ $quote->created_at->diffForHumans() }} 문의한 상품입니다.
                </div>
                <div class="card mb-3">
                    <div class="card-header">견적상품</div>
                    <div class="card-body">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row" style="width: 120px">제목</th>
                                    <td><a href="/admin/products/{{ $quote->product->id }}">{{ $quote->product->title }}</a></td>
                                </tr>
                                <tr>
                                    <th scope="row">부제목</th>
                                    <td>{{ $quote->product->subtitle }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">설명</th>
                                    <td>{{ $quote->product->description }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">상태</th>
                                    <td>{{ __('quotes.'.Str::slug($quote->status)) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">출발 희망일</th>
                                    <td>{{ $quote->travel_date }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">여행객 정보</th>
                                    <td>
                                        @foreach ($quote->companions as $traveler)
                                            <div>
                                                {{ $traveler->real_name }} 님
                                                {{ $traveler->dob }} 생 (만 {{ Carbon\Carbon::parse($traveler->dob)->age }} 세 {{ $traveler->gender === 'M' ? '남성' : '여성' }})
                                                {{ $traveler->accessible_code ? '♿' : '' }}
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @if ($quote->product->is_airtrip)
                                    <tr>
                                        <th scope="row">항공옵션</th>
                                        <td>
                                            <div>선호하는 보딩시간 (한국 → 여행지) : {{ implode(',', $quote->air_departures) }}</div>
                                            <div>선호하는 보딩시간 (여행지 → 한국) : {{ implode(',', $quote->air_arrivals) }}</div>
                                            <div>선호하는 항공좌석 : {{ implode(',', $quote->air_seats) }}</div>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th scope="row">기타옵션</th>
                                    <td>
                                        장애인객실 : {{ $quote->accessible_room ? '필요' : '불필요' }},
                                        리프트차량 : {{ $quote->accessible_vehicle ? '필요' : '불필요' }},
                                        관광가이드 : {{ $quote->travel_guide ? '필요' : '불필요' }},
                                        휠체어대여 : {{ $quote->wheel_chair ? '필요' : '불필요' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">요청/문의</th>
                                    <td>
                                        <div>
                                            요청자 : {{ $quote->user->name . '(' . $quote->user->real_name . ')' }},
                                            전화 : {{ preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})$/", "\\1-\\2-\\3", $quote->user->phone) }},
                                            이메일 : {{ $quote->user->email }}
                                        </div>
                                        <div>{{ $quote->inquiry }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <form
                            id="model"
                            method="POST"
                            action="/admin/quotes/{{ $quote->id }}"
                        >
                            @if (! $quote->status->isEqual(App\Enums\QuoteStatusEnum::USER_CREATED()))
                                @method('PUT')
                            @endif
                            @csrf

                            <div class="card">
                                <div class="card-header">
                                    {{ (strpos($quote->product->code, 'OVR') !== false) ? '해외' : '국내'}}여행
                                    {{ $quote->companions->count() }} 인 견적
                                </div>
                                <div class="card-body">
                                    <invoice-price
                                        destination="{{ substr($quote->product->code, 0, 3) }}"
                                        :count="{{ $quote->companions->count() }}"
                                        :is_airtrip="{{ json_encode($quote->product->is_airtrip) }}"
                                        :items="{{ optional($quote->invoice)->costs ? json_encode($quote->invoice->costs) : json_encode([]) }}">
                                    </invoice-price>
                                    <invoice-flight
                                        :is_airtrip="{{ json_encode($quote->product->is_airtrip) }}"
                                        :items="{{ optional($quote->invoice)->flights ? json_encode($quote->invoice->flights) : json_encode([]) }}">
                                    </invoice-flight>
                                    <invoice-accommodation
                                        :items="{{ optional($quote->invoice)->accommodations ? json_encode($quote->invoice->accommodations) : json_encode([]) }}">
                                    </invoice-accommodation>

                                    @if (! $quote->status->isEqual(App\Enums\QuoteStatusEnum::COMPLETED()))
                                        <div class="form-group">
                                            <label for="opening" class="text-secondary">견적서 안내문 (고객의 최초 문의시 보내는 최초 견적서 안내문입니다.)</label>
                                            <textarea id="opening" name="opening" class="form-control" rows="3" placeholder="안내문">{{ old('opening', optional($quote->invoice)->opening) }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="ending" class="text-secondary">확약서 안내문 (계약금을 받은 이후 보내는 최종 견적서 안내문입니다.)</label>
                                            <textarea id="ending" name="ending" class="form-control" rows="3" placeholder="안내문">{{ old('ending', optional($quote->invoice)->ending) }}</textarea>
                                        </div>
                                    @endif

                                    <hr class="mb-3">

                                    @if (count($errors))
                                        <ul class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    @if ($quote->status->isEqual(App\Enums\QuoteStatusEnum::USER_CREATED()))
                                        <button class="btn btn-primary btn" type="submit">견적서 전송</button>
                                    @elseif ($quote->status->isEqual(App\Enums\QuoteStatusEnum::ADMIN_SENT_ESTIMATE()))
                                        <button class="btn btn-primary btn" type="submit">견적서 내용수정 (주의: 고객알림 없음)</button>
                                    @elseif ($quote->status->isEqual(App\Enums\QuoteStatusEnum::USER_PAID_DEPOSIT()))
                                        <button class="btn btn-primary btn" type="submit">확약서 전송</button>
                                    @elseif ($quote->status->isEqual(App\Enums\QuoteStatusEnum::ADMIN_SENT_INVOICE()))
                                        <button class="btn btn-primary btn" type="submit">확약서 내용수정 (주의: 고객알림 없음)</button>
                                    @elseif ($quote->status->isEqual(App\Enums\QuoteStatusEnum::USER_PAID_BALANCE()))
                                        <button class="btn btn-primary btn" type="submit">확약서 내용수정 (주의: 고객알림 없음)</button>
                                    @endif

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if ($quote->invoice)
                    <div class="row" style="margin-bottom: 6em;">
                        <div class="col-4">
                            <form method="POST" action="/admin/quotes/{{ $quote->id }}/deposit">
                                @method('PUT')
                                @csrf
                                <div class="card">
                                    <div class="card-header">계약금 승인</div>
                                    <div class="card-body">
                                        <div>총합 : {{ number_format($quote->invoice->total) }} 원</div>
                                        <div>계약금 : {{ number_format($quote->invoice->deposit) }} 원</div>
                                        <div>누적입금액 : {{ number_format($quote->invoice->sum()) }} 원</div>
                                        <div>주문상태 : {{ __('quotes.'.Str::slug($quote->status)) }}</div>

                                        <hr class="mb-3">

                                        @if (!! $quote->invoice->pendingDepositReceipt())
                                            <button class="btn btn-success" type="submit">계약금 입금 승인</button>
                                        @else
                                            <button class="btn btn-secondary" disabled type="submit">계약금 입금 승인</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-4">
                            <form method="POST" action="/admin/quotes/{{ $quote->id }}/balance">
                                @method('PUT')
                                @csrf
                                <div class="card">
                                    <div class="card-header">잔금 승인</div>
                                    <div class="card-body">
                                        <div>총합 : {{ number_format($quote->invoice->total) }} 원</div>
                                        <div>잔금 : {{ number_format($quote->invoice->total - $quote->invoice->deposit) }} 원</div>
                                        <div>누적입금액 : {{ number_format($quote->invoice->sum()) }} 원</div>
                                        <div>주문상태 : {{ __('quotes.'.Str::slug($quote->status)) }}</div>

                                        <hr class="mb-3">

                                        @if (!! $quote->invoice->pendingBalanceReceipt())
                                            <button class="btn btn-success" type="submit">잔금 입금 승인</button>
                                        @else
                                            <button class="btn btn-secondary" disabled type="submit">잔금 입금 승인</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-4">
                            <form method="POST" action="/admin/quotes/{{ $quote->id }}/complete">
                                @method('PUT')
                                @csrf
                                <div class="card">
                                    <div class="card-header">해피콜 완료</div>
                                    <div class="card-body">
                                        <div>일정표 전달</div>
                                        <div>이티켓 전달</div>
                                        <div>누적입금액 : {{ number_format($quote->invoice->sum()) }} 원</div>
                                        <div>주문상태 : {{ __('quotes.'.Str::slug($quote->status)) }}</div>

                                        <hr class="mb-3">

                                        @if ($quote->status->isEqual(App\Enums\QuoteStatusEnum::USER_PAID_BALANCE()))
                                            <button class="btn btn-success" type="submit">진행완료 승인</button>
                                        @else
                                            <button class="btn btn-secondary" disabled type="submit">진행완료 승인</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
