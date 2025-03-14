<template>
    <view>

        <page-meta :root-font-size="ui.rootFontSize"/>
        <c-page-header title="统一支付中心" titleAlign="center"></c-page-header>

        <view class="tw-p-3 tw-bg-white margin-bottom">
            <view class="ub-pair">
                <view class="name">结算ID</view>
                <view class="value">{{ order.id ? order.id : '-' }}</view>
            </view>
            <view class="ub-pair">
                <view class="name">内容</view>
                <view class="value">{{ order.body ? order.body : '-' }}</view>
            </view>
            <view class="ub-pair">
                <view class="name">金额</view>
                <view class="value">
                    <text class="ub-text-warning">￥{{ order.feeTotal ? order.feeTotal : '-' }}</text>
                </view>
            </view>
        </view>

        <view class="tw-p-3 tw-bg-white">
            <view class="ub-text-muted margin-bottom">
                点击支付
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
        </view>
    </view>
</template>

<script>
import {StoreBaseConfigMixin, StoreBaseUiMixin, StoreUserMixin} from "../../components/Common/mixins/store";
import {PayCenterMixin} from "./components/PayCenterScript";

export default {
    name: "pay",
    mixins: [StoreBaseConfigMixin, StoreUserMixin, PayCenterMixin, StoreBaseUiMixin],
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    methods: {
        init() {

            const orderSecretId = this.$r.getQuery('order')
            this.doInit(orderSecretId)

            // check code
            const urlInfo = this.$r.parseH5Url()
            let code = urlInfo.param['code']
            if (!code) {
                code = this.$r.getQuery('code')
            }
            const autoClickPayType = this.$storage.get('autoClickPayType')
            if (code) {
                this.$dialog.loadingOn()
                this.$api.post('oauth/callback', {
                    code,
                    type: this.$storage.get('oauthLoginType'),
                    callback: this.$storage.get('oauthLoginCallback'),
                    callbackMode: 'View'
                }, res => {
                    this.$dialog.loadingOff()
                    console.log('oauth callback', res)
                    this.$r.refreshCleanUrl()
                }, res => {
                    this.$dialog.loadingOff()
                    this.$r.refreshCleanUrl()
                })
            } else {
                if (autoClickPayType) {
                    console.log('auto click', autoClickPayType)
                    this.doSubmit(autoClickPayType)
                } else {
                    this.onPayReady(() => {
                        this.doAutoSubmit()
                    })
                }
            }

        },
    }
}
</script>

<style lang="less" scoped>
.ub-list-pay {
    .item {
        color: var(--theme-text-default-color, --color-text);
    }
}
</style>
