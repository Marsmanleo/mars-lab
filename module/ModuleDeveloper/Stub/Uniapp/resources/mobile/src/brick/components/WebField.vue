<template>
    <view class="pb-web-field">
        <navigator class="item" :url="RouterWebUrl(value)">
            <text class="iconfont icon-phone"></text>
            查看网页
        </navigator>
    </view>
</template>


<script>
    import {RouterWebUrl} from "../lib/ui";

    export default {
        name: 'WebField',
        props: {
            value: {
                type: String,
                default: ''
            }
        },
        methods: {
            RouterWebUrl
        }
    }
</script>

<style lang="less" scoped>
    @import "./../../config/theme";
    .pb-web-field {
        padding-bottom: 20rpx;
        .item{
            word-break: break-all;
            color:var(--color-primary);
            line-height:1em;
        }
    }
</style>
