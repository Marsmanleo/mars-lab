<template>
    <view>
        <view v-if="data.status&&data.status!==MemberDistributionUserStatus.VERIFY_SUCCESS.value"
              class="ub-alert warning">
            <text class="iconfont icon-warning tw-mr-2"></text>
            {{ data.statusRemark }}
        </view>
        <view class="ub-form">
            <view class="line">
                <view class="label">
                    姓名
                </view>
                <view class="field">
                    <uni-easyinput v-model="data.name"></uni-easyinput>
                </view>
            </view>
            <view class="line">
                <view class="label">
                    手机号
                </view>
                <view class="field">
                    <uni-easyinput v-model="data.phone"></uni-easyinput>
                </view>
            </view>
        </view>
        <view class="tw-p-3">
            <c-checkbox v-model="data.agree">
                阅读并同意
            </c-checkbox>
            <text class="ub-text-primary tw-align-middle"
                  @click="$r.to('/brick/module/member_distribution/agreement')">
                《用户分销协议》
            </text>
        </view>
        <view class="tw-p-3">
            <view class="btn btn-lg btn-round btn-primary btn-block" @click="doSubmit">
                立即申请
            </view>
        </view>
    </view>
</template>

<script>
import {MemberDistributionUserStatus} from "../constant";

export default {
    name: "Apply",
    data() {
        return {
            MemberDistributionUserStatus,
            loading: true,
            data: {
                name: '',
                phone: '',
                status: 0,
                statusRemark: '',
                agree: false
            }
        }
    },
    mounted() {
        this.$api.post('member_distribution/apply_get', {}, res => {
            this.loading = false
            if (res.data.distributionUser) {
                this.data.name = res.data.distributionUser.name
                this.data.phone = res.data.distributionUser.phone
                this.data.status = res.data.distributionUser.status
                this.data.statusRemark = res.data.distributionUser.statusRemark
            }
        })
    },
    methods: {
        doSubmit() {
            if (!this.data.agree) {
                this.$dialog.tipError('请先阅读并同意《用户分销协议》')
                return
            }
            this.loading = true
            this.$dialog.loadingOn()
            this.$api.post('member_distribution/apply', this.data, res => {
                this.loading = false
                this.$dialog.loadingOff()
                this.$dialog.tipSuccess(res.msg)
                this.$emit('update')
            }, res => {
                this.loading = false
                this.$dialog.loadingOff()
            })
        }
    }
}
</script>

