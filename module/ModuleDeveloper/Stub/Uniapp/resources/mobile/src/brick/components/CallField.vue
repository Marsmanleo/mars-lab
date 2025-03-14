<template>
    <view class="pb-call-field">
        <view @tap.stop="doCall">
            <text class="icon iconfont icon-1101dianhua"></text>
            <text class="tel">{{tel}}</text>
        </view>
    </view>
</template>


<script>
    export default {
        name: 'CallField',
        props: {
            tel: {
                type: String,
                default: null
            },
            icon: {
                type: Boolean,
                default: true
            }
        },
        methods: {
            doCall() {
                this.$dialog.confirm('立即拨打？', () => {
                    // #ifdef H5
                    uni.makePhoneCall({
                        phoneNumber: this.tel
                    })
                    // #endif
                    // #ifdef APP-PLUS
                    plus.device.dial(this.tel, false)
                    // #endif
                    // #ifdef MP-WEIXIN

                    // #endif
                    this.$emit('called', this.tel)
                })
            }
        }
    }
</script>

<style lang="less" scoped>
    @import "./../../config/theme";

    .pb-call-field {
        color: var(--color-primary);

        .icon {
            display: inline-block;
        }

        .tel {
            display: inline-block;
        }
    }
</style>
