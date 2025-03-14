<template>
    <view>
        <view v-if="loading" class="pb-captcha-item loading">
            <text class="iconfont icon-refresh"></text>
            正在加载...
        </view>
        <view v-else>
            <view @click="doVerify" class="pb-captcha-item wait" v-if="status==='wait'">
                <text class="iconfont icon-shield-check"></text>
                点击验证
            </view>
            <view @click="doVerify" class="pb-captcha-item success" v-if="status==='success'">
                <text class="iconfont icon-checked"></text>
                验证成功
            </view>
        </view>
    </view>
</template>

<script>
import {EventBus} from "../lib/event-bus";

export default {
    name: "c-tecmz-captcha",
    data() {
        return {
            loading: true,
            appId: null,
            captchaData: {
                type: null,
                result: null,
                key: null,
            },
            status: 'wait',
        };
    },
    watch: {
        captchaData: {
            handler: function (val, oldVal) {
                if (val.type === 'verify') {
                    if (val.result === 'success') {
                        this.status = 'success'
                        this.$emit('success', {
                            captchaKey: val.key,
                        })
                    }
                }
            },
            deep: true
        }
    },
    mounted() {
        EventBus.$on('modstart:captcha.error', this.onCaptchaError)
        this.load()
    },
    destroyed() {
        EventBus.$off('modstart:captcha.error', this.onCaptchaError)
    },
    methods: {
        onCaptchaError(e) {
            this.refresh()
        },
        doVerify() {
            this.refresh()
            this.$r.startForCallback('/brick/module/tecmz/captcha', null, {appId: this.appId}, res => {
                this.captchaData = res
            })
        },
        load() {
            this.$api.post("captcha_tecmz/info", {}, res => {
                this.loading = false
                this.appId = res.data.appId
            })
        },
        refresh() {
            this.captchaData = {
                type: null,
                result: null,
                key: null,
            }
            this.status = 'wait'
        }
    },
}
</script>

<style lang="less" scoped>
.pb-captcha-item {
    background: var(--color-content-bg);
    border-radius: 1rem;
    line-height: 2rem;
    padding: 0rem 1rem;

    .iconfont {
        margin-right: 0.2rem;
    }

    &.loading {
        color: var(--color-muted);
        background: var(--color-input-bg);
    }

    &.wait {
        color: var(--color-warning-dark);
        background: var(--color-warning-light);
    }

    &.success {
        color: var(--color-success-dark);
        background: var(--color-success-light);
    }
}
</style>

