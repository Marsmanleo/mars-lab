<template>
    <view class="pb-smart-verify">
        <button class="btn btn-block btn-round" :size="size" :style="{height:height}" :disabled="loading"
                @click="send"
                v-if="status==='init'">
            {{ sendText }}
        </button>
        <button class="btn btn-block btn-round" :size="size" :style="{height:height}" :disabled="true"
                v-if="status==='sent'">
            {{ count }}秒
        </button>
        <button class="btn btn-block btn-round" :size="size" :style="{height:height}" :disabled="loading"
                v-if="status==='retry'"
                @click="send">
            重新获取
        </button>
    </view>
</template>

<script>
export default {
    name: 'c-smart-verify',
    props: {
        sendText: {
            type: String,
            default: '点击获取'
        },
        src: {
            type: String,
            default: ''
        },
        target: {
            type: String,
            default: ''
        },
        captcha: {
            type: String,
            default: ''
        },
        size: {
            type: String,
            default: 'large'
        },
        height: {
            type: String,
            default: 'auto'
        },
        captchaData: {
            type: Object,
            default: null
        },
        tipSuccess: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            loading: false,
            status: 'init', // init sent retry
            count: 0,
        }
    },
    mounted() {
    },
    methods: {
        countDown() {
            this.count--
            if (this.count > 0) {
                setTimeout(() => {
                    this.countDown()
                }, 1000)
            } else {
                this.status = 'retry'
            }
        },
        send() {
            this.loading = true
            this.$api.post(this.src, Object.assign({
                target: this.target,
                captcha: this.captcha
            }, this.captchaData), res => {
                this.loading = false
                this.count = 60
                this.status = 'sent'
                this.countDown()
                if (this.tipSuccess && res.msg) {
                    this.$dialog.tipSuccess(res.msg)
                }
            }, res => {
                this.loading = false
                this.$emit('error')
            })
        }
    }
}
</script>

<style lang="less">
.pb-smart-verify {
    .btn {
        width: 100%;
    }
}
</style>
