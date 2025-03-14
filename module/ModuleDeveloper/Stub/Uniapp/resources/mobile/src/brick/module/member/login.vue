<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <c-page-header title="用户登录" :shadow="false"></c-page-header>
    <view class="tw-p-8 ub-content-bg ub-h-full" v-if="show">
        <view class="ub-input-simple">
            <view class="input">
                <input placeholder="输入用户" v-model="data.username"/>
            </view>
        </view>
        <view class="ub-input-simple">
            <view class="input">
                <input type="password" placeholder="输入密码" v-model="data.password"/>
            </view>
        </view>
        <view v-if="config.loginCaptchaEnable">
            <view v-if="config.loginCaptchaProvider==='tecmz'" class="ub-input-simple">
                <c-tecmz-captcha ref="captcha" @success="onCaptchaData"/>
            </view>
            <view v-else class="ub-input-simple">
                <view class="input with-action">
                    <view class="control">
                        <input placeholder="输入验证码" v-model="data.captcha"/>
                    </view>
                    <view class="action">
                        <c-smart-captcha ref="captcha" class="captcha" src="login_captcha"/>
                    </view>
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
        <Retrieve v-if="!config.retrieveDisable"/>
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
            show: false,
            data: {
                username: '',
                password: '',
                captcha: '',
            }
        };
    },
    onShow() {
        this.$starter.boot(() => {
            if (this.config.Member_LoginPhoneEnable) {
                if ('phone' === this.config.Member_LoginDefault) {
                    this.$r.replace('/brick/module/member/login_phone', this.$r.getQueries())
                    return
                }
            }
            this.show = true
        })
    },
    methods: {
        onCaptchaData(data) {
            this.captchaData = data
        },
        doSubmit() {
            this.isSubmitting = true
            const redirect = this.$r.getQuery('redirect')
            // console.log('redirect',redirect, this.$r.pathWithQueries())
            this.$delay(() => {
                this.$api.post('login', Object.assign(this.data, this.captchaData), res => {
                    EventBus.$emitDelay('UpdateApp', () => {
                        console.log('redirect', redirect)
                        if (redirect) {
                            this.$r.replace(redirect)
                        } else {
                            this.$r.back()
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
