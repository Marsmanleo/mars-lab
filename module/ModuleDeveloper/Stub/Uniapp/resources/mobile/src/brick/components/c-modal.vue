<template>
    <tui-modal :show="visible" :custom="true" :width="width">
        <view class="tw-relative">
            <view class="ub-text-center margin-bottom tw-font-bold tw-text-lg">
                {{ title }}
            </view>
            <view>
                <slot></slot>
            </view>
            <view
                @tap="visible = false"
                class="tw--right-10 tw--top-8 tw-absolute tw-bg-white tw-h-8 tw-leading-8 tw-rounded-full tw-shadow tw-text-center tw-text-lg tw-w-8">
                <text class="iconfont icon-close"></text>
            </view>
        </view>
    </tui-modal>
</template>

<script>
export default {
    name: "c-modal",
    props: {
        title: {
            type: String,
            default: '提示'
        },
        width: {
            type: String,
            default: '84%'
        }
    },
    data() {
        return {
            visible: false,
        }
    },
    methods: {
        show() {
            this.visible = true
        },
        close() {
            this.visible = false
        }
    }
}
</script>

<style scoped>

</style>
