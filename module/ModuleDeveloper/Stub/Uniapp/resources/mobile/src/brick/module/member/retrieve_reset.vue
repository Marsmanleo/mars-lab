<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <c-page-header title="重置密码"></c-page-header>
    <view class="tw-p-8 ub-content-bg ub-h-full">
        <tui-steps :items="steps"
                   :activeColor="activeColor" spacing="180rpx"
                   :activeSteps="2"/>

        <form>
            <view class="ub-input-simple">
                <view class="input">
                    <input v-model="data.username" :disabled="true"/>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input">
                    <input type="password" placeholder="输入新密码" v-model="data.password"/>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input">
                    <input type="password" placeholder="确认新密码" v-model="data.passwordRepeat"/>
                </view>
            </view>
            <view class="ub-input-action">
                <button :loading="isSubmitting" :disabled="isSubmitting"
                        class="btn btn-lg btn-primary btn-block btn-round"
                        @click="doSubmit()">
                    设置新密码
                </button>
            </view>
        </form>
    </view>
</template>

<script>
import Logo from "./components/Logo";
import Retrieve from "./components/Retrieve";
import {SystemSetting} from "../../../config/setting";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    mixins: [StoreBaseUiMixin],
    components: {
        Retrieve,
        Logo
    },
    data() {
        return {
            isSubmitting: false,
            data: {
                username: '',
                password: '',
                passwordRepeat: '',
            },
            activeColor: SystemSetting.primaryColor,
            steps: [
                {title: '选择方式', desc: '找回密码方式'},
                {title: '验证身份', desc: '验证本人信息'},
                {title: '重置密码', desc: '重新设定新密码'},
            ]
        };
    },
    onShow() {
        this.$starter.boot(() => {
            this.$dialog.loadingOn()
            this.$api.post('retrieve_reset_info', {}, res => {
                this.$dialog.loadingOff()
                this.data.username = res.data.memberUser.username
            })
        })
    },
    methods: {
        doSubmit() {
            this.isSubmitting = true
            this.$api.post('retrieve_reset', this.data, res => {
                this.$dialog.tipSuccess('重置密码成功，请您登录', () => {
                    this.$r.replace('/brick/module/member/login')
                })
            }, res => {
                this.isSubmitting = false
            })
        }
    }
}
</script>
<style lang="less" scoped>
@import "./style/style";
</style>
