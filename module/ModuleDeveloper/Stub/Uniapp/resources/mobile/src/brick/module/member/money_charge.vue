<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-content-bg ub-h-full">
        <c-page-header title="钱包充值"/>
        <view class="tw-p-4">
            <view class="ub-input-simple">
                <view class="input">
                    <input type="number" placeholder="填写充值金额" v-model="data.money"/>
                </view>
                <view class="help">
                    当前余额：
                    <text class="ub-text-primary">￥{{ total }}</text>
                </view>
            </view>
            <view class="ub-input-action">
                <button :loading="isSubmitting" class="btn btn-lg btn-primary btn-block btn-round"
                        @click="doSubmit()">
                    提交充值
                </button>
            </view>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "money_charge",
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            isSubmitting: false,
            total: '-',
            data: {
                money: ''
            }
        }
    },
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    methods: {
        init() {
            this.$api.post('member_money/get', {}, res => {
                this.total = res.data.total
            })
        },
        doSubmit() {
            this.$dialog.loadingOn()
            this.$api.post('member_money/charge/submit', {
                money: this.data.money,
                redirect: this.$r.pathToUrl('/pages/member'),
            }, (res) => {
                this.$dialog.loadingOff()
                this.$r.to(`/brick/module/pay_center/pay?order=${res.data.orderSecretId}`)
            }, res => this.$dialog.loadingOff())
        }
    }
}
</script>
