<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <c-page-header title="找回密码"></c-page-header>
    <view class="tw-p-8 ub-content-bg ub-h-full">
        <tui-steps :items="steps"
                   :activeColor="activeColor" spacing="180rpx"
                   :activeSteps="0"/>
        <view class="tw-p-4 margin-top" v-if="config.retrievePhoneEnable">
            <c-smart-link class="btn btn-lg btn-primary btn-block btn-round" to="/brick/module/member/retrieve_phone">
                通过手机找回
            </c-smart-link>
        </view>
        <view class="tw-p-4 margin-top" v-if="config.retrieveEmailEnable">
            <c-smart-link class="btn btn-lg btn-primary btn-block btn-round" to="/brick/module/member/retrieve_email">
                通过邮箱找回
            </c-smart-link>
        </view>
    </view>
</template>

<script>
import Logo from "./components/Logo";
import {mapState} from 'vuex'
import {SystemSetting} from "../../../config/setting";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    components: {Logo},
    computed: mapState({
        config: (state) => state.base.config
    }),
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            activeColor: SystemSetting.primaryColor,
            steps: [
                {title: '选择方式', desc: '找回密码方式'},
                {title: '验证身份', desc: '验证本人信息'},
                {title: '重置密码', desc: '重新设定新密码'},
            ]
        }
    },
    onShow() {
        this.$starter.boot()
    },
}
</script>

<style lang="less" scoped>
@import "./style/style";
</style>

