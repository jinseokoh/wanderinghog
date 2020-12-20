<template>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>여행지 정보(안내문/경고문)</div>
            <button class="btn btn-sm btn-outline-dark" data-toggle="modal" data-target="#r-modal" @click="onReminderPopup(0)">정보 생성</button>
        </div>
        <div v-if="reminders.length < 1" class="card-body">
            <span>정보 내용이 없습니다.</span>
        </div>
        <div v-else class="card-body">
            <div v-for="(reminder, i) in reminders" :key="i" class="card text-white bg-secondary" :class="i < reminders.length - 1 ? 'mb-3' : ''">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span>정보 #{{ i + 1 }}</span>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-danger" @click="onReminderDelete(reminder.id)">삭제</button>
                        <button class="btn btn-sm btn-dark" data-toggle="modal" data-target="#r-modal" @click="onReminderPopup(reminder.id)">수정</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">제목</div>
                        <div class="col-5">
                            {{ reminder.title.ko }}
                        </div>
                        <div class="col-5">
                            {{ reminder.title.en }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-2">설명</div>
                        <div class="col-5">
                            {{ reminder.description.ko }}
                        </div>
                        <div class="col-5">
                            {{ reminder.description.en }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" v-show="modal" id="r-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">정보</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="reminderInProcess">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" v-model="reminderInProcess.title" class="form-control" placeholder="제목" />
                            </div>
                            <div class="col-md-6">
                                <input type="text" v-model="reminderInProcess.title_en" class="form-control" placeholder="Title" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <textarea v-model="reminderInProcess.description" rows="5" class="form-control" placeholder="상세내용"></textarea>
                            </div>
                            <div class="col-md-6">
                                <textarea v-model="reminderInProcess.description_en" rows="5" class="form-control" placeholder="Description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="currentReminderId === 0" type="button" class="btn btn-primary" @click="onReminderAdd">정보 생성</button>
                        <button v-else type="button" class="btn btn-primary" @click="onReminderEdit">정보 수정</button>
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
                modal: 0, // could be false
                currentReminderId: 0,
                reminderInProcess: null,
                reminders: [],
            };
        },
        created() {
            if (this.input) {
                this.reminders = this.input;
            }
        },
        methods: {
            async findReminder() {
                const { data } = await axios.get(`/admin/api/products/${this.product_id}/reminders/${this.currentReminderId}`);
                return this.reminderInProcess = data;
            },

            // --------------------------------------------------------------------------------

            onPopupClose() {
                this.modal = 0;
            },
            onReminderPopup(reminderId = 0) {
                this.currentReminderId = reminderId;

                if (reminderId === 0) {
                    this.reminderInProcess = {
                        day: this.reminders.length + 1,
                        title: '',
                        description: '',
                        title_en: '',
                        description_en: '',
                    };
                    this.modal = 1;
                } else {
                    this.findReminder().then(() => {
                        this.modal = 1;
                    });
                }
            },
            onReload() {
                axios.get(`/admin/api/products/${this.product_id}/reminders`)
                    .then(({data}) => {
                        this.reminders = data
                    });
            },

            // --------------------------------------------------------------------------------

            onReminderAdd() {
                axios.post(`/admin/api/products/${this.product_id}/reminders`, this.reminderInProcess)
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
                        $('#r-modal').modal('hide');
                        this.onPopupClose();
                    })
                ;
            },
            onReminderEdit() {
                axios.put(`/admin/api/products/${this.product_id}/reminders/${this.currentReminderId}`, this.reminderInProcess)
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
                        $('#r-modal').modal('hide');
                        this.onPopupClose();
                    })
            },
            onReminderDelete(id) {
                axios.delete(`/admin/api/products/${this.product_id}/reminders/${id}`)
                    .then(() => {
                        this.onReload();
                        flash('성공적으로 삭제했습니다.');
                    })
                    .catch(({ response }) => {
                        flash(response.data, 'danger');
                    })
            },
        }
    }
</script>

