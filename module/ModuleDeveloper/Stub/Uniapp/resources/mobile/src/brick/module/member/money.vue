<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="pb-module-member-money ub-content-bg ub-h-full">
        <c-page-header title="钱包" :shadow="false"></c-page-header>
        <view class="tw-p-4">
            <view class="tw-text-center tw-pt-10">
                <text class="iconfont icon-pay ub-text-danger"
                      style="font-size:3rem;line-height:3rem;"></text>
            </view>
            <view class="tw-text-center">
                <view class="ub-text-danger tw-mt-2">
                    <text class="tw-text-3xl" v-if="!hide">
                        ￥{{ total }}
                    </text>
                    <text class="tw-text-3xl" v-if="hide">
                        ￥******
                    </text>
                </view>
                <view class="tw-pt-2">
                    <text class="ub-text-muted">账户余额</text>
                    <text class="iconfont icon-eye tw-inline-block tw-ml-2" @click="hide=!hide"></text>
                </view>
            </view>
        </view>
        <view class="tw-pt-20">
            <div class="tw-p-4 tw-px-20" v-if="config.Member_MoneyChargeEnable">
                <view class="btn btn-danger gradient btn-lg btn-round btn-block"
                      @click="$r.to('/brick/module/member/money_charge')">
                    充值
                </view>
            </div>
            <div class="tw-p-4 tw-text-center">
                <view class="tw-inline-block tw-mx-4" @tap="$r.to('/brick/module/member/money_log')">
                    资金明细
                </view>
                <view v-if="config.Member_MoneyCashEnable"
                      class="tw-inline-block tw-mx-4"
                      @click="$r.to('/brick/module/member/money_cash')">
                    钱包提现
                </view>
            </div>
        </view>
    </view>
</template>

<script>
import {
    StoreBaseConfigMixin, StoreBaseUiMixin
} from "../../components/Common/mixins/store.js";

export default {
    name: "money",
    mixins: [StoreBaseConfigMixin, StoreBaseUiMixin],
    data() {
        return {
            hide: false,
            total: '*****',
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
        }
    }
}
</script>

