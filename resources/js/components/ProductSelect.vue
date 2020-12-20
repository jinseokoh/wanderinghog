<template>
    <div>
        <label for="product_form" class="text-secondary">연결할 여행상품 (다중선택 가능)</label>
        <select
            id="product_form"
            v-model="selections"
            class="form-control"
            size="16"
            multiple
            name="product_ids[]"
        >
            <option
                v-for="item in items"
                :key="item.id"
                :value="item.id"
            >
                ({{ item.id }}) {{ item.title }}
            </option>
        </select>
    </div>
</template>

<script>
    export default {
        props: {
            keyword: {
                type: String,
                default: () => null
            },
            product_ids: {
                type: Array,
                default: () => []
            }
        },
        data() {
            return {
                items: [],
                selections: []
            }
        },
        mounted () {
            const uri = this.keyword ?
                `/admin/api/products?keyword=${this.keyword}` :
                `/admin/api/products`;
            axios.get(uri)
                .then(({data}) => {
                    this.items = data.data
                });

            if (this.product_ids.length) {
                this.selections = this.product_ids;
            }
        },
    }
</script>
