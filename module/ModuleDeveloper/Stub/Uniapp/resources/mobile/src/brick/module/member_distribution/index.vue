<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-h-full">
        <c-loading v-if="loading" page/>
        <view v-else-if="!distributionUser||distributionUser.status===MemberDistributionUserStatus.VERIFY_FAIL.value">
            <c-page-header title="申请成为分销商" :shadow="false"></c-page-header>
            <view class="ub-content-bg ub-h-full tw-p-5">
                <Apply @update="init"/>
            </view>
        </view>
        <view v-else-if="distributionUser.status===MemberDistributionUserStatus.VERIFYING.value">
            <c-page-header title="分销推广" :shadow="false"></c-page-header>
            <view class="ub-content-bg ub-h-full tw-p-5">
                <view class="tw-text-center tw-py-10">
                    <view>
                        <text class="iconfont icon-warning ub-text-warning"
                              style="font-size:3rem;line-height:5rem;"></text>
                    </view>
                    <view>
                        您的资料正在审核，请耐心等待
                    </view>
                </view>
            </view>
        </view>
        <view v-else>
            <c-page-header-holder title="分销推广"></c-page-header-holder>
            <view class="tw-flex tw-p-5 tw-items-center ub-text-white tw-pb-16"
                  style="background-image:var(--color-primary-gradient-bg);">
                <view class="tw-w-12 tw-mr-4">
                    <c-cover :src="user.avatar" border-radius="50%"></c-cover>
                </view>
                <view class="tw-flex-grow">
                    <view>
                        {{ user.nickname }}
                    </view>
                </view>
                <view @click="$r.back()">
                    <text class="iconfont icon-left"></text>
                    用户中心
                </view>
            </view>
            <view class="tw-p-3 tw--mt-14">
                <view class="ub-content-box margin-bottom">
                    <view class="tw-flex tw-items-center">
                        <div class="tw-flex-grow">
                            <view class="ub-text-muted">
                                可提现收入(元)
                            </view>
                            <view class="tw-text-lg">
                                {{ distributionUser.bonus }}
                            </view>
                        </div>
                        <view>
                            <view class="btn btn-primary btn-round"
                                  @click="$r.to('/brick/module/member_distribution/cash')">
                                去提现
                            </view>
                        </view>
                    </view>
                    <view class="tw-flex tw-text-center tw-mt-4">
                        <view class="">
                            <view class="ub-text-muted">
                                今日收入(元)
                            </view>
                            <view class="tw-text-lg">
                                {{ todayBonus ? todayBonus : '0.00' }}
                            </view>
                        </view>
                        <view class="tw-flex-grow">
                            <view class="ub-text-muted">
                                已提现(元)
                            </view>
                            <view class="tw-text-lg">
                                {{ distributionUser.bonusCashed ? distributionUser.bonusCashed : '0.00' }}
                            </view>
                        </view>
                        <view class="">
                            <view class="ub-text-muted">
                                累积收入(元)
                            </view>
                            <view class="tw-text-lg">
                                {{ distributionUser.bonusTotal ? distributionUser.bonusTotal : '0.00' }}
                            </view>
                        </view>
                    </view>
                </view>
                <view class="ub-content-box margin-bottom tw-text-center">
                    <tui-grid :unlined="true">
                        <tui-grid-item :border="false" :cell="4"
                                       @click="$r.to('/brick/module/member_distribution/order_item')">
                            <view class="tui-grid-icon">
                                <text class="iconfont icon-list-alt"
                                      style="font-size:1.3rem;line-height:2rem;"></text>
                            </view>
                            <text class="tui-grid-label">分销订单</text>
                        </tui-grid-item>
                        <tui-grid-item :border="false" :cell="4"
                                       @click="$r.to('/brick/module/member_distribution/bonus_log')">
                            <view class="tui-grid-icon">
                                <text class="iconfont icon-credit"
                                      style="font-size:1.3rem;line-height:2rem;"></text>
                            </view>
                            <text class="tui-grid-label">收益明细</text>
                        </tui-grid-item>
                        <tui-grid-item :border="false" :cell="4"
                                       @click="$r.to('/brick/module/member_distribution/user')">
                            <view class="tui-grid-icon">
                                <text class="iconfont icon-users"
                                      style="font-size:1.3rem;line-height:2rem;"></text>
                            </view>
                            <text class="tui-grid-label">团队粉丝</text>
                        </tui-grid-item>
                        <tui-grid-item :border="false" :cell="4"
                                       @click="$r.to('/brick/module/member_distribution/share')">
                            <view class="tui-grid-icon">
                                <text class="iconfont icon-share"
                                      style="font-size:1.3rem;line-height:2rem;"></text>
                            </view>
                            <text class="tui-grid-label">邀请好友</text>
                        </tui-grid-item>
                    </tui-grid>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {MemberDistributionUserStatus} from "./constant";
import {StoreBaseUiMixin, StoreUserMixin} from "../../components/Common/mixins/store";
import Apply from "./components/Apply";

export default {
    name: "index",
    mixins: [StoreUserMixin, StoreBaseUiMixin],
    components: {Apply},
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    data() {
        return {
            MemberDistributionUserStatus,
            loading: true,
            distributionUser: null,
            todayBonus: null,
            distributionUrl: null,
        }
    },
    methods: {
        $OnGlobalEvent(name, param) {
            if (name === 'MemberDistribution.Update') {
                this.init()
            }
        },
        init() {
            this.$dialog.loadingOn()
            this.$api.post('member_distribution/info', {}, res => {
                this.loading = false
                this.$dialog.loadingOff()
                this.distributionUser = res.data.distributionUser
                this.todayBonus = res.data.todayBonus
                this.distributionUrl = res.data.distributionUrl
            })
        }
    }
}
</script>

