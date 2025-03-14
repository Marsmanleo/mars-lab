<template>
    <view class="pb-bui-notice-latest tw-bg-yellow-100 tw-text-yellow-600">
        <view class="iconfont icon-bell ub-text-warning"></view>
        <view class="tw-flex-grow" v-if="loading">
            加载中...
        </view>
        <swiper v-else vertical autoplay circular interval="3000" class="swiper">
            <swiper-item v-for="(item,index) in records" :key="index" class="swiper-item">
                <view class="notice-item" @tap="onDetail(item)">{{ item.title }}</view>
            </swiper-item>
        </swiper>
    </view>
</template>

<script>
export default {
    name: "bui-notice-latest",
    data() {
        return {
            loading: true,
            records: [],
        }
    },
    mounted() {
        this.doLoad()
    },
    methods: {
        doLoad() {
            this.loading = true
            this.$api.post('notice/latest', {}, res => {
                this.loading = false
                this.records = res.data.records
            })
        },
        onDetail(item) {
            this.$r.to('/brick/module/notice/index')
        }
    }
}
</script>

<style lang="less" scoped>
.pb-bui-notice-latest {
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
