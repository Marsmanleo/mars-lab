<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <c-page-header title="用户注册" :shadow="false"></c-page-header>
    <view class="tw-p-8 ub-content-bg ub-h-full">
        <view class="ub-input-simple">
            <view class="input">
                <input placeholder="输入手机" v-model="data.phone"/>
            </view>
        </view>
        <view v-if="config.Member_RegisterCaptchaProvider==='tecmz'" class="ub-input-simple">
            <c-tecmz-captcha ref="captcha" @success="onCaptchaData"/>
        </view>
        <view v-else class="ub-input-simple">
            <view class="input with-action">
                <view class="control">
                    <input placeholder="输入验证码" v-model="data.captcha"/>
                </view>
                <view class="action">
                    <c-smart-captcha ref="captcha" class="captcha" src="register_captcha"/>
                </view>
            </view>
        </view>
        <view class="ub-input-simple">
            <view class="input with-action">
                <view class="control">
                    <input placeholder="输入手机验证" v-model="data.verify"/>
                </view>
                <view class="action">
                    <c-smart-verify class="verify" src="register_phone_verify" :target.sync="data.phone"
                                    :captcha="data.captcha"
                                    :captchaData="captchaData"
                                    @error="verifySendError"></c-smart-verify>
                </view>
            </view>
        </view>
        <view v-if="config.Member_RegisterPhonePasswordEnable" class="ub-input-simple">
            <view class="input">
                <input placeholder="设置登录密码" v-model="data.password"/>
            </view>
        </view>
        <view class="ub-input-action">
            <button :loading="isSubmitting" class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">
                注册并登录
            </button>
        </view>
        <view v-if="base.config.Member_AgreementEnable||base.config.Member_PrivacyEnable">
            <c-checkbox class="tw-mr-2" v-model="data.agreement"/>
            同意
            <c-smart-link class="ub-text-primary" to="/brick/module/member/doc?type=agreement"
                          v-if="base.config.Member_AgreementEnable">
                《{{ base.config.Member_AgreementTitle }}》
            </c-smart-link>
            <c-smart-link class="ub-text-primary" to="/brick/module/member/doc?type=privacy"
                          v-if="base.config.Member_PrivacyEnable">
                《{{ base.config.Member_PrivacyTitle }}》
            </c-smart-link>
        </view>
    </view>
</template>

<script>
import {
    EventBus
} from "../../lib/event-bus";
import Oauth from "./components/Oauth";
import Retrieve from "./components/Retrieve";
import {
    StoreMixin
} from "../../store/mixin";
import {StoreBaseConfigMixin, StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    components: {
        Retrieve,
        Oauth,
    },
    mixins: [StoreMixin, StoreBaseConfigMixin, StoreBaseUiMixin],
    data() {
        return {
            isSubmitting: false,
            captchaData: null,
            captchaVerifying: false,
            captchaVerify: '',
            data: {
                phone: '',
                verify: '',
                captcha: '',
                password: '',
            }
        };
    },
    onShow() {
        this.$starter.boot()
    },
    methods: {
        onCaptchaData(data) {
            this.captchaData = data
            this.blurCaptcha()
        },
        blurCaptcha() {
            this.$api.post('register_captcha_verify', Object.assign({
                captcha: this.data.captcha
            }, this.captchaData), res => {
                this.captchaVerify = 'pass'
                this.captchaVerifying = false
            }, (res) => {
                this.captchaVerify = 'error'
                this.captchaVerifying = false
                this.$refs.captcha.refresh()
                return true
            })
        },
        verifySendError() {
            this.$refs.captcha.refresh()
        },
        doSubmit() {
            this.isSubmitting = true
            const redirect = this.$r.getQuery('redirect', '/pages/home')
            // console.log('redirect',redirect, this.$r.pathWithQueries())
            this.$delay(() => {
                this.$api.post('register_phone', Object.assign(this.data, this.captchaData), res => {
                    EventBus.$emitDelay('UpdateApp', () => {
                        console.log('redirect', redirect)
                        if (redirect) {
                            this.$r.replace(redirect)
                        } else {
                            this.$r.relaunch()
                        }
                    })
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
