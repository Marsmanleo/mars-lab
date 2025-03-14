<template>
    <view>
        <view class="oauth" v-if="base&&base.config&&hasOauth">
            <view class="title">
                您还可以使用以下方式登录
            </view>
            <view class="body">
                <!-- #ifdef H5 -->
                <view v-if="base.config.oauthWechatMobileEnable" @click="doOauth('wechatmobile')" class="item wechat">
                    <text class="iconfont">&#xe62f;</text>
                </view>
                <view v-if="base.config.oauthQQEnable" @click="doOauth('qq')" class="item qq">
                    <text
                        class="iconfont">&#xe603;
                    </text>
                </view>
                <view v-if="base.config.oauthWeiboEnable" @click="doOauth('weibo')" class="item weibo">
                    <text
                        class="iconfont">&#xe61c;
                    </text>
                </view>
                <!-- #endif -->
                <!-- #ifdef MP-WEIXIN -->
                <button @tap="doOauth('wechatminiprogram')"
                        style="border:none;"
                        class="item wechat">
                    <text class="iconfont icon-wechat"></text>
                </button>
                <!-- #endif -->
            </view>
        </view>
    </view>
</template>

<script>
import {StoreMixin} from "../../../store/mixin";
import {OauthScript} from "./OauthScript";

export default {
    name: "Oauth",
    mixins: [StoreMixin, OauthScript],
    computed: {
        hasOauth() {
            // #ifdef H5
            if (this.base.config.oauthWechatMobileEnable) {
                return true
            }
            if (this.base.config.oauthQQEnable) {
                return true
            }
            if (this.base.config.oauthWeiboEnable) {
                return true
            }
            // #endif
            // #ifdef MP-WEIXIN
            return true
            // #endif
            return false
        }
    },
    methods: {
        doOauth(type) {
            this.$dialog.loadingOn()
            // #ifdef H5
            const wl = window.location
            const callback = `${wl.protocol}//${wl.host}${wl.pathname}${wl.hash}`
                .replace(/member\/login(_phone)?/, 'member/oauth_bind')
            this.$api.post('oauth/login', {
                type,
                callback
            }, res => {
                this.$dialog.loadingOff()
                this.$storage.set('oauthLoginType', type)
                this.$storage.set('oauthLoginCallback', callback)
                window.location.href = res.data.redirect
            }, res => {
                this.$dialog.loadingOff()
            })
            // #endif
            // #ifdef MP-WEIXIN
            wx.login({
                success: (loginRes) => {
                    console.log('doOauth.login.ok', loginRes)
                    this.$storage.set('oauthLoginType', type)
                    this.$storage.set('oauthLoginBackDelta', 2)
                    this.$r.to('/brick/module/member/oauth_bind', {code: loginRes.code})
                },
                fail: (loginRes) => {
                    console.error('doOauth.login.fail', loginRes)
                    this.$dialog.loadingOff()
                    this.$dialog.tipError('登录微信获取信息失败')
                }
            })
            // #endif
        },
        // #ifdef MP-WEIXIN
        doWechatMiniProgramLogin() {
            this.doWechatMiniProgramLoginProcess((res) => {
                const redirect = this.$r.getQuery('redirect')
                if (this.base && this.base.config && this.base.config.Member_OauthBindPhoneEnable) {
                    if (!res.data.phoneIsBind) {
                        this.$r.replace('/brick/module/member/profile_phone', {
                            redirect
                        })
                        return
                    }
                }
                if (redirect) {
                    this.$r.replace(redirect)
                } else {
                    this.$r.back()
                }
            })
        },
        // #endif
    }
}
</script>

<style lang="less" scoped>
.oauth {
    padding-top: 30px;
    text-align: center;
    color: var(--color-muted);

    .title {
    }

    .body {
        padding: 20px 0;
    }

    .item {
        display: inline-block;
        width: 50px;
        height: 50px;
        background: #999;
        color: #FFF;
        border-radius: 50%;
        text-align: center;
        margin: 0 15px;

        .iconfont {
            font-size: 30px;
            line-height: 50px;
        }

        &.qq {
            background: #498ad5;
        }

        &.weibo {
            background: #e05244;
        }

        &.wechat {
            background: #00bb29;
        }
    }
}
</style>
