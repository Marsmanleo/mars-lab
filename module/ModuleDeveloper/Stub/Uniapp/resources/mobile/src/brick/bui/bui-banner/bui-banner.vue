<template>
    <view class="bui-banner" :class="{'bui-banner-round':round}">
        <swiper indicator-dots autoplay circular :interval="5000" :duration="150"
                indicator-color="rgba(255, 255, 255, 0.9)" :style="{height:height}"
                indicator-active-color="var(--color-primary)" class="bui-banner-swiper">
            <swiper-item v-for="(item,index) in list" :key="index" class="bui-banner-swiper-item"
                         @click="onClick(item)"
                         :style="{backgroundImage:'url('+item.image+')'}">
            </swiper-item>
        </swiper>
    </view>
</template>

<script>
export default {
    name: "bui-banner",
    props: {
        position: {
            type: String,
            default: 'mHome'
        },
        height: {
            type: String,
            default: '350rpx',
        },
        round: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            list: []
        }
    },
    mounted() {
        this.init()
    },
    methods: {
        onClick(item) {
            if (!item.link) {
                return;
            }
            this.$r.to(item.link, null, true)
        },
        init() {
            this.loading = true
            this.$api.post('banner/get', {
                position: this.position
            }, res => {
                this.loading = false
                this.list = res.data.filter(o => {
                    return o.type === 1
                })
            })
        }
    }
}
</script>

<style lang="less">
.bui-banner {
    &.bui-banner-round {
        border-radius: 0.5rem;

        .uni-swiper-slide-frame {
            border-radius: 0.5rem;
        }

        /deep/ .uni-swiper-wrapper {
            border-radius: 0.5rem;

            .uni-swiper-slides {
                border-radius: 0.5rem;
            }

        }

        .bui-banner-swiper {
            border-radius: 0.5rem;

            .bui-banner-swiper-item {
                border-radius: 0.5rem;
            }
        }
    }

    .bui-banner-swiper {
        .bui-banner-swiper-item {
            background: no-repeat center;
            background-size: cover;
        }
    }
}
</style>
