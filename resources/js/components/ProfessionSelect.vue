<template>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">아바타</th>
            <th scope="col">아이디</th>
            <th scope="col">정보</th>
            <th scope="col">직업분류</th>
            <th scope="col">직업상세</th>
            <th scope="col">메뉴</th>
            <th scope="col">상태</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="user in users" :key="user.id">
            <th scope="row">
                <a :href="userLink">{{ user.id }}</a>
            </th>
            <td>
                <img :src="user.avatar" style="width: 100px; height: 100px;" />
                <img :src="user.avatar" style="width: 100px; height: 100px;" />
            </td>
            <td>{{ user.username }}</td>
            <td>
                <span>{{ user.name }}</span>
                <span>{{ user.gender }}</span>
                <span>{{ user.age }}</span>
            </td>
            <td>
                <div class="form-group custom-select d-block w-100" required>
                    <select v-model="professionType">
                        <!--                        <option disabled value="">Please select one</option>-->
                        <option v-for="option in options" :value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
            </td>
            <td>
                <input type="text" name="profession" v-model="profession" class="form-control" placeholder="상세내용">
            </td>
            <td>
                <button class="btn btn-sm btn-primary" @click="">승인</button>
                <button class="btn btn-sm btn-danger" @click="">거절</button>
            </td>
            <td>
                <span class="badge badge-pill badge-light">비활성</span>
            </td>
        </tr>
        </tbody>
    </table>

</template>

<script>
    export default {
        props: {
            query_string: { type: String, required: true },
            users: { type: Array, required: true },
        },
        data() {
            return {
                options: [
                    { text: '학생', value: 1 },
                    { text: '회사원', value: 2 },
                    { text: '전문직', value: 3 },
                    { text: '의료직', value: 4 },
                    { text: '교육직', value: 5 },
                    { text: '공무원', value: 6 },
                    { text: '예술가', value: 7 },
                    { text: '사업가', value: 8 },
                    { text: '금융직', value: 9 },
                    { text: '연구/기술직', value: 10 },
                    { text: '군인', value: 11 },
                    { text: '기타', value: 12 },
                ],
                id: null,
                avatar: null,
                cardImage: null,
                name: null,
                gender: null,
                age: null,
                profession: null,
                professionType: null,
            }
        },
        mounted () {
            this.id = this.user_id;
            this.avatar = this.user_avatar;
            this.cardImage = this.user_card_image;
            this.name = this.user_name;
            this.gender = this.user_gender;
            this.age = this.user_age;
            this.profession = this.user_profession;
            this.professionType = this.user_profession_type;

            console.log(this.avatar)
        },

        computed: {
            userLink() {
                return `/admin/cards/${ this.user_id }?${ this.query_string }`;
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
