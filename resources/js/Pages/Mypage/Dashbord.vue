<template>
  <elerning-layout>
    <div>
    <!-- コンテンツ -->
      <h1>ようこそ{{user_name}}さん</h1>
      <h2 class="text-gray-700 text-3xl font-medium">学習状況</h2>
      <div v-for="(score,key) in info" :key="score.id">
        {{key}}
        <div class="flex">
          <div class="relative w-64 m-5 bg-gray-500 h-8">
            <div v-bind:style="{width:score + '%'}" class="aboslute h-full bg-green-300"></div>
          </div>
          <span class="mt-5">{{score}}%</span>
        </div>
      </div>
      <div class="mt-5">
        <h2 class="text-gray-700 text-3xl font-medium">受講完了証明書の取得</h2>
        <div v-if="done">
          全ての講座が完了しました。
          <form @submit.prevent="submit" class="mt-5">
            <jet-button :disabled="finish">
              完了メールを送信する
            </jet-button>
          </form>
        </div>
        <div v-else>
          未完了の講座があります
        </div>
      </div>
    <!-- -->
    </div>
  </elerning-layout>
</template>

<style scoped>

</style>

<script>
    import { defineComponent } from 'vue'
    import ElerningLayout from '@/Layouts/ElerningLayout.vue'
    import JetButton from '@/Jetstream/Button.vue'

    export default defineComponent({
        components: {
          ElerningLayout,
          JetButton,
        },
        props: {
          info : Object,
          done: Boolean,
          user_name: String,
        },
        data() {
            return {
              form: this.$inertia.form(),
              finish: false,
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('course.all.done'), {
                    onSuccess: () => this.finish = true,
                    onError: () => alert("エラーが発生しました"),
                })
            }
        },
    })
</script>
