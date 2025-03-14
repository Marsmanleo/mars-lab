<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="钱包提现"></c-page-header>
        <c-loading v-if="loading" page/>
        <view class="tw-p-4 ub-content-bg" v-else>
            <view class="tw-float-right">
                <view class="ub-text-tertiary"
                      @click="$r.to('/brick/module/member/money_cash_log')">
                    <text class="iconfont icon-time"></text>
                    提现记录
                </view>
            </view>
            <view>
                <view class="ub-text-muted" v-if="!info.canCash">
                    钱包余额 {{ info.total }} 元，最少 {{ info.min }} 元可提现
                </view>
            </view>
            <view class="tw-text-sm" v-if="info.canCash">
                提现金额
            </view>
            <view class="tw-flex tw-py-2" v-if="info.canCash">
                <view class="tw-text-3xl tw-align-bottom" style="line-height:3rem;">￥</view>
                <view class="tw-flex-grow tw-align-bottom">
                    <input type="number"
                           style="font-size:2rem;line-height:2rem;border-bottom:1px solid #EEE;height:100%;"
                           placeholder-class="ub-text-muted" v-model="submit.money"/>
                </view>
            </view>
            <view class="ub-text-muted" v-if="info.canCash">
                当前钱包余额 {{ info.total }} 元，
                <text class="ub-text-primary" @click="submit.money=info.total">全部提现</text>
            </view>
            <view class="tw-pt-2" v-if="info.canCash">
                <view class="ub-form vertical">
                    <view class="line">
                        <view class="label">打款方式</view>
                        <view class="field">
                            <c-constant-tag-selector :types="info.types"
                                                     v-model="submit.type"></c-constant-tag-selector>
                        </view>
                    </view>
                    <view class="line">
                        <view class="label">支付宝账号</view>
                        <view class="field">
                            <input v-model="submit.alipayAccount" placeholder-class="ub-text-muted"
                                   placeholder="输入收款支付宝账号"/>
                        </view>
                    </view>
                    <view class="line">
                        <view class="label">支付宝姓名</view>
                        <view class="field">
                            <input v-model="submit.alipayRealname" placeholder-class="ub-text-muted"
                                   placeholder="输入收款支付宝姓名"/>
                        </view>
                    </view>
                    <view class="line">
                        <view class="label">到账金额</view>
                        <view class="field">
                            <view>
                                ￥{{ value }}
                            </view>
                            <view class="help">
                                手续费{{ info.rate }}%
                            </view>
                        </view>
                    </view>
                    <view class="line">
                        <view class="field">
                            <view class="btn btn-primary btn-block btn-lg btn-round" @click="doSubmit">提交</view>
                        </view>
                    </view>
                </view>
            </view>
        </view>
        <view class="ub-panel margin-top" v-if="!!info.desc">
            <view class="head">
                <view class="title">
                    提现说明
                </view>
            </view>
            <view class="body">
                <view class="ub-html" v-html="info.desc"></view>
            </view>
        </view>
    </view>
</template>

<script>
import {
    StoreBaseConfigMixin, StoreBaseUiMixin
} from "../../components/Common/mixins/store.js";

export default {
    name: "money",
    mixins: [StoreBaseConfigMixin, StoreBaseUiMixin],
    data() {
        return {
            loading: true,
            info: {
                total: '-',
                desc: '',
                rate: '',
                min: '',
                canCash: false,
                types: {},
            },
            value: '-',
            lastValue: null,
            submit: {
                type: null,
                money: '',
                alipayRealname: '',
                alipayAccount: '',
            }
        }
    },
    watch: {
        submit: {
            deep: true,
            handler(n, o) {
                if (n.money && n.money !== this.lastValue) {
                    this.lastValue = n.money
                    this.$api.post('member_money/cash/calc', {
                        money: this.lastValue
                    }, res => {
                        this.value = res.data.value
                    })
                }
            }
        }
    },
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    methods: {
        init() {
            this.$api.post('member_money/cash/get', {}, res => {
                this.loading = false
                this.info = res.data
                this.submit.type = res.data.defaultType
            })
        },
        doSubmit() {
            this.$dialog.loadingOn()
            this.$api.post('member_money/cash/submit', this.submit, res => {
                this.$dialog.loadingOff()
                this.$dialog.tipSuccess('提现申请提交成功', () => {
                    this.$r.back()
                })
            }, res => {
                this.$dialog.loadingOff()
            })
        }
    }
}
</script>

<style>
</style>
