<template>
    <view
        class="tw-bg-black tw-bg-opacity-60 tw-fixed tw-flex tw-inset-0 tw-items-center tw-justify-center tw-text-center tw-text-white">
        <view>
            <view>
                <text style="font-size:3rem;" class="iconfont icon-close-o ub-text-danger"></text>
            </view>
            <view>
                {{ text }}
            </view>
        </view>
    </view>
</template>

<script>
export default {
    name: "c-page-error",
    props: {
        text: {
            type: String,
            default: '加载失败'
        }
    },
}
</script>

<style scoped>

</style>
