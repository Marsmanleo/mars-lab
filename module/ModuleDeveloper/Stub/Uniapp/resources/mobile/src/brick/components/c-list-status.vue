<template>
    <view class="pb-list-status">
        <view v-if="false">
            loading:{{ loading ? 'true' : 'false' }},
            records.length:{{ records.length }},
            isInit:{{ isInit ? 'true' : 'false' }},
            isNoMore:{{ isNoMore ? 'true' : 'false' }}
        </view>
        <view class="empty" v-if="!simple && !loading && isInit && records.length===0">
            <view class="iconfont icon-empty-box"></view>
            <view class="text">{{ emptyText }}</view>
        </view>
        <view class="loading" v-if="loading && page!==1">
            <text class="iconfont icon-refresh tw-animate-spin tw-inline-block"></text>
            <text>正在加载中...</text>
        </view>
        <view class="no-more" v-if="!loading && isNoMore && records.length>0">
            <text v-if="total<0">没有更多了</text>
            <text v-if="total>=0">总共{{ total }}条记录</text>
        </view>
        <view class="pull-to-load" v-if="!simple && !loading && !isNoMore && isInit" @tap.stop="doLoadMore">
            上拉加载更多
        </view>
    </view>
</template>

<script>
export default {
    name: "c-list-status",
    props: {
        records: {
            type: Array,
            default: () => [],
        },
        loading: {
            type: Boolean,
            default: false,
        },
        total: {
            type: Number,
            default: -1,
        },
        page: {
            type: Number,
            default: -1,
        },
        simple: {
            type: Boolean,
            default: false,
        },
        emptyText: {
            type: String,
            default: '没有记录',
        },
    },
    data() {
        return {
            isInit: false,
            isNoMore: false,
        }
    },
    methods: {
        reset() {
            this.isInit = false
            this.isNoMore = false
        },
        noMore() {
            this.isNoMore = true
        },
        init() {
            this.isInit = true
            this.isNoMore = false
        },
        shouldNext() {
            return !(this.isNoMore || this.loading)
        },
        doLoadMore() {
            this.$emit('on-load-more')
        }
    }
}
</script>

<style lang="less" scoped>
@import "./../../config/theme";

.pb-list-status {
    text-align: center;
    min-height: 3rem;
    background-color: transparent;

    .pull-to-load,
    .no-more,
    .loading,
    .empty {
        text-align: center;
        color: var(--color-muted);
        padding: 1rem 0;
        min-height: 1rem;
        line-height: 1rem;
    }

    .pull-to-load {
    }

    .no-more {
        .iconfont {
            margin-right: 0.5rem;
        }
    }

    .loading {
        .iconfont {
            display: inline-block;
            margin: 0;
            padding: 0;
            margin-right: 0.5rem;
            width: 1rem;
            height: 1rem;
            text-align: center;
            line-height: 1rem;
            -webkit-animation: load-progress-spinner 2s linear infinite;
            animation: load-progress-spinner 2s linear infinite;
        }
    }

    .empty {
        padding: 6rem 0;

        .iconfont {
            font-size: 3rem;
            color: var(--color-muted);
            height: 3rem;
            line-height: 3rem;
        }

        .text {
            padding-top: 1rem;
        }
    }
}

@-webkit-keyframes load-progress-spinner {
    0% {
        -webkit-transform: rotate(0);
        transform: rotate(0);
    }

    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@keyframes load-progress-spinner {
    0% {
        -webkit-transform: rotate(0);
        transform: rotate(0);
    }

    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
</style>
