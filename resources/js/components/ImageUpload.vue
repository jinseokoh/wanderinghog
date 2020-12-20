<template>
    <div class="upload btn btn-outline-dark">
        업로드
        <input type="file" :name="name" accept="image/*" @change="onChange">
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                default: 'file'
            }
        },
        methods: {
            onChange(e) {
                if (! e.target.files.length) return;

                let file = e.target.files[0];
                let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = e => {
                    let src = e.target.result;
                    this.$emit('loaded', { src, file });
                };
            }
        }
    }
</script>

<style>
    div.upload {
        position: relative;
    }
    div.upload > input {
        position: absolute;
        width: 80px;
        /*font-size: 50px;*/
        opacity: 0;
        right: 0;
        top: 0;
    }
</style>
