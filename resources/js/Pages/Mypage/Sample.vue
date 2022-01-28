<template>
    <div> 
        <ul>
            <li v-for="error in form.errors" :key="error.id">
                {{ error }}
            </li>
        </ul>
        <form @submit.prevent="send">

            <label>Name</label>
            <input type="text" name="name" v-model="form.name" />
            <label>Email</label>
            <input type="text" name="name" v-model="form.email" />
            <secondary-button :type="submit">
                テストする
            </secondary-button>
        </form>
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import SecondaryButton from '@/Jetstream/SecondaryButton.vue'

    export default defineComponent({
        components: {
            SecondaryButton
        },
        data() {
            return {
                submit: "submit",             
                form: this.$inertia.form({
                    name: "",
                    email: "",
                }),
            }
        },
        methods: {
            send(){
                console.log("send");
                this.form.post('/testpost'); // フォームのnameとemailがパラメータに設定されます
            }
        }
    })
</script>
