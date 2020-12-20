<template>
    <div v-if="is_airtrip" class="mb-5">
        <div v-if="flights.length < 1" class="mb-2">
            <span>항공 정보를 입력해주세요.</span>
        </div>
        <div v-else>
            <table class="table">
                <thead>
                <tr>
                    <th v-for="(header, i) in headers" :key="i" scope="col">
                        {{ header.text }}
                    </th>
                </tr>
                </thead>
                <draggable v-model="flights" tag="tbody">
                    <tr v-for="(flight, j) in flights" :key="j">
                        <th scope="row">
                            <input type="hidden" name="flights[]" :key="j" :value="JSON.stringify(flight)">
                            <button type="button" class="btn btn-outline-danger btn-sm" @click="onDeleteButtonClick(j)">X</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" @click="onEditButtonClick(j)">E</button>
                            <button type="button" class="btn btn-outline-dark btn-sm" @click="onCopyButtonClick(j)">C</button>
                        </th>
                        <td><span>{{ options.find(e => e.code === flight.flight_type).title }}</span></td>
                        <td><span>{{ flight.airline }}</span></td>
                        <td><span>{{ flight.flight_number }}</span></td>
                        <td><span>{{ flight.seat_number }}</span></td>
                        <td><span>{{ flight.origin_country }}</span></td>
                        <td><span>{{ flight.origin_city }}</span></td>
                        <td><span>{{ flight.origin_time }}</span></td>
                        <td><span>{{ flight.destin_country }}</span></td>
                        <td><span>{{ flight.destin_city }}</span></td>
                        <td><span>{{ flight.destin_time }}</span></td>
                        <td><span>{{ flight.comment }}</span></td>
                    </tr>
                </draggable>
            </table>
        </div>
        <div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="inputFlightType" class="text-secondary">구분</label>
                    <select id="inputFlightType" class="custom-select" v-model="input.flight_type">
                        <option v-for="(option, i) in options" :key="i" :value="option.code">{{ option.title }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="inputAirline" class="text-secondary">항공사</label>
                    <input id="inputAirline" type="text" v-model="input.airline" class="form-control" placeholder="항공사" />
                </div>
                <div class="col-md-2">
                    <label for="inputFlightNumber" class="text-secondary">항공기 번호</label>
                    <input id="inputFlightNumber" type="text" v-model="input.flight_number" class="form-control" placeholder="항공기번호" />
                </div>
                <div class="col-md-2">
                    <label for="inputSeatNumber" class="text-secondary">좌석 번호</label>
                    <input id="inputSeatNumber" type="text" v-model="input.seat_number" class="form-control" placeholder="좌석번호" />
                </div>
                <div class="col-md-4">
                    <label for="inputFlightComment" class="text-secondary">비고</label>
                    <input id="inputFlightComment" type="text" v-model="input.comment" class="form-control" placeholder="비고" />
                </div>
                <div class="col-md-2">
                    <label for="inputOriginCountry" class="text-secondary">출발국가</label>
                    <input id="inputOriginCountry" type="text" v-model="input.origin_country" class="form-control" placeholder="국가" />
                </div>
                <div class="col-md-2">
                    <label for="inputOriginCity" class="text-secondary">출발도시</label>
                    <input id="inputOriginCity" type="text" v-model="input.origin_city" class="form-control" placeholder="도시" />
                </div>
                <div class="col-md-2">
                    <label for="inputOriginTime" class="text-secondary">출발시각</label>
                    <input id="inputOriginTime" type="text" v-model="input.origin_time" class="form-control" placeholder="시각" />
                </div>
                <div class="col-md-2">
                    <label for="inputDestinCountry" class="text-secondary">도착국가</label>
                    <input id="inputDestinCountry" type="text" v-model="input.destin_country" class="form-control" placeholder="국가" />
                </div>
                <div class="col-md-2">
                    <label for="inputDestinCity" class="text-secondary">도착도시</label>
                    <input id="inputDestinCity" type="text" v-model="input.destin_city" class="form-control" placeholder="도시" />
                </div>
                <div class="col-md-2">
                    <label for="inputDestinTime" class="text-secondary">도착시각</label>
                    <input id="inputDestinTime" type="text" v-model="input.destin_time" class="form-control" placeholder="시각" />
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button v-if="editing === undefined" class="btn btn-block btn-outline-dark" type="button" @click="onAddButtonClick">항목 추가</button>
                    <button v-else class="btn btn-block btn-outline-primary" type="button" @click="onUpdateButtonClick">항목 수정</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import draggable from "vuedraggable"

    export default {
        components: {
            draggable,
        },
        props: {
            is_airtrip: {
                type: Boolean,
                default: false
            },
            items: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                headers: [
                    { text: '#' },
                    { text: '구분' },
                    { text: '항공사' },
                    { text: '항공기 번호' },
                    { text: '좌석 번호' },
                    { text: '출발 국가' },
                    { text: '출발 도시' },
                    { text: '출발 시각' },
                    { text: '도착 국가' },
                    { text: '도착 도시' },
                    { text: '도착 시각' },
                    { text: '비고' },
                ],
                options: [
                    { code: 1, title: '직항' },
                    { code: 2, title: '경유' },
                ],
                input: {
                    flight_type: 1,
                    airline: null,
                    flight_number: 'TBA',
                    seat_number: 'TBA',
                    origin_country: null,
                    origin_city: null,
                    origin_time: null,
                    destin_country: null,
                    destin_city: null,
                    destin_time: null,
                    comment: null
                },
                flights: this.items,
                editing: undefined
            };
        },
        methods: {
            resetInput() {
                this.input = {
                    flight_type: 1,
                    airline: null,
                    flight_number: 'TBA',
                    seat_number: 'TBA',
                    origin_country: null,
                    origin_city: null,
                    origin_time: null,
                    destin_country: null,
                    destin_city: null,
                    destin_time: null,
                    comment: null
                };
            },

            // --------------------------------------------------------------------------------

            onDeleteButtonClick(i) {
                this.flights.splice(i, 1);
            },
            onCopyButtonClick(i) {
                this.flights = [...this.flights, {
                    flight_type: this.flights[i].flight_type,
                    airline: this.flights[i].airline,
                    flight_number: this.flights[i].flight_number,
                    seat_number: this.flights[i].seat_number,
                    origin_country: this.flights[i].origin_country,
                    origin_city: this.flights[i].origin_city,
                    origin_time: this.flights[i].origin_time,
                    destin_country: this.flights[i].destin_country,
                    destin_city: this.flights[i].destin_city,
                    destin_time: this.flights[i].destin_time,
                    comment: this.flights[i].comment,
                }];
            },
            onEditButtonClick(i) {
                this.editing = i;
                this.input.flight_type = this.flights[i].flight_type;
                this.input.airline = this.flights[i].airline;
                this.input.flight_number = this.flights[i].flight_number;
                this.input.seat_number = this.flights[i].seat_number;
                this.input.origin_country = this.flights[i].origin_country;
                this.input.origin_city = this.flights[i].origin_city;
                this.input.origin_time = this.flights[i].origin_time;
                this.input.destin_country = this.flights[i].destin_country;
                this.input.destin_city = this.flights[i].destin_city;
                this.input.destin_time = this.flights[i].destin_time;
                this.input.comment = this.flights[i].comment;
            },
            onUpdateButtonClick() {
                this.flights.splice(this.editing, 1, {
                    flight_type: this.input.flight_type,
                    airline: this.input.airline,
                    flight_number: this.input.flight_number,
                    seat_number: this.input.seat_number,
                    origin_country: this.input.origin_country,
                    origin_city: this.input.origin_city,
                    origin_time: this.input.origin_time,
                    destin_country: this.input.destin_country,
                    destin_city: this.input.destin_city,
                    destin_time: this.input.destin_time,
                    comment: this.input.comment,
                });
                this.resetInput();
                this.editing = undefined;
            },
            onAddButtonClick() {
                this.flights = [...this.flights, {
                    flight_type: this.input.flight_type,
                    airline: this.input.airline,
                    flight_number: this.input.flight_number,
                    seat_number: this.input.seat_number,
                    origin_country: this.input.origin_country,
                    origin_city: this.input.origin_city,
                    origin_time: this.input.origin_time,
                    destin_country: this.input.destin_country,
                    destin_city: this.input.destin_city,
                    destin_time: this.input.destin_time,
                    comment: this.input.comment,
                }];
                this.resetInput();
            },
        }
    }
</script>

