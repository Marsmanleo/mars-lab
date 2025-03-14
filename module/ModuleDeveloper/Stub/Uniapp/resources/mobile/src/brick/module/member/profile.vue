<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="我的资料"/>

        <view class="tw-p-4">

            <view class="ub-m-list">
                <view class="ub-m-list-item" @tap="$refs.nicknameEdit.show()">
                    <view class="icon">
                        <text class="iconfont icon-user"></text>
                    </view>
                    <view class="title">
                        昵称
                    </view>
                    <view class="value">
                        <text v-if="user.nickname">{{ user.nickname }}</text>
                        <text v-else class="ub-text-muted">未设置</text>
                    </view>
                </view>
                <view class="ub-m-list-item">
                    <view class="icon">
                        <text class="iconfont icon-profile"></text>
                    </view>
                    <view class="title">
                        账号
                    </view>
                    <view class="value">
                        <text v-if="user.username">{{ user.username }}</text>
                        <text v-else class="ub-text-muted">未设置</text>
                    </view>
                </view>
            </view>

            <view class="ub-m-list">
                <view class="ub-m-list-item" @tap="$r.to('/brick/module/member/profile_avatar')">
                    <view class="icon">
                        <text class="iconfont icon-user-o"></text>
                    </view>
                    <view class="title">
                        修改头像
                    </view>
                    <view class="angle">
                        <text class="iconfont icon-angle-right"></text>
                    </view>
                </view>
                <view class="ub-m-list-item" @tap="$r.to('/brick/module/member/profile_password')">
                    <view class="icon">
                        <text class="iconfont icon-lock"></text>
                    </view>
                    <view class="title">
                        修改密码
                    </view>
                    <view class="angle">
                        <text class="iconfont icon-angle-right"></text>
                    </view>
                </view>
            </view>

            <view class="ub-m-list">
                <view v-if="config.Member_ProfilePhoneEnable" class="ub-m-list-item"
                      @tap="$r.to('/brick/module/member/profile_phone')">
                    <view class="icon">
                        <text class="iconfont icon-phone"></text>
                    </view>
                    <view class="title">
                        手机
                    </view>
                    <view class="value">
                        {{ user.phone ? user.phone : '' }}
                    </view>
                    <view class="angle">
                        <text class="iconfont icon-edit"></text>
                    </view>
                </view>
                <view v-if="config.Member_ProfileEmailEnable" class="ub-m-list-item"
                      @tap="$r.to('/brick/module/member/profile_email')">
                    <view class="icon">
                        <text class="iconfont icon-email"></text>
                    </view>
                    <view class="title">
                        邮箱
                    </view>
                    <view class="value">
                        {{ user.email ? user.email : '' }}
                    </view>
                    <view class="angle">
                        <text class="iconfont icon-edit"></text>
                    </view>
                </view>
            </view>

        </view>

        <view class="tw-absolute tw-bottom-0 tw-left-0 tw-right-0 tw-p-4">
            <view class="btn btn-danger gradient btn-block btn-lg btn-round" @tap="doLogout">
                <text class="iconfont icon-lock tw-mr-2"></text>
                退出登录
            </view>
        </view>

        <NicknameEditDialog ref="nicknameEdit"></NicknameEditDialog>

    </view>
</template>

<script>
import {
    mapState
} from 'vuex'
import NicknameEditDialog from "./components/NicknameEditDialog";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";
import {EventBus} from "../../lib/event-bus";

export default {
    components: {NicknameEditDialog},
    computed: mapState({
        user: (state) => state.user.user,
        config: (state) => state.base.config
    }),
    mixins: [StoreBaseUiMixin],
    data() {
        return {};
    },
    onShow() {
        this.$starter.boot()
    },
    methods: {
        doLogout() {
            this.$dialog.confirm('确认退出登录？', () => {
                const redirect = this.$r.getQuery('redirect')
                if (this.config.ssoClientEnable) {
                    // window.__Dao.exec('sso/server_logout_prepare', {
                    //     domainUrl: UrlUtil.domainUrl(),
                    // }, res => {
                    //     window.location.href = res.data.redirect
                    // })
                } else {
                    this.$api.post('logout', {}, res => {
                        EventBus.$emitDelay('UpdateApp', () => {
                            if (redirect) {
                                this.$r.replace(redirect)
                            } else {
                                this.$r.back()
                            }
                        })
                    })
                }
            })
        }
    }
}
</script>


<style lang="less" scoped>
@import "./style/style";
</style>
