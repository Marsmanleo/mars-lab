<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="编辑地址" :shadow="false" has-action>
            <block slot="action">
                <view v-if="data.id>0" class="item ub-text-danger tw-pr-1" @click="doDelete">
                    <text class="iconfont icon-trash tw-mr-1" style="font-size:0.7rem;"></text>
                    删除
                </view>
            </block>
        </c-page-header>
        <view class="tw-p-2">
            <view class="ub-content-box">
                <view class="ub-form">
                    <view class="line">
                        <view class="label">姓名</view>
                        <view class="field">
                            <uni-easyinput v-model="data.name" placeholder="输入姓名"></uni-easyinput>
                        </view>
                    </view>
                    <view class="line">
                        <view class="label">手机</view>
                        <view class="field">
                            <view class="tw-flex tw-items-center">
                                <view class="tw-flex-grow">
                                    <uni-easyinput v-model="data.phone" placeholder="输入手机"></uni-easyinput>
                                </view>
                                <!-- #ifdef MP-WEIXIN -->
                                <view class="tw-w-24 tw-ml-2">
                                    <bui-get-phone-number-button
                                        @change="onWechatMiniProgramPhoneNumberChange"/>
                                </view>
                                <!-- #endif -->
                            </view>
                        </view>
                    </view>
                    <view class="line">
                        <view class="label">省市区</view>
                        <view class="field">
                            <bui-area-china-selector v-model="data.area"></bui-area-china-selector>
                        </view>
                    </view>
                    <view class="line">
                        <view class="label">详细地址</view>
                        <view class="field">
                            <uni-easyinput type="textarea" v-model="data.detail" placeholder="输入详细地址"></uni-easyinput>
                        </view>
                    </view>
                    <view class="line">
                        <view class="field">
                            <c-checkbox class="tw-mr-2" v-model="data.isDefault">
                                默认地址
                            </c-checkbox>
                        </view>
                    </view>
                    <view class="tw-p-4">
                        <view class="btn btn-round btn-lg btn-block btn-primary" @click="doSubmit">
                            保存
                        </view>
                    </view>
                </view>
            </view>
        </view>
        <tui-loading v-if="loading"/>
    </view>
</template>

<script>
import {RouterStartForCallbackMixin} from "../../components/Common/mixins/router";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "member_address_edit",
    mixins: [RouterStartForCallbackMixin, StoreBaseUiMixin],
    data() {
        return {
            loading: true,
            data: {
                id: 0,
                name: '',
                phone: '',
                area: '',
                detail: '',
                isDefault: false,
            }
        }
    },
    methods: {
        init() {
            this.loading = false
            this.data = Object.assign({}, this.data, this.value)
        },
        doSubmit() {
            this.$dialog.loadingOn()
            this.$api.post('member_address/edit', this.data, res => {
                this.$dialog.loadingOff()
                this.callbackConfirm(null)
            }, res => {
                this.$dialog.loadingOff()
            })
        },
        doDelete() {
            this.$dialog.loadingOn()
            this.$api.post('member_address/delete', {id: this.data.id}, res => {
                this.$dialog.loadingOff()
                this.callbackConfirm(null)
            }, res => {
                this.$dialog.loadingOff()
            })
        },
        // #ifdef MP-WEIXIN
        onWechatMiniProgramPhoneNumberChange(value) {
            this.data.phone = value.phone
        },
        // #endif
    }
}
</script>

