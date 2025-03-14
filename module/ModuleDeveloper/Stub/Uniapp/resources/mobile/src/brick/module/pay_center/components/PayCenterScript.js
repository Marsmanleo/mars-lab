import {BeanUtil} from "../../../lib/util";
import {AgentUtil} from "../../../lib/ui";

export const PayCenterMixin = {
    data() {
        return {

            ready: {
                config: false,
                order: false,
            },

            payConfigInit: false,
            payConfig: {
                device: 'mobile',
                alipayEnable: false,
                alipayWebEnable: false,
                wechatMobileEnable: false,
                wechatEnable: false,
                memberMoneyEnable: false,
                h5: []
            },
            disabledPayTypes: null,
            payType: '',

            orderSecretId: null,
            order: {
                id: '',
                status: '',
                body: '',
                redirect: '',
                feeTotal: '',
            },
        }
    },
    computed: {
        enableH5Alipay() {
            return this.payConfig.alipayEnable && (null === this.disabledPayTypes || !this.disabledPayTypes.includes('alipay'))
        },
        enableH5AlipayWeb() {
            return this.payConfig.alipayWebEnable && (null === this.disabledPayTypes || !this.disabledPayTypes.includes('alipay_mobile'))
        },
        enableH5WechatMobile() {
            return this.payConfig.wechatEnable && AgentUtil.isWechat() && (null === this.disabledPayTypes || !this.disabledPayTypes.includes('wechat_mobile'))
        },
        enableH5WechatH5() {
            return this.payConfig.wechatEnable && !AgentUtil.isWechat() && (null === this.disabledPayTypes || !this.disabledPayTypes.includes('wechat_h5'))
        },
        enableMpWeixinWechatMiniProgram() {
            return this.payConfig.wechatEnable && (null === this.disabledPayTypes || !this.disabledPayTypes.includes('wechat_mini_program'))
        },
        enableAppWechatApp() {
            return this.payConfig.wechatEnable && (null === this.disabledPayTypes || !this.disabledPayTypes.includes('wechat_app'))
        },
        enableMemberMoney() {
            return this.user.id > 0 && this.payConfig.memberMoneyEnable && (null === this.disabledPayTypes || !this.disabledPayTypes.includes('member_money'))
        }
    },
    methods: {
        doInit(orderSecretId) {

            this.orderSecretId = orderSecretId
            if (!this.payConfigInit) {
                this.payConfigInit = true
                this.$api.post('pay/config', {}, res => {
                    BeanUtil.update(this.payConfig, res.data)
                    this.ready.config = true
                })
            }

            this.$dialog.loadingOn()
            this.$api.post('pay/info', {
                order: this.orderSecretId
            }, res => {
                this.$dialog.loadingOff()
                this.order = res.data.order
                this.disabledPayTypes = res.data.disabledPayTypes
                this.ready.order = true
            }, res => this.$dialog.loadingOff())

        },
        onPayReady(cb) {
            if (this.ready.config && this.ready.order) {
                this.$nextTick(cb)
            } else {
                setTimeout(() => this.onPayReady(cb), 100)
            }
        },
        doAutoSubmit() {
            // console.log('doAutoSubmit')
            let payTypeCount = 0
            // #ifdef H5
            payTypeCount += [
                this.enableH5Alipay,
                this.enableH5AlipayWeb,
                this.enableH5WechatMobile,
                this.enableH5WechatH5,
            ].filter(v => v).length
            // #endif
            // #ifdef MP-WEIXIN
            payTypeCount += [
                this.enableMpWeixinWechatMiniProgram,
            ].filter(v => v).length
            // #endif
            // #ifdef APP-PLUS
            payTypeCount += [
                this.enableAppWechatApp,
            ].filter(v => v).length
            // #endif
            // #ifdef H5
            this.payConfig.h5.forEach(v => {
                if (null === this.disabledPayTypes || !this.disabledPayTypes.includes(v.name)) {
                    payTypeCount++
                }
            })
            // #endif
            payTypeCount += [
                this.enableMemberMoney,
            ].filter(v => v).length
            // console.log('payTypeCount', payTypeCount)
            if (payTypeCount === 1) {
                // 只有一种支付方式，自动提交
                // #ifdef H5
                if (this.enableH5Alipay) {
                    this.doSubmit('alipay')
                } else if (this.enableH5AlipayWeb) {
                    this.doSubmit('alipay_web')
                } else if (this.enableH5WechatMobile) {
                    this.doSubmit('wechat_mobile')
                } else if (this.enableH5WechatH5) {
                    this.doSubmit('wechat_h5')
                }
                // #endif
                // #ifdef MP-WEIXIN
                if (this.enableMpWeixinWechatMiniProgram) {
                    this.doSubmit('wechat_mini_program')
                }
                // #endif
                // #ifdef APP-PLUS
                if (this.enableAppWechatApp) {
                    this.doSubmit('wechat_app')
                }
                // #endif
                // #ifdef H5
                this.payConfig.h5.forEach(v => {
                    if (null === this.disabledPayTypes || !this.disabledPayTypes.includes(v.name)) {
                        this.doSubmit(v.name)
                    }
                })
                // #endif
                if (this.enableMemberMoney) {
                    this.doSubmit('member_money')
                }
            }
        },
        processRedirect(url) {
            // #ifdef H5
            if (url.startsWith('/pages')) {
                this.$r.replace(url)
            } else {
                window.location.href = url
            }
            // #endif
            // #ifdef MP-WEIXIN
            this.$r.replace(url)
            // #endif
            // #ifdef APP-PLUS
            this.$r.replace(url)
            // #endif
        },
        doSubmit(payType) {
            this.payType = payType
            this.$dialog.loadingOn()
            switch (this.payType) {
                case 'wechat_mobile':
                    this.doSubmitWechatMobile()
                    break
                case 'wechat_mini_program':
                    this.doSubmitWechatMiniProgram()
                    break
                case 'wechat_app':
                    this.doSubmitWechatApp()
                    break
                case 'alipay':
                case 'alipay_web':
                case 'alipay_mobile':
                case 'wechat_h5':
                case 'member_money':
                    this.doSubmitCommon()
                    break;
                default:
                    this.doSubmitCommon()
                    break;
            }
        },
        doSubmitCommon() {
            this.$api.post('pay/submit', {
                type: this.payType,
                order: this.orderSecretId
            }, res => {
                this.$dialog.loadingOff()
                const callback = () => {
                    if (res.data.payLink) {
                        this.processRedirect(res.data.payLink)
                    } else if (res.data.redirect) {
                        this.processRedirect(res.data.redirect)
                    }
                }
                if (res.msg) {
                    this.$dialog.tipSuccess(res.msg)
                    setTimeout(callback, 3000)
                } else {
                    callback()
                }
            }, res => {
                this.$dialog.loadingOff()
            })
        },
        doSubmitWechatApp() {
            this.$api.post('pay/submit', {
                type: this.payType,
                order: this.orderSecretId
            }, submitRes => {
                this.$dialog.loadingOff()
                console.log('pay.submit.success', submitRes)
                const payJsSdkConfig = submitRes.data.payJsSdkConfig
                uni.requestPayment({
                    provider: 'wxpay',
                    timeStamp: payJsSdkConfig.timeStamp,
                    nonceStr: payJsSdkConfig.nonceStr,
                    package: payJsSdkConfig.package,
                    signType: payJsSdkConfig.signType,
                    paySign: payJsSdkConfig.paySign,
                    success: (res) => {
                        /* {"errMsg":"requestPayment:ok"} */
                        if (res.errMsg === 'requestPayment:ok') {
                            this.$dialog.tipError('支付成功')
                            this.processRedirect(submitRes.data.successRedirect)
                        } else {
                            this.$dialog.tipError('支付失败：' + JSON.stringify(res))
                        }
                    },
                    fail: (err) => {
                        this.$dialog.tipError('支付取消')
                    }
                })
            }, res => {
                this.$dialog.loadingOff()
                if (res.data && res.data.needOauthLogin) {
                }
            })
        },
        doSubmitWechatMiniProgram() {
            wx.login({
                success: (loginRes) => {
                    // console.log('doWechatMiniProgramInit', loginRes)
                    this.$api.post('pay/submit', {
                        type: this.payType,
                        order: this.orderSecretId,
                        param: {
                            code: loginRes.code
                        }
                    }, (submitRes) => {
                        this.$dialog.loadingOff()
                        console.log('pay.submit.success', submitRes)
                        const payJsSdkConfig = submitRes.data.payJsSdkConfig
                        uni.requestPayment({
                            provider: 'wxpay',
                            timeStamp: payJsSdkConfig.timeStamp,
                            nonceStr: payJsSdkConfig.nonceStr,
                            package: payJsSdkConfig.package,
                            signType: payJsSdkConfig.signType,
                            paySign: payJsSdkConfig.paySign,
                            success: (res) => {
                                /* {"errMsg":"requestPayment:ok"} */
                                if (res.errMsg === 'requestPayment:ok') {
                                    this.$dialog.tipError('支付成功')
                                    this.processRedirect(submitRes.data.successRedirect)
                                } else {
                                    this.$dialog.tipError('支付失败：' + JSON.stringify(res))
                                }
                            },
                            fail: (err) => {
                                this.$dialog.tipError('支付取消')
                            }
                        })
                    }, res => {
                        this.$dialog.loadingOff()
                    })
                },
                fail: (loginRes) => {
                    this.$dialog.tipError('登录微信获取信息失败')
                    console.error('doWechatMiniProgramInit', loginRes)
                }
            })
        },


        doSubmitWechatMobile() {
            this.$storage.remove('autoClickPayType')
            this.$api.post('pay/submit', {
                type: this.payType,
                order: this.orderSecretId
            }, res => {
                this.$dialog.loadingOff()
                // console.log('pay.submit.success', res)
                WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    res.data.payJsSdkConfig,
                    (response) => {
                        if (response.err_msg === "get_brand_wcpay_request:ok") {
                            alert('支付成功');
                            this.processRedirect(res.data.successRedirect)
                        } else if (response.err_msg === 'get_brand_wcpay_request:cancel') {
                            alert('支付已取消');
                        } else {
                            alert('支付失败 ' + JSON.stringify(response));
                        }
                    }
                );
            }, res => {
                this.$dialog.loadingOff()
                if (res.data && res.data.needOauthLogin) {
                    this.$storage.set('autoClickPayType', this.payType)
                    this.$dialog.loadingOn()
                    const callback = this.$r.pathToUrl(this.$r.urlBuild('/brick/module/pay_center/pay', {
                        order: this.orderSecretId,
                    }))
                    this.$api.post('oauth/login', {
                        type: 'wechatmobile',
                        silence: true,
                        callbackMode: 'View',
                        callback
                    }, res => {
                        this.$dialog.loadingOff()
                        this.$storage.set('oauthLoginType', 'wechatmobile')
                        this.$storage.set('oauthLoginCallback', callback)
                        this.processRedirect(res.data.redirect)
                    }, res => {
                        this.$dialog.loadingOff()
                    })
                    return true
                }
            })
        },
    }
}
