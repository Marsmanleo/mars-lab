<template>
    <view @click="doSubmit" class="tw-h-10 tw-w-10 tw-leading-6 tw-overflow-hidden tw-text-center tw-relative">
        <view v-if="value" class="iconfont icon-star ub-text-primary" style="font-size:1rem;margin-top:-0.2rem;">
        </view>
        <view v-if="!value" class="iconfont icon-star-alt ub-text-muted" style="font-size:1rem;margin-top:-0.2rem;">
        </view>
        <view class="tw-text-xs tw-leading-2 tw-absolute tw-bottom-0 tw-right-0 tw-left-0"
              :class="{'ub-text-muted':!value,'ub-text-primary':value}">
            {{ count ? count : '收藏' }}
        </view>
    </view>
</template>

<script>
import {StoreUserMixin} from "../../components/Common/mixins/store";

export default {
    name: 'bui-member-fav-btn',
    mixins: [StoreUserMixin],
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        count: {
            type: Number,
            default: 0,
        },
        biz: {
            type: String,
            default: '',
        },
        bizId: {
            type: Number,
            default: 0
        }
    },
    methods: {
        doSubmit() {
            this.requireUserLogin(() => {
                this.$api.post(`member_fav/submit/${this.biz}`, {
                    biz: this.biz,
                    bizId: this.bizId
                }, res => {
                    this.$emit('update', !this.value, {
                        count: res.data.count
                    })
                })
            })
        }
    }
}
</script>

<style>

</style>
