import {EventBus} from "../../../lib/event-bus";

export const OauthScript = {
    methods: {
        // #ifdef MP-WEIXIN
        doWechatMiniProgramLoginProcess(cb) {
            this.$dialog.loadingOn()
            wx.login({
                success: (loginRes) => {
                    console.log('doWechatMiniProgramLoginProcess.login.ok', loginRes)
                    this.$api.post('oauth/login_wechat_mini_program', {
                        code: loginRes.code,
                    }, res => {
                        console.log('doWechatMiniProgramLoginProcess.api.ok', res)
                        this.$dialog.loadingOff()
                        this.$dialog.tipSuccess('登录成功')
                        EventBus.$emitDelay('UpdateApp', () => {
                            cb && cb(res);
                        })
                    }, res => {
                        console.log('doWechatMiniProgramLoginProcess.api.fail', res)
                        this.$dialog.loadingOff()
                    })
                },
                fail: (loginRes) => {
                    this.$dialog.tipError('登录微信获取信息失败')
                    console.error('doWechatMiniProgramLoginProcess.login.fail', loginRes)
                }
            })
        }
        // #endif
    }
}
