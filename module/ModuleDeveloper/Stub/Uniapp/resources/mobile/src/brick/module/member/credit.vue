<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-content-bg ub-h-full">
        <c-page-header title="积分" :shadow="false"></c-page-header>
        <view class="tw-p-4">
            <view class="tw-text-center tw-pt-10">
                <text class="iconfont icon-credit ub-text-warning"
                      style="font-size:3rem;line-height:3rem;"></text>
            </view>
            <view class="tw-text-center">
                <view class="ub-text-warning tw-mt-2">
                    <text class="tw-text-3xl">
                        {{ total }}
                    </text>
                </view>
                <view class="tw-pt-2">
                    <text class="ub-text-muted">我的积分</text>
                </view>
            </view>
        </view>
        <view class="tw-pt-20">
            <div class="tw-p-4 tw-px-20" v-if="$hasModule('MemberCreditCharge')">
                <view class="btn btn-warning gradient btn-lg btn-round btn-block"
                      @click="$r.to('/brick/module/member_credit_charge/index')">
                    积分充值
                </view>
            </div>
            <div class="tw-p-4 tw-text-center">
                <view class="tw-inline-block tw-mx-4" @tap="$r.to('/brick/module/member/credit_log')">
                    积分明细
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
            total: '-'
        }
    },
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    methods: {
        init() {
            this.$api.post('member_credit/get', {}, res => {
                this.total = res.data.total
            })
        }
    }
}
</script>

