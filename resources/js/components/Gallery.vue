<template>
    <div class="d-flex justify-content-center">
        <vue-gallery :images="images" :index="index" @close="index = null"></vue-gallery>
        <div
            class="gallery-image"
            v-for="(item, i) in items"
            :key="i"
            @click="index = i"
            :style="{ backgroundImage: 'url(' + item.thumb + ')', width: '200px', height: '200px' }"
        ></div>
    </div>
</template>

<script>
    import VueGallery from 'vue-gallery'

    export default {
        components: {
            'vue-gallery': VueGallery
        },
        computed: {
            images() {
                return this.items.map(i => i.src)
            }
        },
        props: {
            items: { default: [] },
        },
        data () {
            return {
                index: null,
            }
        }
    };
</script>

<style>
    .gallery-image {
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        border: 1px solid #ebebeb;
        margin: 5px;
    }
</style>
