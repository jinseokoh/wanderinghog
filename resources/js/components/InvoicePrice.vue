<template>
    <div class="mb-5">
        <div v-if="costs.length < 1" class="mb-2">
            <span>견적 항목을 입력해주세요.</span>
        </div>
        <div v-else>
            <input type="hidden" name="pax" :value="count" />
            <input type="hidden" name="total" :value="total" />
            <table class="table">
                <thead>
                <tr>
                    <th v-for="(header, i) in headers" :key="i" scope="col">
                        {{ header.text }}
                    </th>
                </tr>
                </thead>
                <draggable v-model="costs" tag="tbody">
                    <tr v-for="(cost, j) in costs" :key="j">
                        <th scope="row">
                            <input type="hidden" name="costs[]" :key="j" :value="JSON.stringify(cost)">
                            <button type="button" class="btn btn-outline-danger btn-sm" @click="onDeleteButtonClick(j)">X</button>
                            <button type="button" class="btn btn-outline-primary btn-sm" @click="onEditButtonClick(j)">E</button>
                        </th>
                        <td><span>{{ cost.title }}</span></td>
                        <td class="text-right"><span>{{ cost.subtotal.toLocaleString() }}</span> 원</td>
                        <td class="text-right"><span>{{ cost.price.toLocaleString() }}</span> 원</td>
                        <td><span>{{ cost.pax }}</span></td>
                        <td><span>{{ cost.comment }}</span></td>
                    </tr>
                </draggable>
                <tr v-if="costs.length" class="table-info">
                    <th scope="row"></th>
                    <td><span>합계</span></td>
                    <td class="text-right"><span>{{ sum.toLocaleString() }}</span> 원</td>
                    <td class="text-right"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr v-if="costs.length" class="table-info">
                    <th scope="row">
                        <button type="button" class="btn btn-outline-secondary btn-sm" @click="commissionRate--">감소</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" @click="commissionRate++">증가</button>
                    </th>
                    <td><span>수수료</span></td>
                    <td class="text-right"><span>{{ revenue.toLocaleString() }}</span> 원</td>
                    <td class="text-right"></td>
                    <td></td>
                    <td>
                        <span>{{ commissionRate }} %</span>
                    </td>
                </tr>
                <tr v-if="costs.length" class="table-info">
                    <th scope="row"></th>
                    <td><span>부가세</span></td>
                    <td class="text-right"><span>{{ vat.toLocaleString() }}</span> 원</td>
                    <td class="text-right"></td>
                    <td></td>
                    <td>수수료의 10%</td>
                </tr>
                <tr v-if="costs.length" class="table-info">
                    <th scope="row"></th>
                    <td><span>1인 견적가</span></td>
                    <td class="text-right"><span>{{ ratePerPax.toLocaleString() }}</span> 원</td>
                    <td class="text-right"></td>
                    <td></td>
                    <td>{{ gross.toLocaleString() }} 원 / {{ count }} = {{ (gross / count).toLocaleString() }}</td>
                </tr>
                <tr v-if="costs.length" class="table-info">
                    <th scope="row"></th>
                    <td><span>견적가 총합</span></td>
                    <td class="text-right"><span>{{ total.toLocaleString() }}</span> 원</td>
                    <td class="text-right"></td>
                    <td></td>
                    <td>{{ ratePerPax.toLocaleString() }} 원 * {{ count }}</td>
                </tr>
            </table>
        </div>
        <div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="inputTitle" class="text-secondary">항목</label>
                    <div v-if="input.title === 'etc'">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="제목" v-model="input.custom" aria-describedby="button-addon">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="button-addon" @click="input.title='airfare-adult'">취소</button>
                            </div>
                        </div>
                    </div>
                    <select v-else id="inputTitle" class="custom-select" v-model="input.title">
                        <option v-for="(title, i) in options" :key="i" :value="title.value">{{ title.text }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="inputPrice" class="text-secondary">단가</label>
                    <input id="inputPrice" type="text" v-model="input.price" class="form-control" placeholder="단가" />
                </div>
                <div class="col-md-3">
                    <label for="inputPax" class="text-secondary">수량</label>
                    <select id="inputPax" class="custom-select" v-model="input.pax">
                        <option v-for="n in 25" :value="n">{{ n }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="inputPriceComment" class="text-secondary">비고</label>
                    <input id="inputPriceComment" type="text" v-model="input.comment" class="form-control" placeholder="비고" />
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
            destination: {
                type: String,
                required: true
            },
            is_airtrip: {
                type: Boolean,
                default: false
            },
            count: {
                type: Number,
                required: true
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
                    { text: '이름' },
                    { text: '산출가' },
                    { text: '단가' },
                    { text: '수량' },
                    { text: '비고' },
                ],
                options: [],
                input: {
                    title: 'insurance',
                    custom: null,
                    subtotal: 0,
                    price: null,
                    pax: 1,
                    comment: null
                },
                costs: [],
                editing: undefined,
                commissionRate: 10,
            };
        },
        computed: {
            sum() {
                return this.costs.reduce((acc, item) => { return acc + item.subtotal }, 0);
            },
            revenue() {
                return Math.ceil(this.sum * (this.commissionRate / 100))
            },
            vat() {
                return Math.ceil(this.revenue * 0.1)
            },
            gross() {
                return this.sum + this.revenue + this.vat
            },
            ratePerPax() {
                return Math.ceil((this.gross / this.count) / 1000) * 1000
            },
            total() {
                return this.ratePerPax * this.count
            }
        },
        created() {
            if (this.destination === 'OVR') { // 해외상품
                this.options = [
                    { text: '여행자보험', value: 'insurance' },

                    { text: '왕복항공료 (성인)', value: 'airfare-adult' },
                    { text: '왕복항공료 (아동)', value: 'airfare-children' },
                    { text: '왕복항공료 (유아)', value: 'airfare-infant' },

                    { text: '지상비', value: 'land-operator' },

                    { text: '기타', value: 'etc' },
                ]
            } else { // 국내상품
                if (this.is_airtrip) {
                    this.options = [
                        { text: '여행자 보험', value: 'insurance' },

                        { text: '왕복항공료 (성인)', value: 'airfare-adult' },
                        { text: '왕복항공료 (아동)', value: 'airfare-children' },
                        { text: '왕복항공료 (유아)', value: 'airfare-infant' },

                        { text: '숙박비', value: 'accommodations' },
                        { text: '식사비', value: 'meals' },
                        { text: '차량비', value: 'transportation' },
                        { text: '인솔자 비용', value: 'tour-conductor' },
                        { text: '가이드 비용', value: 'tour-guide' },
                        { text: '관광지 입장료', value: 'tickets' },

                        { text: '인솔자 식사비', value: 'tc-meals' },
                        { text: '인솔자 숙박비', value: 'tc-accommodations' },
                        { text: '가이드 식사비', value: 'tg-meals' },
                        { text: '가이드 숙박비', value: 'tg-accommodations' },
                        { text: '기사 식사비', value: 'driver-meals' },
                        { text: '기사 숙박비', value: 'driver-accommodations' },

                        { text: '기타', value: 'etc' },
                    ];
                } else {
                    this.options = [
                        { text: '여행자 보험', value: 'insurance' },

                        { text: '숙박비', value: 'accommodations' },
                        { text: '식사비', value: 'meals' },
                        { text: '차량비', value: 'transportation' },
                        { text: '인솔자 비용', value: 'tour-conductor' },
                        { text: '가이드 비용', value: 'tour-guide' },
                        { text: '관광지 입장료', value: 'tickets' },

                        { text: '인솔자 식사비', value: 'tc-meals' },
                        { text: '인솔자 숙박비', value: 'tc-accommodations' },
                        { text: '가이드 식사비', value: 'tg-meals' },
                        { text: '가이드 숙박비', value: 'tg-accommodations' },
                        { text: '기사 식사비', value: 'driver-meals' },
                        { text: '기사 숙박비', value: 'driver-accommodations' },

                        { text: '기타', value: 'etc' },
                    ];
                }
            }
            this.input.pax = this.count;
            this.costs = this.items;
        },
        methods: {
            resetInput() {
                this.input = {
                    title: 'insurance',
                    custom: null,
                    subtotal: 0,
                    price: null,
                    pax: this.count,
                    comment: null
                };
            },

            // --------------------------------------------------------------------------------

            onDeleteButtonClick(i) {
                this.costs.splice(i, 1);
            },
            onEditButtonClick(i) {
                this.editing = i;
                const option = this.options.find(e => e.text === this.costs[i].title);
                if (option) {
                    this.input.title = option.value
                } else {
                    this.input.title = 'etc';
                    this.input.custom = this.costs[i].title;
                }
                this.input.price = this.costs[i].price;
                this.input.pax = this.costs[i].pax;
                this.input.comment = this.costs[i].comment;
            },
            onUpdateButtonClick() {
                const title = (this.input.title === 'etc' && this.input.custom) ? this.input.custom : this.options.find(e => e.value === this.input.title).text;
                const subtotal = parseInt(this.input.price) * this.input.pax;
                const price = parseInt(this.input.price);
                const pax = this.input.pax;
                const comment = this.input.comment;
                if (subtotal > 0 && title !== null) {
                    this.costs.splice(this.editing, 1, { title, subtotal, price, pax, comment });
                }
                this.resetInput();
                this.editing = undefined;
            },
            onAddButtonClick() {
                const title = (this.input.title === 'etc' && this.input.custom) ? this.input.custom : this.options.find(e => e.value === this.input.title).text;
                const subtotal = parseInt(this.input.price) * this.input.pax;
                const price = parseInt(this.input.price);
                const pax = this.input.pax;
                const comment = this.input.comment;
                if (subtotal > 0 && title !== null) {
                    this.costs = [...this.costs, { title, subtotal, price, pax, comment }]
                }
                this.resetInput();
            },
        }
    }
</script>

