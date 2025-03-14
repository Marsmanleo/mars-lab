<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <c-page-header title="用户注册" :shadow="false"></c-page-header>
    <view class="tw-p-8 ub-content-bg ub-h-full" v-if="show">
        <form>
            <view class="ub-input-simple">
                <view class="input">
                    <input placeholder="输入用户名" v-model="data.username"/>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input">
                    <input type="password" placeholder="输入密码" v-model="data.password"/>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input">
                    <input type="password" placeholder="重复确认密码" v-model="data.passwordRepeat"/>
                </view>
            </view>
            <view v-if="config.Member_RegisterCaptchaProvider==='tecmz'" class="ub-input-simple">
                <c-tecmz-captcha ref="captcha" @success="onCaptchaData"/>
            </view>
            <view v-else class="ub-input-simple">
                <view class="input with-action">
                    <view class="control">
                        <input placeholder="输入图片验证码" @blur="blurCaptcha" @focus="focusCaptcha" v-model="data.captcha"/>
                    </view>
                    <view class="action">
                        <c-smart-captcha ref="captcha" class="captcha" src="register_captcha"/>
                    </view>
                </view>
                <div class="help">
                    <view class="ub-text-muted" v-if="captchaVerify===''">
                        <text class="iconfont icon-warning tw-mr-1"></text>
                        输入图片验证码完成校验
                    </view>
                    <view class="ub-text-success" v-if="captchaVerify==='pass'">
                        <text class="iconfont icon-check tw-mr-1"></text>
                        图片验证码验证通过
                    </view>
                    <view class="ub-text-danger" v-if="captchaVerify==='error'">
                        <text class="iconfont icon-close tw-mr-1"></text>
                        图片验证码验证错误
                    </view>
                </div>
            </view>
            <view class="ub-input-simple" v-if="config.registerPhoneEnable">
                <view class="input">
                    <input placeholder="输入手机号" v-model="data.phone"/>
                </view>
            </view>
            <view class="ub-input-simple" v-if="config.registerPhoneEnable">
                <view class="input with-action">
                    <view class="control">
                        <input placeholder="输入手机验证" v-model="data.phoneVerify"/>
                    </view>
                    <view class="action">
                        <c-smart-verify class="verify" src="register_phone_verify" :target.sync="data.phone"
                                        :captcha.sync="data.captcha" @error="verifySendError"></c-smart-verify>
                    </view>
                </view>
            </view>
            <view class="ub-input-simple" v-if="config.registerEmailEnable">
                <view class="input">
                    <input placeholder="输入邮箱" v-model="data.email"/>
                </view>
            </view>
            <view class="ub-input-simple" v-if="config.registerEmailEnable">
                <view class="input with-action">
                    <view class="control">
                        <input placeholder="输入邮箱验证" v-model="data.emailVerify"/>
                    </view>
                    <view class="action">
                        <c-smart-verify class="verify" src="register_email_verify" :target.sync="data.email"
                                        :captcha.sync="data.captcha" @error="verifySendError"></c-smart-verify>
                    </view>
                </view>
            </view>
            <view class="ub-input-action">
                <button :loading="isSubmitting" class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">
                    注册
                </button>
            </view>
            <view v-if="config.Member_AgreementEnable||config.Member_PrivacyEnable">
                <c-checkbox class="tw-mr-2" v-model="data.agreement"/>
                同意
                <c-smart-link class="ub-text-primary" to="/brick/module/member/doc?type=agreement"
                              v-if="config.Member_AgreementEnable">
                    《{{ config.Member_AgreementTitle }}》
                </c-smart-link>
                <c-smart-link class="ub-text-primary" to="/brick/module/member/doc?type=privacy"
                              v-if="config.Member_PrivacyEnable">
                    《{{ config.Member_PrivacyTitle }}》
                </c-smart-link>
            </view>
        </form>
        <Oauth v-if="!config.registerDisable || config.registerOauthEnable"/>
    </view>
</template>

<script>
import Logo from "./components/Logo";
import Oauth from "./components/Oauth";
import Retrieve from "./components/Retrieve";
import {StoreMixin} from "../../store/mixin";
import {SystemSetting} from "../../../config/setting";
import {StoreBaseConfigMixin, StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    components: {
        Retrieve,
        Oauth,
        Logo,
    },
    mixins: [StoreMixin, StoreBaseConfigMixin, StoreBaseUiMixin],
    data() {
        return {
            captchaData: null,
            isSubmitting: false,
            show: false,
            data: {
                agreement: false,
                username: '',
                captcha: '',
                phone: '',
                phoneVerify: '',
                email: '',
                emailVerify: '',
                password: '',
                passwordRepeat: '',
            },
            captchaVerifying: false,
            captchaVerify: '',
        };
    },
    onShow() {
        this.$starter.boot(() => {
            if (this.config.Member_RegisterPhoneEnable) {
                if (this.config.Member_RegisterDefault === 'phone') {
                    this.$r.replace('/brick/module/member/register_phone')
                    return;
                }
            }
            this.show = true
        })
    },
    methods: {
        onCaptchaData(data) {
            this.captchaData = data
            this.blurCaptcha()
        },
        focusCaptcha() {
            this.captchaVerify = ''
            this.captchaVerifying = true
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
        },
        doSubmit() {
            if (this.captchaVerifying) {
                setTimeout(() => {
                    this.doSubmit()
                }, 100)
                return
            }
            this.isSubmitting = true
            this.$delay(() => {
                this.$api.post('register', this.data, res => {
                    this.$dialog.tipSuccess('注册成功，请登录', () => {
                        this.$r.replace(SystemSetting.route.login)
                    });
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
