<template>
    <view class="ub-header ub-header-popup">
        <view class="header-status" :style="{backgroundColor:backgroundColor}"></view>
        <view class="header-container" :style="{backgroundColor:backgroundColor}">
            <view class="body" :class="{'has-back':hasCancel}">
                <text class="back iconfont" :style="{color:color}" v-if="hasCancel" @click="doCancel">&#xe60b;</text>
                <text class="title" :style="{color:color}">{{title}}</text>
                <view class="action" :style="{color:color}">
                    <slot name="action"></slot>
                </view>
            </view>
        </view>
        <view class="header-container-placeholder"></view>
    </view>
</template>

<script>
    export default {
        name: "PagePopupHeader",
        props: {
            title: {
                type: String,
                default: ''
            },
            backgroundColor: {
                type: String,
                default: '#FFF'
            },
            color: {
                type: String,
                default: '#333333'
            },
            hasCancel: {
                type: Boolean,
                default: true
            }
        },
        methods: {
            doCancel() {
                this.$emit('cancel')
            }
        },
    }
</script>

<style lang="less">
    .ub-header {
        .header-status{
            height: var(--status-bar-height);
        }
        .header-container {
            top: var(--status-bar-height);
        }
        .header-container-placeholder {
            height: calc(100rpx + var(--status-bar-height));
        }
    }
</style>
