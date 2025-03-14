<template>
    <view class="pb-files-field" v-if="value.length>0">
        <block v-for="(item,itemIndex) in value" :key="itemIndex">
            <navigator class="item" :url="RouterWebUrl(item)">
                <text class="iconfont icon-fill222"></text>
                {{item.substring(item.lastIndexOf('/')+1)}}
            </navigator>
        </block>
    </view>
</template>


<script>
    import {RouterWebUrl} from "../lib/ui";

    export default {
        name: 'FilesField',
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
    .pb-files-field{
        padding-bottom:20rpx;
        .item{
            color:var(--color-primary);
        }
    }
</style>
