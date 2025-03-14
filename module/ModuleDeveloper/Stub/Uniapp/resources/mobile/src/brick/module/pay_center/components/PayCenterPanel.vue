<template>
    <tui-drawer mode="bottom" :visible="visible" @close="close">
        <view class="ub-content-bg tw-p-3">

            <view class="tw-flex tw-items-center">
                <view class="tw-flex-grow">
                    统一支付中心
                </view>
                <view>
                    <text class="ub-text-muted iconfont icon-close" @tap="close"></text>
                </view>
            </view>

            <view class="margin-bottom tw-bg-gray-100 tw-bg-white tw-p-3 tw-rounded-lg">
                <view class="tw-flex">
                    <view class="tw-w-16">结算ID</view>
                    <view class="tw-flex-grow tw-text-right">{{ order.id ? order.id : '-' }}</view>
                </view>
                <view class="tw-flex">
                    <view class="tw-w-16">内容</view>
                    <view class="tw-flex-grow tw-text-right">{{ order.body ? order.body : '-' }}</view>
                </view>
                <view class="tw-flex">
                    <view class="tw-w-16">金额</view>
                    <view class="tw-flex-grow tw-text-right">
                        <text class="ub-text-warning">￥{{ order.feeTotal ? order.feeTotal : '-' }}</text>
                    </view>
                </view>
            </view>

            <view class="ub-list-pay">

                <!-- #ifdef H5 -->
                <view v-if="enableH5Alipay"
                      class="item" @tap="doSubmit('alipay')"
                      :class="{active:payType==='alipay'}">
                    <image class="icon" :src="config.siteCDN+'vendor/PayCenter/image/alipay.svg'"/>
                    支付宝
                </view>
                <view
                    v-if="enableH5AlipayWeb"
                    class="item" @tap="doSubmit('alipay_mobile')"
                    :class="{active:payType==='alipay_mobile'}">
                    <image class="icon" :src="config.siteCDN+'vendor/PayCenter/image/alipay.svg'"/>
                    支付宝支付
                </view>
                <view
                    v-if="enableH5WechatMobile"
                    class="item" @tap="doSubmit('wechat_mobile')"
                    :class="{active:payType==='wechat_mobile'}">
                    <image class="icon" :src="config.siteCDN+'vendor/PayCenter/image/wechat.svg'"/>
                    微信支付
                </view>
                <view
                    v-if="enableH5WechatH5"
                    class="item" @tap="doSubmit('wechat_h5')"
                    :class="{active:payType==='wechat_h5'}">
                    <image class="icon" :src="config.siteCDN+'vendor/PayCenter/image/wechat.svg'"/>
                    微信H5支付
                </view>
                <!-- #endif -->

                <!-- #ifdef MP-WEIXIN -->
                <view
                    v-if="enableMpWeixinWechatMiniProgram"
                    class="item" @tap="doSubmit('wechat_mini_program')"
                    :class="{active:payType==='wechat_mini_program'}">
                    <image class="icon" :src="config.siteCDN+'vendor/PayCenter/image/wechat.svg'"/>
                    微信支付
                </view>
                <!-- #endif -->

                <!-- #ifdef APP-PLUS -->
                <view
                    v-if="enableAppWechatApp"
                    class="item" @tap="doSubmit('wechat_app')"
                    :class="{active:payType==='wechat_app'}">
                    <image class="icon" :src="config.siteCDN+'vendor/PayCenter/image/wechat.svg'"/>
                    微信支付
                </view>
                <!-- #endif -->

                <!-- #ifdef H5 -->
                <view v-for="(h,hIndex) in payConfig.h5" :key="h.name"
                      v-if="(null===disabledPayTypes || !disabledPayTypes.includes(h.name))" class="item"
                      @tap="doSubmit(h.name)"
                      :class="{active:payType===h.name}">
                    <image class="icon" :src="config.siteCDN+'vendor/PayCenter/image/money.svg'"/>
                    {{ h.title }}
                </view>
                <!-- #endif -->

                <view
                    v-if="enableMemberMoney"
                    class="item" @tap="doSubmit('member_money')"
                    :class="{active:payType==='member_money'}">
                    <image class="icon" :src="config.siteCDN+'vendor/PayCenter/image/money.svg'"/>
                    余额支付
                    <bui-member-money-total/>
                </view>

            </view>

            <view class="tw-h-10"></view>

        </view>
    </tui-drawer>

</template>

<script>
import {PayCenterMixin} from "./PayCenterScript";
import {StoreBaseConfigMixin, StoreUserMixin} from "../../../components/Common/mixins/store";

export default {
    name: "PayCenterPanel",
    mixins: [StoreBaseConfigMixin, StoreUserMixin, PayCenterMixin],
    data() {
        return {
            visible: false,
            cancel: null,
        }
    },
    methods: {
        show(orderSecretId, cancel) {
            this.doInit(orderSecretId)
            this.visible = true
            this.cancel = cancel
            this.onPayReady(() => {
                this.doAutoSubmit()
            })
        },
        close() {
            this.visible = false
            if (this.cancel) {
                this.cancel()
            }
        },
    },
}
</script>

<style scoped>

</style>
