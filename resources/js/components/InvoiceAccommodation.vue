<template>
    <div class="mb-5">
        <div v-if="accommodations.length < 1" class="mb-2">
            <span>호텔 정보를 입력해주세요.</span>
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
                <draggable v-model="accommodations" tag="tbody">
                    <tr v-for="(accommodation, j) in accommodations" :key="j">
                        <th scope="row">
                            <input type="hidden" name="accommodations[]" :key="j" :value="JSON.stringify(accommodation)">
                            <button type="button" class="btn btn-outline-danger btn-sm" @click="onDeleteButtonClick(j)">X</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" @click="onEditButtonClick(j)">E</button>
                            <button type="button" class="btn btn-outline-dark btn-sm" @click="onCopyButtonClick(j)">C</button>
                        </th>
                        <td><span>{{ options.find(e => e.code === accommodation.accommodation_type).title }}</span></td>
                        <td><span>{{ accommodation.name }}</span></td>
                        <td><span>{{ accommodation.address }}</span></td>
                        <td><span>{{ accommodation.phone }}</span></td>
                        <td><span>{{ accommodation.url }}</span></td>
                        <td><span>{{ accommodation.comment }}</span></td>
                    </tr>
                </draggable>
            </table>
        </div>
        <div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="inputAccommodationType" class="text-secondary">구분</label>
                    <select id="inputAccommodationType" class="custom-select" v-model="input.accommodation_type">
                        <option v-for="(option, i) in options" :key="i" :value="option.code">{{ option.title }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="inputHotelName" class="text-secondary">이름</label>
                    <input id="inputHotelName" type="text" v-model="input.name" class="form-control" placeholder="이름" />
                </div>
                <div class="col-md-8">
                    <label for="inputHotelAddress" class="text-secondary">주소</label>
                    <input id="inputHotelAddress" type="text" v-model="input.address" class="form-control" placeholder="주" />
                </div>
                <div class="col-md-2">
                    <label for="inputHotelPhone" class="text-secondary">전화</label>
                    <input id="inputHotelPhone" type="text" v-model="input.phone" class="form-control" placeholder="전화" />
                </div>
                <div class="col-md-2">
                    <label for="inputHotelUrl" class="text-secondary">웹 URL</label>
                    <input id="inputHotelUrl" type="text" v-model="input.url" class="form-control" placeholder="URL" />
                </div>
                <div class="col-md-8">
                    <label for="inputHotelComment" class="text-secondary">비고</label>
                    <input id="inputHotelComment" type="text" v-model="input.comment" class="form-control" placeholder="비고" />
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
                    { text: '이름' },
                    { text: '주소' },
                    { text: '전화' },
                    { text: 'URL' },
                    { text: '비고' },
                ],
                options: [
                    { code: 'two-stars', title: '2 성급 호텔/리조트' },
                    { code: 'three-stars', title: '3 성급 호텔/리조트' },
                    { code: 'four-stars', title: '4 성급 호텔/리조트' },
                    { code: 'five-stars', title: '5 성급 호텔/리조트' },
                    { code: 'six-stars', title: '6 성급 호텔/리조트' },
                    { code: 'hanok', title: '한옥' },
                    { code: 'guest-house', title: '게스트하우스' },
                    { code: 'other', title: '기타' },
                ],
                input: {
                    accommodation_type: 'three-stars',
                    name: null,
                    address: 'TBA',
                    phone: 'TBA',
                    url: 'TBA',
                    comment: null
                },
                accommodations: this.items,
                editing: undefined
            };
        },
        methods: {
            resetInput() {
                this.input = {
                    accommodation_type: 'three-stars',
                    name: null,
                    address: 'TBA',
                    phone: 'TBA',
                    url: 'TBA',
                    comment: null
                };
            },

            // --------------------------------------------------------------------------------

            onDeleteButtonClick(i) {
                this.accommodations.splice(i, 1);
            },
            onCopyButtonClick(i) {
                this.accommodations = [...this.accommodations, {
                    accommodation_type: this.accommodations[i].accommodation_type,
                    name: this.accommodations[i].name,
                    address: this.accommodations[i].address,
                    phone: this.accommodations[i].phone,
                    url: this.accommodations[i].url,
                    comment: this.accommodations[i].comment,
                }];
            },
            onEditButtonClick(i) {
                this.editing = i;
                this.input.accommodation_type = this.accommodations[i].accommodation_type;
                this.input.name = this.accommodations[i].name;
                this.input.address = this.accommodations[i].address;
                this.input.phone = this.accommodations[i].phone;
                this.input.url = this.accommodations[i].url;
                this.input.comment = this.accommodations[i].comment;
            },
            onUpdateButtonClick() {
                this.accommodations.splice(this.editing, 1, {
                    accommodation_type: this.input.accommodation_type,
                    name: this.input.name,
                    address: this.input.address,
                    phone: this.input.phone,
                    url: this.input.url,
                    comment: this.input.comment,
                });
                this.resetInput();
                this.editing = undefined;
            },
            onAddButtonClick() {
                this.accommodations = [...this.accommodations, {
                    accommodation_type: this.input.accommodation_type,
                    name: this.input.name,
                    address: this.input.address,
                    phone: this.input.phone,
                    url: this.input.url,
                    comment: this.input.comment,
                }];
                this.resetInput();
            },
        }
    }
</script>

