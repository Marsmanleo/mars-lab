<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>

        <c-page-header title="修改邮箱"/>

        <view class="tw-p-4 ub-content-bg ub-h-full" v-if="!!user.email && !change">
            <view class="ub-m-list ub-box-shadow">
                <view class="ub-m-list-item">
                    <view class="icon">
                        <text class="iconfont icon-email"></text>
                    </view>
                    <view class="title">
                        您已绑定邮箱
                    </view>
                    <view class="value">
                        {{ user.email }}
                    </view>
                </view>
            </view>
            <view>
                <button class="btn btn-primary btn-block btn-lg" @tap="change=true">立即修改</button>
            </view>
        </view>
        <view class="tw-p-8 ub-content-bg ub-h-full" v-if="!user.email || change">
            <view class="ub-input-simple">
                <view class="input">
                    <input placeholder="输入新邮箱" v-model="data.email"/>
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
                        <input placeholder="输入邮箱验证码" v-model="data.verify"/>
                    </view>
                    <view class="action">
                        <c-smart-verify class="verify"
                                        src="member_profile/email_verify"
                                        :target.sync="data.email"
                                        :captcha.sync="data.captcha"
                                        @error="onVerifyError"></c-smart-verify>
                    </view>
                </view>
            </view>
            <view class="ub-input-action">
                <button :loading="loading" class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">确认修改
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
    computed: mapState({
        user: (state) => state.user.user
    }),
    mixins: [StoreBaseUiMixin],
    onShow() {
        this.$starter.boot()
    },
    data() {
        return {
            loading: false,
            change: false,
            data: {
                email: '',
                verify: '',
                captcha: '',
            }
        }
    },
    methods: {
        doSubmit() {
            this.loading = true
            this.$delay(() => {
                this.$api.post('member_profile/email', this.data, res => {
                    this.loading = false
                    this.change = false
                    this.data.email = ''
                    this.data.verify = ''
                    this.data.captcha = ''
                    EventBus.$emitDelay('UpdateApp')
                    this.$dialog.tipSuccess('修改成功', () => {
                        this.$r.back()
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

