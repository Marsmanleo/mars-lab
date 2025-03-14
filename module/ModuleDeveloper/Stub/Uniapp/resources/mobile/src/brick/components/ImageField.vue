<template>
    <view class="pb-image-field">
        <image class="item" @click="doPreview()" :src="value" :lazy-load="true" mode="aspectFit" />
    </view>
</template>


<script>
    export default {
        name: 'ImageField',
        props: {
            value: {
                type: String,
                default: null,
            }
        },
        methods:{
            doPreview(){
                uni.previewImage({
                    current:0,
                    urls:[this.value]
                })
            }
        }
    }
</script>

<style lang="less" scoped>
    @import "./../../config/theme";
    .pb-image-field{
        overflow: hidden;
        .item{
            width:100rpx;
            height:100rpx;
            border:1px solid #CCC;
            border-radius:3px;
            margin:0 20rpx 20rpx 0;
            background: #FFF;
            display: block;
            float:left;
        }
    }
</style>
