<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="修改密码"/>
        <view class="tw-p-8 ub-content-bg ub-h-full">
            <view class="ub-input-simple">
                <view class="input">
                    <input type="password" placeholder="输入原密码" v-model="data.passwordOld"/>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input">
                    <input type="password" placeholder="输入新密码" v-model="data.passwordNew"/>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input">
                    <input type="password" placeholder="确认新密码" v-model="data.passwordRepeat"/>
                </view>
            </view>
            <view class="ub-input-action">
                <button :loading="loading" class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">登录
                </button>
            </view>
        </view>
    </view>
</template>

<script>


import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    mixins: [StoreBaseUiMixin],
    onShow() {
        this.$starter.boot()
    },
    data() {
        return {
            loading: false,
            data: {
                passwordOld: '',
                passwordNew: '',
                passwordRepeat: '',
            }
        }
    },
    methods: {
        doSubmit() {
            this.loading = true
            this.$api.post('member_profile/password', this.data, res => {
                this.loading = false
                this.$dialog.tipSuccess('修改成功，请重新登录', () => {
                    this.$r.to('/brick/module/member/logout')
                })
            }, res => {
                this.loading = false
            })
        },
    },
}
</script>

