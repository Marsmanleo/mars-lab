<template>
    <view>
        <view v-if="page" class="tw-p-4 tw-bg-white">
            <view style="height:100vh;">
                <view class="tw-bg-gray-100 tw-h-8 tw-rounded tw-mb-8" v-for="i in count" :key="i"></view>
            </view>
        </view>
        <view v-else-if="mini" class="ub-empty">
            <view class="tw-inline-block tw-mr-1" :class="spin?'tw-animate-spin':''">
                <text :class="icon"></text>
            </view>
            {{ text }}
        </view>
        <view v-else class="ub-empty">
            <view class="icon">
                <view :class="spin?'tw-animate-spin tw-inline-block':''">
                    <text :class="icon"></text>
                </view>
            </view>
            <view class="text">{{ text }}</view>
        </view>
    </view>
</template>

<script>
export default {
    name: "c-loading",
    props: {
        text: {
            type: String,
            default: '正在加载'
        },
        icon: {
            type: String,
            default: 'iconfont icon-refresh'
        },
        spin: {
            type: Boolean,
            default: true
        },
        mini: {
            type: Boolean,
            default: false
        },
        page: {
            type: Boolean,
            default: false
        },
        count: {
            type: Number,
            default: 8
        }
    },
}
</script>
