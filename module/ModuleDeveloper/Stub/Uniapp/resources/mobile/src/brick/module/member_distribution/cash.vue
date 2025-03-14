<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-h-full">
        <c-page-header title="提现到钱包"></c-page-header>
        <c-loading v-if="loading" page/>
        <view class="ub-content-bg ub-h-full tw-p-5">
            <view class="ub-form">
                <view class="line">
                    <view class="label">
                        可提现金额
                    </view>
                    <view class="field">
                        <view class="tw-text-lg">
                            {{ distributionUser.bonus }}
                        </view>
                    </view>
                </view>
                <view class="line">
                    <view class="label">
                        提现金额
                    </view>
                    <view class="field">
                        <uni-easyinput v-model="data.bonusCash"></uni-easyinput>
                    </view>
                </view>
            </view>
            <view class="tw-p-3">
                <view class="ub-alert warning">
                    最小提现金额为{{ minCash }}元
                </view>
            </view>
            <view class="tw-p-3">
                <view class="btn btn-lg btn-round btn-primary btn-block" @click="doSubmit">
                    立即申请
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {GlobalEvent} from "../../lib/event";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "cash",
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            loading: true,
            minCash: 0,
            distributionUser: {
                bonus: 0,
            },
            data: {
                bonusCash: '',
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
            this.loading = true
            this.$dialog.loadingOn()
            this.$api.post('member_distribution/cash_get', {}, res => {
                this.loading = false
                this.$dialog.loadingOff()
                this.minCash = res.data.minCash
                this.distributionUser = res.data.distributionUser
                this.data.bonusCash = res.data.distributionUser.bonus
            })
        },
        doSubmit() {
            this.$dialog.loadingOn()
            this.$api.post('member_distribution/cash', this.data, res => {
                this.$dialog.loadingOff()
                this.$dialog.tipSuccess('提现操作成功', () => {
                    GlobalEvent.fire('MemberDistribution.Update')
                    this.$r.back()
                })
            }, res => {
                this.$dialog.loadingOff()
            })
        }
    }
}
</script>

