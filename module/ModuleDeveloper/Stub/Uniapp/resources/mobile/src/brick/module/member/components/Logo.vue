<template>
    <view class="logo">
        <view class="image">
            <image class="img" mode="aspectFit" :src="config.siteLogo"/>
        </view>
    </view>
</template>

<style lang="less" scoped>
    .logo {
        text-align: center;
        .image {
            padding: 50rpx 0 0 0;
            height: 150rpx;
            .img {
                height: 100rpx;
                width:400rpx;
            }
        }
    }
</style>

<script>
    import {mapState} from 'vuex'
    export default {
        name: 'Logo',
        computed: mapState({
            config: (state) => state.base.config
        }),
    }
</script>
