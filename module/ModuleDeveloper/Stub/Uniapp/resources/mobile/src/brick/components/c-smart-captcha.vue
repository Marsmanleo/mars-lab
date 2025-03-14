<template>
    <image class="pb-smart-captcha" @click="refresh" :style="{height:height}" :src="image"/>
</template>

<script>
import {EventBus} from "../lib/event-bus";

const empty = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAOCAYAAAAbvf3sAAAAH0lEQVQoU2P88ePHfwYSAOOoBiJCazSUiAgkBpJDCQD/sTaxd/eHJAAAAABJRU5ErkJggg=='
export default {
    name: 'c-smart-captcha',
    props: {
        src: {
            type: String,
            default: '',
        },
        height: {
            type: String,
            default: '1.5rem'
        }
    },
    data() {
        return {
            image: empty,
            verified: false
        }
    },
    mounted() {
        EventBus.$on('modstart:captcha.error', this.onCaptchaError)
        this.refresh()
    },
    destroyed() {
        EventBus.$off('modstart:captcha.error', this.onCaptchaError)
    },
    methods: {
        onCaptchaError(e) {
            this.refresh()
        },
        refresh() {
            this.image = empty
            this.$api.post(this.src, {}, res => {
                this.image = res.data.image
            })
        },
    }
}
</script>

<style lang="less" scoped>
.pb-smart-captcha {
    width: 100%;
    cursor: pointer;
    border-radius: 0.25rem;
}
</style>
