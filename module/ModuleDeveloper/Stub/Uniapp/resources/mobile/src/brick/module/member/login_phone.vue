<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <c-page-header title="用户登录" :shadow="false"></c-page-header>
    <view class="tw-p-8 ub-content-bg ub-h-full">
        <view class="ub-input-simple">
            <view class="input">
                <input placeholder="输入手机" v-model="data.phone"/>
            </view>
        </view>
        <view v-if="config.loginCaptchaProvider==='tecmz'" class="ub-input-simple">
            <c-tecmz-captcha ref="captcha" @success="onCaptchaData"/>
        </view>
        <view v-else class="ub-input-simple">
            <view class="input with-action">
                <view class="control">
                    <input placeholder="输入验证码" v-model="data.captcha"/>
                </view>
                <view class="action">
                    <c-smart-captcha ref="captcha" class="captcha" src="login_phone_captcha"/>
                </view>
            </view>
        </view>
        <view class="ub-input-simple">
            <view class="input with-action">
                <view class="control">
                    <input placeholder="输入手机验证" v-model="data.verify"/>
                </view>
                <view class="action">
                    <c-smart-verify class="verify" src="login_phone_verify" :target.sync="data.phone"
                                    :captcha="data.captcha"
                                    :captchaData="captchaData"
                                    @error="verifySendError"></c-smart-verify>
                </view>
            </view>
        </view>
        <view class="ub-input-action">
            <button :loading="isSubmitting" class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">
                登录
            </button>
        </view>
        <view v-if="!config.registerDisable">
            还没有账号？
            <c-smart-link to="/brick/module/member/register" type="link">立即注册</c-smart-link>
        </view>
        <Oauth v-if="!config.registerDisable || config.registerOauthEnable"/>
        <Retrieve/>
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
            data: {
                phone: '',
                verify: '',
                captcha: '',
            }
        };
    },
    onShow() {
        this.$starter.boot()
    },
    methods: {
        onCaptchaData(data) {
            this.captchaData = data
        },
        verifySendError() {
            this.$refs.captcha.refresh()
        },
        doSubmit() {
            this.isSubmitting = true
            const redirect = this.$r.getQuery('redirect', '/pages/home')
            // console.log('redirect',redirect, this.$r.pathWithQueries())
            this.$delay(() => {
                this.$api.post('login_phone', Object.assign(this.data, this.captchaData), res => {
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
