<template>
    <div>
        <div v-if="image">
            <img :src="itemImage" style="width: 120px; height: 120px;" />
            <button type="button" class="btn btn-sm btn-danger" @click="onDelete" style="position: absolute; top: 10px; left: 10px;">삭제</button>
        </div>
        <div v-else>
            <form method="POST" enctype="multipart/form-data">
                <image-upload @loaded="onLoad"></image-upload>
            </form>
        </div>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';
    export default {
        components: { ImageUpload },
        props: {
            item_id: {
                type: Number,
                required: true
            },
            image: {
                type: Object,
                default: null
            }
        },
        data() {
            return {
                itemImage: null,
            };
        },
        mounted() {
            if (this.image) {
                this.itemImage = this.image.thumb;
            }
        },
        methods: {
            onDelete() {
                axios.delete(`/admin/api/items/${this.item_id}/media`)
                    .then(() => {
                        flash('성공적으로 삭제했습니다.');
                        this.$emit('changed');
                    })
                    .catch(error => {
                    flash(error.response.data, 'danger');
                });
            },
            onLoad(upload) {
                this.itemImage = upload.src;
                this.persist(upload.file);
            },
            persist(file) {
                let data = new FormData();
                data.append('file', file);
                axios.post(`/admin/api/items/${this.item_id}/media`, data)
                    .then(() => {
                        flash('성공적으로 저장했습니다.');
                        this.$emit('changed');
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            },
        }
    }
</script>
