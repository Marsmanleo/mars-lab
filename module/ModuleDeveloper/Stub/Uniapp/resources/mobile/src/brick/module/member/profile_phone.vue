<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>

        <c-page-header :title="(!!user.phone)?'修改手机':'绑定手机'"/>

        <view class="tw-p-4 ub-content-bg ub-h-full" v-if="!!user.phone && !change">
            <view class="ub-m-list ub-box-shadow">
                <view class="ub-m-list-item">
                    <view class="icon">
                        <text class="iconfont icon-phone"></text>
                    </view>
                    <view class="title">
                        您已绑定手机
                    </view>
                    <view class="value">
                        {{ user.phone }}
                    </view>
                </view>
            </view>
            <view>
                <button class="btn btn-primary btn-block btn-lg btn-round" @tap="change=true">立即修改</button>
            </view>
        </view>
        <view v-if="!user.phone || change" class="tw-p-8 ub-content-bg ub-h-full">
            <view class="ub-input-simple">
                <view class="input">
                    <input placeholder="输入新手机" v-model="data.phone"/>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input with-action">
                    <view class="control">
                        <input placeholder="输入图片验证" v-model="data.captcha"/>
                    </view>
                    <view class="action">
                        <c-smart-captcha ref="captcha" class="captcha" src="member_profile/captcha"/>
                    </view>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input with-action">
                    <view class="control">
                        <input placeholder="输入手机验证码" v-model="data.verify"/>
                    </view>
                    <view class="action">
                        <c-smart-verify class="verify"
                                        src="member_profile/phone_verify"
                                        :target.sync="data.phone"
                                        :captcha.sync="data.captcha"
                                        @error="onVerifyError"></c-smart-verify>
                    </view>
                </view>
            </view>
            <view class="ub-input-action">
                <button :loading="loading" class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">
                    {{ (!!user.phone) ? '确认修改' : '确认绑定' }}
                </button>
                <button v-if="!user.phone" class="btn btn-lg btn-block btn-round margin-top" @click="$r.back()">
                    暂不绑定
                </button>
            </view>
        </view>
    </view>
</template>

<script>

import {EventBus} from "../../lib/event-bus";
import {mapState} from 'vuex'
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    mixins: [StoreBaseUiMixin],
    computed: mapState({
        user: (state) => state.user.user
    }),
    onShow() {
        this.$starter.boot()
    },
    data() {
        return {
            loading: false,
            change: false,
            data: {
                phone: '',
                verify: '',
                captcha: '',
            }
        }
    },
    methods: {
        doSubmit() {
            this.loading = true
            this.$delay(() => {
                this.$api.post('member_profile/phone', this.data, res => {
                    this.loading = false
                    this.change = false
                    this.data.phone = ''
                    this.data.verify = ''
                    this.data.captcha = ''
                    EventBus.$emitDelay('UpdateApp')
                    this.$dialog.tipSuccess('修改成功', () => {
                        const redirect = this.$r.getQuery('redirect')
                        if (redirect) {
                            this.$r.to(redirect)
                        } else {
                            this.$r.back()
                        }
                    })
                }, res => {
                    this.loading = false
                })
            })
        },
        onVerifyError() {
            this.$refs.captcha.refresh()
        }
    },
}
</script>

