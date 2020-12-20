<template>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>지도</div>
            <button class="btn btn-sm btn-outline-dark" data-toggle="modal" data-target="#m-modal" @click="onMapPopup(0)">지도 생성</button>
        </div>
        <div v-if="maps.length < 1" class="card-body">
            <span>지도 내용이 없습니다.</span>
        </div>
        <div v-else class="card-body">
            <div v-for="(map, i) in maps" :key="i" class="card text-white bg-secondary" :class="i < maps.length - 1 ? 'mb-3' : ''">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="ellipsis">
                        <span class="badge badge-pill badge-success">{{ map.day }}</span>
                        <span class="mr-2">일차</span>
                    </div>
                    <div v-if="map.venues.length < 1">
                        <button class="btn btn-sm btn-danger" @click="onMapDelete(map.id)">지도 삭제</button>
                        <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#m-modal" @click="onMapPopup(map.id)">지도 수정</button>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#m-modal" @click="onVenuePopup(i, map.id)">베뉴 생성</button>
                    </div>
                    <div v-else>
                        <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#m-modal" @click="onMapPopup(map.id)">지도 수정</button>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#m-modal" @click="onVenuePopup(i, map.id)">베뉴 생성</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">제목</div>
                        <div class="col-5">
                            {{ map.title.ko }}
                        </div>
                        <div class="col-5">
                            {{ map.title.en }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">설명</div>
                        <div class="col-5">
                            {{ map.description.ko }}
                        </div>
                        <div class="col-5">
                            {{ map.description.en }}
                        </div>
                    </div>
                </div>
                <div v-if="map.venues.length < 1" class="card-body">
                    <span>베뉴 내용이 없습니다.</span>
                </div>
                <div v-else class="card-body text-dark">
                    <ul v-for="(venue, j) in map.venues" :key="j" class="list-group" :class="j < map.venues.length - 1 ? 'mb-3' : ''">
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <span class="badge badge-pill badge-dark">{{ venue.order }}</span>
                                <span class="mr-3">번</span>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-danger" @click="onVenueDelete(map.id, venue.id)">삭제</button>
                                <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#m-modal" @click="onVenuePopup(i, map.id, venue.id)">수정</button>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-2">제목</div>
                                <div class="col-5">
                                    {{ venue.title.ko }}
                                </div>
                                <div class="col-5">
                                    {{ venue.title.en }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-2">설명</div>
                                <div class="col-5">
                                    {{ venue.description.ko }}
                                </div>
                                <div class="col-5">
                                    {{ venue.description.en }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="badge badge-secondary">위도</span>
                                            <span>{{ venue.latitude }}</span>
                                            <span class="badge badge-secondary">경도</span>
                                            <span>{{ venue.longitude }}</span>
                                            <span class="badge badge-secondary">줌레벨</span>
                                            <span>{{ venue.zoom_level }}</span>
                                            <span class="badge badge-secondary">url</span>
                                            <span v-if="venue.url">{{ venue.url }}</span>
                                            <span v-else>n/a</span>
                                        </li>
                                        <li class="list-group-item">
                                            <span class="badge badge-secondary">전화</span>
                                            <span v-if="venue.phone">{{ venue.phone }}</span>
                                            <span v-else>n/a</span>
                                            <span class="badge badge-secondary">주소</span>
                                            <span v-if="venue.address">{{ venue.address }}</span>
                                            <span v-else>n/a</span>
                                        </li>
                                        <li class="list-group-item ellipsis">
                                            <span v-if="venue.geo_json">{{ venue.geo_json }}</span>
                                            <span v-else>입력된 GeoJson 데이터 없음</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="modal fade" v-show="modal" id="m-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">지도</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="modal === 1">
                            <div class="form-group">
                                <label for="day">일차</label>
                                <select id="day" class="form-control" v-model="mapInProcess.day" required>
                                    <option v-for="n in 20" :key="n">{{ n }}</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" v-model="mapInProcess.title" class="form-control" placeholder="제목" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" v-model="mapInProcess.title_en" class="form-control" placeholder="Title" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" v-model="mapInProcess.description" rows="3" class="form-control" placeholder="설명" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" v-model="mapInProcess.description_en" rows="3" class="form-control" placeholder="Description" />
                                </div>
                            </div>
                        </div>
                        <div v-else-if="modal === 2">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    순번
                                </div>
                                <div class="col-md-9">
                                    <select id="order" class="form-control" v-model="venueInProcess.order" required>
                                        <option v-for="n in maps[currentMapOrder].venues.length + 1" :key="n">{{ n }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" v-model="venueInProcess.title" class="form-control" placeholder="제목" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" v-model="venueInProcess.title_en" class="form-control" placeholder="Title" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <textarea v-model="venueInProcess.description" rows="3" class="form-control" placeholder="상세내용"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <textarea v-model="venueInProcess.description_en" rows="3" class="form-control" placeholder="Description"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" v-model="venueInProcess.coordinates" class="form-control" placeholder="구글지도 위치정보" />
                            </div>
                            <div class="form-group">
                                <input type="text" v-model="venueInProcess.phone" class="form-control" placeholder="전화번호 (선택사항)" />
                            </div>
                            <div class="form-group">
                                <input type="text" v-model="venueInProcess.address" class="form-control" placeholder="주소 (선택사항)" />
                            </div>
                            <div class="form-group">
                                <textarea v-model="venueInProcess.geo_json" rows="3" class="form-control" placeholder="GeoJson (선택사항)"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <input type="text" v-model="venueInProcess.url" class="form-control" placeholder="동선지도 주소 (선택사항)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="modal === 1 && currentMapId === 0" type="button" class="btn btn-primary" @click="onMapAdd">지도 생성</button>
                        <button v-else-if="modal === 1 && currentMapId > 0" type="button" class="btn btn-primary" @click="onMapEdit">지도 수정</button>
                        <button v-else-if="modal === 2 && currentVenueId === 0" type="button" class="btn btn-primary" @click="onVenueAdd">베뉴 생성</button>
                        <button v-else-if="modal === 2 && currentVenueId > 0" type="button" class="btn btn-primary" @click="onVenueEdit">베뉴 수정</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
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
                modal: 0, // 1 = map, 2 = item
                currentMapOrder: 0,
                currentMapId: 0,
                currentVenueId: 0,

                mapInProcess: null,
                venueInProcess: null,

                maps: [],
            };
        },
        mounted() {
            if (this.input) {
                this.maps = this.input;
            }
        },
        methods: {
            async fetchMap() {
                const { data } = await axios.get(`/admin/api/products/${this.product_id}/maps/${this.currentMapId}`);
                return this.mapInProcess = data;
            },
            async fetchVenue() {
                const { data } = await axios.get(`/admin/api/maps/${this.currentMapId}/venues/${this.currentVenueId}`);
                return this.venueInProcess = data;
            },

            // --------------------------------------------------------------------------------

            onPopupClose() {
                this.modal = 0;
            },
            onMapPopup(mapId = 0) {
                this.currentMapId = mapId;

                if (mapId === 0) {
                    this.mapInProcess = {
                        day: this.maps.length + 1,
                        title: '',
                        description: '',
                        title_en: '',
                        description_en: '',
                    };
                    this.modal = 1;
                } else {
                    this.fetchMap().then(() => {
                        this.modal = 1;
                    });
                }
            },
            onVenuePopup(id, mapId, venueId = 0) {
                this.currentMapOrder = id;
                this.currentMapId = mapId;
                this.currentVenueId = venueId;

                if (venueId === 0) {
                    this.venueInProcess = {
                        order: this.maps[id].venues.length + 1,
                        title: '',
                        description: '',
                        title_en: '',
                        description_en: '',
                        coordinates: '',
                        phone: null,
                        address: null,
                        geo_json: null,
                        url: null,
                    };
                    this.modal = 2;
                } else { // retrieve current item
                    this.fetchVenue().then(() => {
                        this.modal = 2;
                    });
                }
            },

            // --------------------------------------------------------------------------------

            onMapAdd() {
                axios.post(`/admin/api/products/${this.product_id}/maps`, this.mapInProcess)
                    .then(({data}) => {
                        this.maps = data;
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
                        $('#m-modal').modal('hide');
                        this.onPopupClose();
                    })
                ;
            },
            onMapEdit() {
                axios.put(`/admin/api/products/${this.product_id}/maps/${this.currentMapId}`, this.mapInProcess)
                    .then(({data}) => {
                        this.maps = data;
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
                        $('#m-modal').modal('hide');
                        this.onPopupClose();
                    })
            },
            onMapDelete(id) {
                axios.delete(`/admin/api/products/${this.product_id}/maps/${id}`)
                    .then(({data}) => {
                        this.maps = data;
                        flash('성공적으로 삭제했습니다.');
                    })
                    .catch(({ response }) => {
                        flash(response.data, 'danger');
                    })
            },

            // --------------------------------------------------------------------------------

            onReloadMaps() {
                axios.get(`/admin/api/products/${this.product_id}/maps`)
                    .then(({data}) => {
                        this.maps = data
                    });
            },

            onVenueAdd() {
                axios.post(`/admin/api/maps/${this.currentMapId}/venues`, this.venueInProcess)
                    .then(({data}) => {
                        this.onReloadMaps();
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
                        $('#m-modal').modal('hide');
                        this.onPopupClose();
                    })
            },
            onVenueEdit() {
                axios.put(`/admin/api/maps/${this.currentMapId}/venues/${this.currentVenueId}`, this.venueInProcess)
                    .then(({data}) => {
                        this.onReloadMaps();
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
                        $('#m-modal').modal('hide');
                        this.onPopupClose();
                    })
            },
            onVenueDelete(map, venue) {
                axios.delete(`/admin/api/maps/${map}/venues/${venue}`)
                    .then(() => {
                        this.onReloadMaps();
                        flash('베뉴가 삭제되었습니다.');
                    })
                    .catch(({ response }) => {
                        flash(response.data, 'danger');
                    })
            },

        }
    }
</script>

