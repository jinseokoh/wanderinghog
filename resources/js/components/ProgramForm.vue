<template>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>일정</div>
            <button class="btn btn-sm btn-outline-dark" data-toggle="modal" data-target="#i-modal" @click="onProgramPopup()">일정 생성</button>
        </div>
        <div v-if="programs.length < 1" class="card-body">
            <span>일정 내용이 없습니다.</span>
        </div>
        <div v-else class="card-body">
            <div v-for="(program, i) in programs" :key="i" class="card text-white bg-secondary" :class="i < programs.length - 1 ? 'mb-3' : ''">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge badge-pill badge-success">{{ program.day }}</span>
                        <span class="mr-2">일차</span>
                    </div>
                    <div v-if="program.items.length < 1">
                        <button class="btn btn-sm btn-danger" @click="onItineraryDelete(program.id)">일정 삭제</button>
                        <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#i-modal" @click="onProgramPopup(program.id)">일정 수정</button>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#i-modal" @click="onItemPopup(i, program.id)">아이템 생성</button>
                    </div>
                    <div v-else>
                        <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#i-modal" @click="onProgramPopup(program.id)">일정 수정</button>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#i-modal" @click="onItemPopup(i, program.id)">아이템 생성</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">제목</div>
                        <div class="col-5">
                            {{ program.title.ko }}
                        </div>
                        <div class="col-5">
                            {{ program.title.en }}
                        </div>
                    </div>
<!--                    <div class="row">-->
<!--                        <div class="col-2">설명</div>-->
<!--                        <div class="col-5">-->
<!--                            {{ program.description.ko }}-->
<!--                        </div>-->
<!--                        <div class="col-5">-->
<!--                            {{ program.description.en }}-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
                <div v-if="program.items.length < 1" class="card-body">
                    <span>아이템 내용이 없습니다.</span>
                </div>
                <div v-else class="card-body text-dark">
                    <ul v-for="(item, j) in program.items" :key="j" class="list-group" :class="j < program.items.length - 1 ? 'mb-3' : ''">
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <span class="badge badge-pill badge-dark">{{ item.order }}</span>
                                <span class="mr-3">번</span>
                                <span>[{{ itemName(item.slug) }}]</span>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-danger" @click="onItemDelete(program.id, item.id)">삭제</button>
                                <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#i-modal" @click="onItemPopup(i, program.id, item.id)">수정</button>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-2">
                                    <item-image :item_id="item.id" :image="item.image" @changed="onReload"></item-image>
                                </div>
                                <div class="col-md-5">
                                    <div>{{ item.title.ko }}</div>
                                    <div>{{ item.description.ko }}</div>
                                </div>
                                <div class="col-md-5">
                                    <div>{{ item.title.en }}</div>
                                    <div>{{ item.description.en }}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="modal fade" v-show="modal" id="i-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ modal === 1 ? '일정' : '아이템' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="modal === 1">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    일차
                                </div>
                                <div class="col-md-9">
                                    <select id="day" class="form-control" v-model="programInProcess.day" required>
                                        <option v-for="n in programs.length + 1" :key="n">{{ n }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" v-model="programInProcess.title" class="form-control" placeholder="제목" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" v-model="programInProcess.title_en" class="form-control" placeholder="Title" />
                                </div>
                            </div>
                        </div>
                        <div v-else-if="modal === 2">
                            <div class="row">
                                <div class="col-md-3">
                                    순번
                                </div>
                                <div class="col-md-9">
                                    <select id="order" class="form-control" v-model="itemInProcess.order" required>
                                        <option v-for="n in programs[currentItineraryOrder].items.length + 1" :key="n">{{ n }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    종류
                                </div>
                                <div class="col-md-9">
                                    <select id="item_type" class="form-control" v-model="itemInProcess.type" required>
                                        <option v-for="(itemType, k) in itemTypes" :key="k" :value="itemType">{{ itemType.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" v-model="itemInProcess.title" class="form-control" placeholder="제목" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" v-model="itemInProcess.title_en" class="form-control" placeholder="Title" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <textarea v-model="itemInProcess.description" rows="5" class="form-control" placeholder="상세내용"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <textarea v-model="itemInProcess.description_en" rows="5" class="form-control" placeholder="Description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="modal === 1 && currentItineraryId === 0" type="button" class="btn btn-primary" @click="onItineraryAdd">일정 생성</button>
                        <button v-else-if="modal === 1 && currentItineraryId > 0" type="button" class="btn btn-primary" @click="onItineraryEdit">일정 생성</button>
                        <button v-else-if="modal === 2 && currentItemId === 0" type="button" class="btn btn-primary" @click="onItemAdd">아이템 생성</button>
                        <button v-else-if="modal === 2 && currentItemId > 0" type="button" class="btn btn-primary" @click="onItemEdit">아이템 수정</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ItemImage from './ItemImage.vue';
    export default {
        components: { ItemImage },
        props: {
            product_id: {
                type: Number,
                required: true
            },
            input: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                modal: 0, // 1 = program, 2 = item
                currentItineraryOrder: 0,
                currentItineraryId: 0,
                currentItemId: 0,

                programInProcess: null,
                itemInProcess: null,

                programs: [],
                itemTypes: [
                    { name: '육로이동', slug: 'ROAD' },
                    { name: '선박이동', slug: 'BOAT' },
                    { name: '항공이동', slug: 'FLIGHT' },
                    { name: '공항출발', slug: 'FLIGHT_DEPARTURE' },
                    { name: '공항도착', slug: 'FLIGHT_ARRIVAL' },
                    { name: '숙소', slug: 'ACCOMMODATION' },
                    { name: '식사', slug: 'MEAL' },
                    { name: '관광', slug: 'SIGHTSEEING' },
                    { name: '정보', slug: 'INFORMATION' },
                    { name: '주의사항', slug: 'WARNING' },
                ],
            };
        },
        mounted() {
            if (this.input) {
                this.programs = this.input;
            }
        },
        methods: {
            async fetchItinerary() {
                const { data } = await axios.get(`/admin/api/products/${this.product_id}/programs/${this.currentItineraryId}`);
                return this.programInProcess = data;
            },
            async fetchItem() {
                const { data } = await axios.get(`/admin/api/programs/${this.currentItineraryId}/items/${this.currentItemId}`);
                return this.itemInProcess = data;
            },

            // --------------------------------------------------------------------------------

            onPopupClose() {
                this.modal = 0;
            },
            onProgramPopup(programId = 0) {
                this.currentItineraryId = programId;

                if (programId === 0) {
                    this.programInProcess = {
                        day: this.programs.length + 1,
                        title: ''
                    };
                    this.modal = 1;
                } else {
                    this.fetchItinerary().then(() => {
                        this.modal = 1;
                    });
                }
            },
            onItemPopup(id, programId, itemId = 0) {
                this.currentItineraryOrder = id;
                this.currentItineraryId = programId;
                this.currentItemId = itemId;

                if (itemId === 0) {
                    this.itemInProcess = {
                        order: this.programs[id].items.length + 1,
                        type: { name: '육로이동', slug: 'ROAD' },
                        title: '',
                        description: ''
                    };
                    this.modal = 2;
                } else { // retrieve current item
                    this.fetchItem().then(() => {
                        this.modal = 2;
                    });
                }
            },
            onReload() {
                axios.get(`/admin/api/products/${this.product_id}/programs`)
                    .then(({data}) => {
                        this.programs = data
                    });
            },

            // --------------------------------------------------------------------------------

            onItineraryAdd() {
                axios.post(`/admin/api/products/${this.product_id}/programs`, this.programInProcess)
                    .then(() => {
                        this.onReload();
                        flash('성공적으로 생성했습니다.');
                    })
                    .catch(({ response }) => {
                        if (response.data.hasOwnProperty('errors')) {
                            let errorFields = Object.keys(response.data.errors);
                            let field = errorFields[0];
                            const messages = response.data.errors[field];
                            flash(messages[0], 'danger');
                        }
                    })
                    .finally(() => {
                        $('#i-modal').modal('hide');
                        this.onPopupClose();
                    })
                ;
            },
            onItineraryEdit() {
                axios.put(`/admin/api/products/${this.product_id}/programs/${this.currentItineraryId}`, this.programInProcess)
                    .then(() => {
                        this.onReload();
                        flash('성공적으로 수정했습니다.');
                    })
                    .catch(({ response }) => {
                        if (response.data.hasOwnProperty('errors')) {
                            let errorFields = Object.keys(response.data.errors);
                            let field = errorFields[0];
                            const messages = response.data.errors[field];
                            flash(messages[0], 'danger');
                        }
                    })
                    .finally(() => {
                        $('#i-modal').modal('hide');
                        this.onPopupClose();
                    })
                ;
            },
            onItineraryDelete(id) {
                axios.delete(`/admin/api/products/${this.product_id}/programs/${id}`)
                    .then(() => {
                        this.onReload();
                        flash('성공적으로 삭제했습니다.');
                    })
                    .catch(({ response }) => {
                        flash(response.data, 'danger');
                    })
            },

            // --------------------------------------------------------------------------------

            onItemAdd() {
                axios.post(`/admin/api/programs/${this.currentItineraryId}/items`, this.itemInProcess)
                    .then(({data}) => {
                        this.onReload();
                        flash('성공적으로 생성했습니다.');
                    })
                    .catch(({ response }) => {
                        if (response.data.hasOwnProperty('errors')) {
                            let errorFields = Object.keys(response.data.errors);
                            let field = errorFields[0];
                            const messages = response.data.errors[field];
                            flash(messages[0], 'danger');
                        }
                    })
                    .finally(() => {
                        $('#i-modal').modal('hide');
                        this.onPopupClose();
                    })
            },
            onItemEdit() {
                axios.put(`/admin/api/programs/${this.currentItineraryId}/items/${this.currentItemId}`, this.itemInProcess)
                    .then(({data}) => {
                        this.onReload();
                        flash('성공적으로 수정했습니다.');
                    })
                    .catch(({ response }) => {
                        if (response.data.hasOwnProperty('errors')) {
                            let errorFields = Object.keys(response.data.errors);
                            let field = errorFields[0];
                            const messages = response.data.errors[field];
                            flash(messages[0], 'danger');
                        }
                    })
                    .finally(() => {
                        $('#i-modal').modal('hide');
                        this.onPopupClose();
                    })
            },
            onItemDelete(program, item) {
                axios.delete(`/admin/api/programs/${program}/items/${item}`)
                    .then(() => {
                        this.onReload();
                        flash('아이템이 삭제되었습니다.');
                    })
                    .catch(({ response }) => {
                        flash(response.data, 'danger');
                    })
            },

            // --------------------------------------------------------------------------------

            itemName(v) {
                return this.itemTypes.find(i => i.slug === v).name;
            },
        }
    }
</script>

