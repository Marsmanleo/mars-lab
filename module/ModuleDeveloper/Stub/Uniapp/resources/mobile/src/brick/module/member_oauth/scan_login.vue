<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view style="min-height:100vh;" class="tw-bg-white">
        <c-page-header title="扫码登录" :has-back="false"/>
        <view v-if="loading">
            <view class="ub-text-muted tw-py-10 tw-text-center">
                加载中...
            </view>
        </view>
        <view v-if="logined">
            <view class="tw-text-center tw-py-10">
                <view class="ub-text-success">
                    <text class="iconfont icon-check" style="font-size:4rem;"></text>
                </view>
                <view class="">
                    登录成功，请关闭此页面
                </view>
            </view>
        </view>
        <view v-if="!logined && !loading">
            <view v-if="!scene" class="tw-text-center tw-py-10">
                <view class="ub-text-danger">
                    <text class="iconfont icon-warning" style="font-size:4rem;"></text>
                </view>
                <view class="ub-text-danger">
                    您访问的页面出现异常，请联系管理员
                </view>
            </view>
            <view v-else class="tw-text-center tw-py-20 tw-px-10">
                <view class="tw-rounded-full tw-m-auto tw-text-white tw-bg-gray-300" style="width:4rem;height:4rem;">
                    <view style="padding-top:0.8rem;">
                        <text class="iconfont icon-wechat" style="font-size:2.5rem;line-height:2.5rem;"></text>
                    </view>
                </view>
                <view class="ub-text-muted tw-mt-5">
                    将使用微信登录
                </view>
                <view>
                    <button @tap="doWechatMiniProgramGetUserInfo" style="border:none;"
                            class="btn btn-primary btn-block btn-lg tw-mt-5">
                        确定登录
                    </button>
                    <navigator class="tw-text-center ub-text-primary tw-mt-5" open-type="exit" target="miniProgram">
                        取消
                    </navigator>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: 'scan_login',
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            loading: true,
            scene: null,
            logined: false,
        }
    },
    onReady() {
        this.$starter.boot(() => {
            this.scene = this.$r.getQuery('scene')
            // #ifdef MP-WEIXIN
            this.init()
            // #endif
        })
    },
    methods: {
        init() {
            this.loading = false
        },
        doWechatMiniProgramGetUserInfo() {
            wx.login({
                success: (loginRes) => {
                    console.log('doWechatMiniProgramGetUserInfo.login', loginRes)
                    // console.log('loginRes', loginRes)
                    this.$api.post('oauth/login_wechat_mini_program', {
                        code: loginRes.code,
                        scene: this.scene,
                    }, res => {
                        this.$dialog.loadingOff()
                        this.$dialog.tipSuccess('登录成功')
                        this.logined = true
                    }, res => this.$dialog.loadingOff())
                },
                fail: (loginRes) => {
                    this.$dialog.tipError('登录微信获取信息失败')
                    console.error('doWechatMiniProgramInit', loginRes)
                }
            })
        },
        doCancel() {
        }
    }
}
</script>

<style>
</style>
