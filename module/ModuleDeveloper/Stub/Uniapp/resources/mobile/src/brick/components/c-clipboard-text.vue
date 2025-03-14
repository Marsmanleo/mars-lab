<template>
    <view @click="doClipboard" :class="cls">
        <slot></slot>
    </view>
</template>

<script>
export default {
    name: "c-clipboard-text",
    props: {
        cls: {
            type: String,
            default: 'tw-inline-block'
        },
        text: {
            type: String,
            default: ''
        },
        successText: {
            type: String,
            default: '复制成功'
        }
    },
    methods: {
        doClipboard() {
            uni.setClipboardData({
                data: this.text,
                success: () => {
                    this.$dialog.tipSuccess(this.successText)
                }
            })
        }
    }
}
</script>

