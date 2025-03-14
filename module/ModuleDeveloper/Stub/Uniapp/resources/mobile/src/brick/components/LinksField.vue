<template>
    <view class="pb-links-field">
        <block v-for="(item,itemIndex) in value" :key="itemIndex">
            <navigator class="item" :url="RouterWebUrl(item)">
                <text class="iconfont icon-link"></text>
                {{item}}
            </navigator>
        </block>
    </view>
</template>


<script>
    import {RouterWebUrl} from "../lib/ui";

    export default {
        name: 'LinksField',
        props: {
            value: {
                type: Array,
                default: () => [],
            }
        },
        methods:{
            RouterWebUrl
        }
    }
</script>

<style lang="less" scoped>
    .pb-links-field{
        padding-bottom:20rpx;
        .item{
            word-break: break-all;
            color:var(--color-primary);
        }
    }
</style>
