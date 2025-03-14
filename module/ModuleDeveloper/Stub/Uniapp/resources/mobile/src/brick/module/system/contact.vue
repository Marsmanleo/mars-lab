<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="联系客服"/>
        <c-loading v-if="loading" page/>
        <view v-else class="ub-h-full tw-p-2">
            <view class="ub-content-bg tw-rounded-lg tw-p-8">
                <!-- #ifdef MP-WEIXIN -->
                <view class="tw-mb-4">
                    <button class="btn btn-primary btn-lg btn-round btn-block" open-type="contact" @contact="onContact">
                        <text class="iconfont icon-customer tw-mr-1"></text>
                        <text>在线客服咨询</text>
                    </button>
                </view>
                <!-- #endif -->
                <view v-if="data.email" class="tw-flex tw-mb-4">
                    <view class="tw-w-20">
                        <text class="iconfont icon-email tw-w-6 tw-inline-block"></text>
                        邮箱
                    </view>
                    <view class="tw-flex-grow">{{ data.email }}</view>
                </view>
                <view v-if="data.phone" class="tw-flex tw-mb-4">
                    <view class="tw-w-20">
                        <text class="iconfont icon-phone tw-w-6 tw-inline-block"></text>
                        电话
                    </view>
                    <view class="tw-flex-grow">{{ data.phone }}</view>
                </view>
                <view v-if="data.address" class="tw-flex tw-mb-4">
                    <view class="tw-w-20">
                        <text class="iconfont icon-address tw-w-6 tw-inline-block"></text>
                        地址
                    </view>
                    <view class="tw-flex-grow">{{ data.address }}</view>
                </view>
                <view v-if="data.qrcode" class="tw-text-center tw-pt-6 tw-px-10">
                    <c-image preview :src="data.qrcode"/>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "contact",
    mixins: [StoreBaseUiMixin],
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    data() {
        return {
            loading: true,
            data: {
                email: '',
                phone: '',
                address: '',
                qrcode: '',
            }
        }
    },
    methods: {
        onContact(e) {
            console.log('onContact', e)
        },
        init() {
            this.loading = true
            this.$dialog.loadingOn()
            this.$api.post('site/contact', {}, res => {
                this.loading = false
                this.$dialog.loadingOff()
                this.data = res.data
            })
        }
    }
}
</script>
