<template>
    <view class="pb-images-field">
        <block v-for="(item,itemIndex) in value" :key="itemIndex">
            <image class="item" @click="doPreview(itemIndex)" :src="item" :lazy-load="true" mode="aspectFit" />
        </block>
    </view>
</template>


<script>
    export default {
        name: 'ImagesField',
        props: {
            value: {
                type: Array,
                default: () => [],
            }
        },
        methods:{
            doPreview(index){
                uni.previewImage({
                    current:index,
                    urls:this.value
                })
            }
        }
    }
</script>

<style lang="less" scoped>
    @import "./../../config/theme";
    .pb-images-field{
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
