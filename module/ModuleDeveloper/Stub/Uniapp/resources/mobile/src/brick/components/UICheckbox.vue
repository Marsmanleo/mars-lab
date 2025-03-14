<template>
    <view class="pb-ui-checkbox iconfont"><block v-if="!value">&#xe61b;</block><block v-if="value">&#xe7be;</block></view>
</template>

<script>
    export default {
        name: "UICheckbox",
        props: {
            value: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style lang="less" scoped>
    .pb-ui-checkbox {
        color: var(--color-primary);
        font-size:36rpx;
        display: inline-block;
    }
</style>
