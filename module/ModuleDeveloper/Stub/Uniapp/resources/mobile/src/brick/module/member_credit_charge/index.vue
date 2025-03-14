<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="积分充值" :shadow="false"></c-page-header>
        <view>
            <view v-for="(item,itemIndex) in records" :key="itemIndex">
                <view class="tw-flex tw-p-4 tw-justify-center tw-m-4 tw-bg-white tw-rounded-lg">
                    <view class="tw-mr-4">
                        <view class="tw-rounded-full tw-w-12 tw-h-12 tw-bg-yellow-400 tw-text-white tw-text-center">
                            <text class="iconfont icon-credit" style="font-size:1.3rem;"></text>
                        </view>
                    </view>
                    <view class="tw-flex-grow">
                        <view>{{ item.title }}</view>
                        <view class="tw-text-lg">￥{{ item.price }}</view>
                    </view>
                    <view class="tw-flex tw-justify-center tw-flex-col">
                        <view class="btn btn-primary btn-round" @click="doSubmit(item.id)">
                            立即充值
                        </view>
                    </view>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: 'home',
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            records: [],
        }
    },
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    methods: {
        init() {
            this.$api.post('member_credit/charge/all', {}, res => {
                this.records = res.data
            })
        },
        doSubmit(chargeId) {
            this.$dialog.loadingOn()
            this.$api.post('member_credit/charge/submit', {
                chargeId,
                redirect: '/pages/member'
            }, res => {
                this.$dialog.loadingOff()
                this.$r.to('/brick/module/pay_center/pay?order=' + res.data.orderSecretId)
            }, res => {
                this.$dialog.loadingOff()
            })
        }
    }
}
</script>

<style>
</style>
