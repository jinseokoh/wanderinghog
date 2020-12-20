<template>
    <div>
        <label for="category_form" class="text-secondary">지역 카테고리 (다중선택 가능)</label>
        <select
            id="category_form"
            v-model="selections"
            class="form-control"
            size="8"
            multiple
            name="category[]"
        >
            <option
                v-for="(item, i) in items"
                :key="i"
                :value="item.slug"
            >
                {{ indentedName(item.depth, item.name) }}
            </option>
        </select>
    </div>
</template>

<script>
    export default {
        props: {
            categories: {
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
            axios.get('/api/categories')
                .then(({data}) => {
                    this.items = data.data
                });

            if (this.categories.length) {
                this.selections = this.categories;
            }
        },
        methods: {
            indentedName(itemDepth, itemName) {
                let name = '';
                let depth = itemDepth - 1;
                for (let i = 1; i < depth; i++) {
                    name = name + "\xA0\xA0"
                }

                return name + `${depth}) ${itemName}`;
            }
        }
    }
</script>
