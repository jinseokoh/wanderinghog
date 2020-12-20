<template>
    <div>
        <div v-if="avatar" class="mb-3">
            <img :src="avatar" width="120" height="120" />
        </div>
        <div v-else class="mb-3">
            <img src="data:image/png;base64,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" width="120" height="120" />
        </div>
        <div>
            <form method="POST" enctype="multipart/form-data">
                <image-upload name="avatar" @loaded="onLoad"></image-upload>
            </form>
        </div>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';
    export default {
        components: { ImageUpload },
        props: {
            user: {
                type: Object,
                default: null
            }
        },
        data() {
            return {
                avatar: null
            };
        },
        mounted() {
            if (this.user) {
                this.avatar = this.user.avatar;
            }
        },
        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },
        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },
            persist(avatar) {
                let data = new FormData();
                data.append('avatar', avatar);
                axios.post(`/admin/api/admins/${this.user.id}/avatar`, {id: this.user.id, data})
                    .then(() => flash('Avatar uploaded!'))
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            }
        }
    }
</script>
