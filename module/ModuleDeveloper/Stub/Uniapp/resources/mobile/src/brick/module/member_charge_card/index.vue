<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="兑换卡" :shadow="false"></c-page-header>
        <c-loading v-if="loading" page/>
        <view v-else class="tw-pt-2 tw-px-2">
            <view class="ub-content-box margin-bottom">
                <view class="tw-px-3">
                    <view class="ub-input-simple">
                        <view class="input">
                            <input placeholder="输入卡密" v-model="data.code"/>
                        </view>
                    </view>
                    <view class="ub-input-simple">
                        <view class="input with-action">
                            <view class="control">
                                <input placeholder="输入验证码" v-model="data.captcha"/>
                            </view>
                            <view class="action">
                                <c-smart-captcha ref="captcha" class="captcha" src="captcha/image"/>
                            </view>
                        </view>
                    </view>
                    <view class="ub-input-action">
                        <view class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">
                            兑换
                        </view>
                    </view>
                </view>
            </view>
            <view class="ub-panel" v-if="contentEnable">
                <view class="head">
                    <view class="title">
                        卡密兑换
                    </view>
                </view>
                <view class="body">
                    <rich-text class="ub-html" :nodes="content || ''"></rich-text>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: 'index',
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            loading: true,
            contentEnable: false,
            content: '',
            data: {
                code: '',
                captcha: '',
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
            this.$api.post('member_charge_card/info', {}, res => {
                this.loading = false
                this.contentEnable = res.data.contentEnable
                this.content = res.data.content
            })
        },
        doSubmit() {
            this.$dialog.loadingOn()
            this.$api.post('member_charge_card/submit', this.data, res => {
                this.$dialog.loadingOff()
                this.$dialog.tipSuccess(res.msg, () => {
                    this.$r.back()
                })
            }, res => {
                this.$dialog.loadingOff()
            })
        }
    }
}
</script>

