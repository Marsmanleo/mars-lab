<template>
    <view v-if="images && images.length>0" class="bui-cover-images">
        <swiper indicator-dots="true" autoplay="autoplay" circular="ture"
                indicator-color="rgba(255,255,255, 0.3)" indicator-active-color="rgba(255,255,255, 0.8)"
                interval="5000" duration="150" easing-function="linear"
                :style="{height:height}">
            <swiper-item v-for="(item, index) in images" :key="index" :style="{height:height}">
                <image v-if="getItemType(item)==='string'" mode="aspectFill" :src="item" :style="{height:height}"></image>
                <image v-if="getItemType(item)==='object'" mode="aspectFill" :src="item.image" :style="{height:height}"></image>
                <view v-if="getItemType(item)==='object'&&item.text" class="tw-absolute tw-bottom-2 tw-left-2 tw-right-2 tw-rounded-lg tw-text-white tw-p-2"
                      style="background:rgba(0,0,0,0.5);">{{ item.text }}</view>
            </swiper-item>
        </swiper>
    </view>
</template>

<script>
export default {
    name: "bui-cover-images",
    props:{
        images:{
            type:Array,
            default:null
        },
        height:{
            type:String,
            default:'750rpx'
        }
    },
    methods: {
        // 小程序直接行内 typeof 会编译成小程序不能识别的函数，所以这里用这个方法
        getItemType(v){
            return typeof(v)
        }
    }
}
</script>

<style lang="less" scoped>
.bui-cover-images swiper, .bui-cover-images swiper-item, .bui-cover-images image {
    width: 100%;
    overflow: hidden;
}
</style>
