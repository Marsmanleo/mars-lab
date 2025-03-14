<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <c-page-header title="通过手机找回密码"></c-page-header>
    <view class="tw-p-8 ub-content-bg ub-h-full">
        <tui-steps :items="steps"
                   :activeColor="activeColor" spacing="180rpx"
                   :activeSteps="1"/>
        <form>
            <view class="ub-input-simple">
                <view class="input">
                    <input placeholder="输入手机" v-model="data.phone"/>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input with-action">
                    <view class="control">
                        <input placeholder="输入图片验证" v-model="data.captcha"/>
                    </view>
                    <view class="action">
                        <c-smart-captcha ref="captcha" class="captcha" src="retrieve_captcha"/>
                    </view>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input with-action">
                    <view class="control">
                        <input placeholder="输入手机验证码" v-model="data.verify"/>
                    </view>
                    <view class="action">
                        <c-smart-verify class="verify"
                                        src="retrieve_phone_verify"
                                        :target.sync="data.phone"
                                        :captcha.sync="data.captcha"
                                        @error="verifySendError"></c-smart-verify>
                    </view>
                </view>
            </view>
            <view class="ub-input-action">
                <button :loading="isSubmitting" class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">
                    下一步
                </button>
            </view>
        </form>

    </view>
</template>

<script>
import Logo from "./components/Logo";
import {SystemSetting} from "../../../config/setting";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";


export default {
    mixins: [StoreBaseUiMixin],
    components: {Logo},
    onShow() {
        this.$starter.boot()
    },
    data() {
        return {
            isSubmitting: false,
            data: {
                phone: '',
                captcha: '',
                verify: '',
            },
            activeColor: SystemSetting.primaryColor,
            steps: [
                {title: '选择方式', desc: '找回密码方式'},
                {title: '验证身份', desc: '验证本人信息'},
                {title: '重置密码', desc: '重新设定新密码'},
            ]
        };
    },
    methods: {
        verifySendError() {
            this.$refs.captcha.refresh()
        },
        doSubmit() {
            this.isSubmitting = true
            this.$delay(() => {
                this.$api.post('retrieve_phone', this.data, res => {
                    this.$r.to('/brick/module/member/retrieve_reset')
                }, res => {
                    this.isSubmitting = false
                })
            })
        }
    }
}
</script>
<style lang="less" scoped>
@import "./style/style";
</style>
