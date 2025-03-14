<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <c-page-header title="授权登录" :shadow="false"></c-page-header>
    <view class="tw-p-4 ub-content-bg ub-h-full">
        <view v-if="tryLogin">
            <view class="ub-content-box margin-top ub-h-full">
                <view class="tw-py-20">
                    <c-loading text="正在登录"/>
                </view>
            </view>
        </view>
        <view v-else>
            <view class="margin-top ub-text-center">
                <view class="tw-h-24 tw-mx-auto tw-w-24 ub-border tw-p-1 tw-rounded-lg tw-relative">
                    <view class="tw-rounded-lg ub-cover-1-1"
                          :style="{'background-image':'url('+(data.avatar?data.avatar:(avatarUpload?avatarUpload:avatarEmpty))+')'}"></view>
                    <!-- #ifdef MP-WEIXIN -->
                    <view
                        class="tw-absolute tw-bottom-0 tw-left-0 tw-right-0 tw-rounded-b-lg tw-leading-6 tw-bg-black tw-text-white tw-bg-opacity-50">
                        点击修改
                    </view>
                    <button open-type="chooseAvatar" @chooseavatar="onChooseAvatar"
                            class="tw-absolute tw-inset-0 tw-opacity-0"
                    ></button>
                    <!-- #endif -->
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input">
                    <view class="control">
                        <!-- #ifdef H5 -->
                        <input placeholder="请输入用户名" v-model="data.username"/>
                        <!-- #endif -->
                        <!-- #ifdef MP-WEIXIN -->
                        <input type="nickname" placeholder="请输入用户名" v-model="data.username"/>
                        <!-- #endif -->
                    </view>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input with-action">
                    <view class="control">
                        <input placeholder="输入图片验证码" @blur="blurCaptcha" @focus="focusCaptcha" v-model="data.captcha"/>
                    </view>
                    <view class="action">
                        <c-smart-captcha ref="captcha" class="captcha" src="oauth/bind_captcha"/>
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
            <view v-if="config.Member_OauthBindPhoneEnable">
                <view class="ub-input-simple">
                    <view class="input">
                        <input placeholder="输入手机号" v-model="data.phone"/>
                    </view>
                </view>
                <view class="ub-input-simple">
                    <view class="input with-action">
                        <view class="control">
                            <input placeholder="输入手机验证" v-model="data.phoneVerify"/>
                        </view>
                        <view class="action">
                            <c-smart-verify class="verify" src="oauth/bind_phone_verify" :target.sync="data.phone"
                                            :captcha.sync="data.captcha" @error="verifySendError"></c-smart-verify>
                        </view>
                    </view>
                </view>
            </view>
            <view v-if="config.Member_OauthBindEmailEnable">
                <view class="ub-input-simple">
                    <view class="input">
                        <input placeholder="输入邮箱" v-model="data.email"/>
                    </view>
                </view>
                <view class="ub-input-simple">
                    <view class="input with-action">
                        <view class="control">
                            <input placeholder="输入邮箱验证" v-model="data.emailVerify"/>
                        </view>
                        <view class="action">
                            <c-smart-verify class="verify" src="oauth/bind_email_verify" :target.sync="data.email"
                                            :captcha.sync="data.captcha" @error="verifySendError"></c-smart-verify>
                        </view>
                    </view>
                </view>
            </view>

            <view class="submit">
                <button :loading="isSubmitting" class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">
                    确定绑定
                </button>
            </view>
        </view>
    </view>
</template>

<script>
import {EventBus} from "../../lib/event-bus";
import Logo from "./components/Logo";
import Oauth from "./components/Oauth";
import Retrieve from "./components/Retrieve";
import {StoreMixin} from "../../store/mixin";
import {StoreBaseConfigMixin, StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    components: {Retrieve, Oauth, Logo},
    mixins: [StoreMixin, StoreBaseConfigMixin, StoreBaseUiMixin],
    data() {
        return {
            tryLogin: true,
            isSubmitting: false,
            captchaVerify: '',
            captchaVerifying: false,
            data: {
                type: '',
                avatar: '',
                username: '',
                captcha: '',
                phone: '',
                phoneVerify: '',
                email: '',
                emailVerify: '',
            },
            avatarEmpty: null,
            avatarUpload: null,
        };
    },
    onShow() {
        this.$starter.boot(() => {
            this.data.type = this.$storage.get('oauthLoginType')
            this.doOauthCallback()
        })
    },
    methods: {
        doOauthCallback() {
            let code = null
            // #ifdef H5
            const urlInfo = this.$r.parseH5Url()
            code = urlInfo.param['code']
            // #endif
            if (!code) {
                code = this.$r.getQuery('code')
            }
            this.$dialog.loadingOn()
            this.$api.post('oauth/callback', {
                code,
                type: this.data.type,
                callback: this.$storage.get('oauthLoginCallback')
            }, res => {
                this.$dialog.loadingOff()
                this.avatarEmpty = res.data.avatarEmpty
                this.data.avatar = res.data.user.avatar
                this.data.username = res.data.user.username
                this.doTryLogin()
            }, res => {
                this.$dialog.loadingOff()
            })
        },
        doTryLogin() {
            this.$dialog.loadingOn()
            this.$api.post('oauth/try_login', {type: this.data.type}, res => {
                this.$dialog.loadingOff()
                if (res.data.memberUserId) {
                    this.isSubmitting = true
                    this.$dialog.tipSuccess('登录成功，正在跳转', () => {
                        EventBus.$emitDelay('UpdateApp', () => {
                            this.$r.back(this.$storage.get('oauthLoginBackDelta', 1))
                        })
                    })
                } else {
                    this.tryLogin = false
                }
            }, res => {
                this.$dialog.loadingOff()
            })
        },
        doSubmit() {
            if (this.captchaVerifying) {
                setTimeout(() => {
                    this.doSubmit()
                }, 100)
                return
            }
            this.isSubmitting = true
            this.$dialog.loadingOn()
            this.$delay(() => {
                this.$api.post('oauth/bind', this.data, res => {
                    const callback = () => {
                        this.$dialog.loadingOff()
                        EventBus.$emitDelay('UpdateApp', () => {
                            this.$r.back(this.$storage.get('oauthLoginBackDelta', 1))
                        })
                    }
                    if (this.avatarUpload) {
                        this.$api.postUpload('member_profile/avatar', {
                            filePath: this.avatarUpload,
                            name: 'avatar',
                            formData: {
                                type: 'file',
                            }
                        }, res => {
                            callback()
                        }, res => {
                            this.$dialog.loadingOff()
                        })
                    } else {
                        callback()
                    }
                }, res => {
                    this.isSubmitting = false
                })
            })
        },
        focusCaptcha() {
            this.captchaVerify = ''
            this.captchaVerifying = true
        },
        verifySendError() {
        },
        blurCaptcha() {
            this.$api.post('oauth/bind_captcha_verify', {
                captcha: this.data.captcha
            }, res => {
                this.captchaVerify = 'pass'
                this.captchaVerifying = false
            }, (res) => {
                this.captchaVerify = 'error'
                this.captchaVerifying = false
                this.$refs.captcha.refresh()
                return true
            })
        },
        // #ifdef MP-WEIXIN
        onChooseAvatar(e) {
            this.avatarUpload = e.detail.avatarUrl
        }
        // #endif
    }
}
</script>

<style lang="less" scoped>
@import "./style/style";
</style>

