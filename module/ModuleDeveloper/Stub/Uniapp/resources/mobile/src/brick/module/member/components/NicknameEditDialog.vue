<template>
    <c-modal title="设置昵称" ref="modal">
        <view class="ub-input-simple">
            <view class="input">
                <!-- #ifdef H5 -->
                <input placeholder="请输入昵称" v-model="data.nickname"/>
                <!-- #endif -->
                <!-- #ifdef MP-WEIXIN -->
                <input type="nickname" placeholder="请输入昵称" v-model="data.nickname"/>
                <!-- #endif -->
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
        <view class="margin-bottom">
            <view @click="doSubmit"
                  class="btn btn-round btn-primary btn-lg btn-block margin-bottom">
                保存
            </view>
        </view>
        <view class="ub-border tw-p-3 tw-rounded">
            <view>为什么要设置昵称？</view>
            <view class="ub-text-muted">
                <view>
                    保护隐私，不会暴露真实账号名称
                </view>
                <view>
                    彰显个性，换掉无聊字符
                </view>
                <view>
                    随时修改，想改随时更改
                </view>
            </view>
        </view>
    </c-modal>
</template>

<script>
import {StoreUserMixin} from "../../../components/Common/mixins/store";
import {EventBus} from "../../../lib/event-bus";

export default {
    name: "NicknameEditDialog",
    mixins: [StoreUserMixin],
    data() {
        return {
            data: {
                nickname: ''
            }
        }
    },
    methods: {
        show() {
            this.data.nickname = this.user.nickname
            this.$refs.modal.show()
        },
        doSubmit() {
            this.$dialog.loadingOn()
            this.$api.post('member_profile/nickname', this.data, res => {
                this.$dialog.loadingOff()
                EventBus.$emitDelay('UpdateApp')
                this.$dialog.tipSuccess('设置成功')
                this.$refs.modal.close()
            }, res => {
                this.$dialog.loadingOff()
            })
        }
    }
}
</script>

