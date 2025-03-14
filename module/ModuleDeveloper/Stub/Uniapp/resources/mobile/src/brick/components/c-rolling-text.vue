<template>
    <view class="c-rolling-text tw-bg-yellow-100 tw-text-yellow-600">
        <view class="iconfont icon-bell ub-text-warning"></view>
        <swiper vertical autoplay circular interval="3000" class="swiper">
            <swiper-item v-for="(item,index) in values" :key="index" class="swiper-item">
                <view class="notice-item" @tap="onDetail(item)">{{ item.title }}</view>
            </swiper-item>
        </swiper>
    </view>
</template>

<script>
export default {
    name: "c-rolling-text",
    props: {
        values: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        onDetail(item) {
            if (item.url) {
                this.$r.to(item.url)
                return
            }
        }
    }
}
</script>

<style lang="less" scoped>
.c-rolling-text {
    width: 100%;
    box-sizing: border-box;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: nowrap;
    padding: 0 0.25rem;
    border-radius: 0.25rem;

    .iconfont {
        font-size: 0.8rem;
        margin-right: 0.5rem;
        line-height: 1.5rem;
    }

    .swiper {
        font-size: 0.65rem;
        height: 1.25rem;
        flex: 1;
    }

    .swiper-item {
        display: flex;
        align-items: center
    }

    .notice-item {
        line-height: 1.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
}
</style>
