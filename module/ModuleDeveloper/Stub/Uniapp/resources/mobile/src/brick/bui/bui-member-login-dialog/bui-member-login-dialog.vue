<template>
    <c-modal title="您需要完成登录" ref="modal" width="50%">
        <view>
            <button class="btn btn-primary btn-round btn-block btn-lg"
                    @tap="doWechatMiniProgramLogin">
                <text class="iconfont icon-wechat"></text>
                <text>点击登录</text>
            </button>
        </view>
    </c-modal>
</template>

<script>
import {OauthScript} from "../../module/member/components/OauthScript";

export default {
    name: "bui-member-login-dialog",
    mixins: [OauthScript],
    data() {
        return {
            ctx: null,
            successCallback: null
        }
    },
    computed: {
        support() {
            let support = false
            // #ifdef MP-WEIXIN
            support = true;
            // #endif
            return support
        }
    },
    methods: {
        show(ctx, successCallback) {
            this.ctx = ctx
            this.successCallback = successCallback
            this.$refs.modal.show()
        },
        // #ifdef MP-WEIXIN
        doWechatMiniProgramLogin() {
            this.doWechatMiniProgramLoginProcess((res) => {
                this.$refs.modal.close()
                this.successCallback && this.successCallback()
            })
        }
        // #endif
    }
}
</script>

<style lang="less" scoped>
.bui-member-login-dialog {
    background: rgba(0, 0, 0, 0.5);
    position: fixed;
    left: 0;
    right: 0;
    z-index: 1000;
    top: 0;
    bottom: 0;
    display: flex;
    align-items: center;

    .content {
        margin: 0 auto;
        background: var(--color-body-bg);
        border-radius: 0.5rem;
        padding: 1rem;
        position: relative;

        .close {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            background: var(--color-body-bg);
            width: 1.5rem;
            height: 1.5rem;
            line-height: 1.5rem;
            text-align: center;
            border-radius: 50%;
            box-shadow: 0 0 5px #999;
        }
    }
}
</style>
