<template>
    <view>
        <video id="player" :src="url"
               class="tw-w-full"
               @error="onVideoErrorCallback" controls></video>
    </view>
</template>

<script>
export default {
    name: "c-video",
    props: {
        url: {
            type: Object,
            default: () => {
                return {};
            }
        }
    },
    methods: {
        onVideoErrorCallback() {
            this.$dialog.tipError('视频播放错误')
        }
    }
}
</script>

<style scoped>

</style>
