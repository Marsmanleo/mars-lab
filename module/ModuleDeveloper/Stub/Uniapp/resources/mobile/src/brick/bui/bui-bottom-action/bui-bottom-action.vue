<template>
    <view>
        <view class="bui-bottom-action-placeholder"></view>
        <view class="bui-bottom-action">
            <view class="icons" v-if="icons.length>0">
                <view class="item" v-for="(icon,iconIndex) in icons" :key="iconIndex" @click="$emit('icon-click',{index:iconIndex,icon:icon})">
                    <text class="icon" :class="icon.icon"></text>
                    <view class="text">{{icon.text}}</view>
                    <view class="badge" v-if="icon.badge>0">{{icon.badge}}</view>
                </view>
            </view>
            <view class="actions" :class="{'one-action':!actionSecondShow}">
                <view class="item second" v-if="actionSecondShow" @click="$emit('action-second')">
                    {{actionSecond}}
                </view>
                <view class="item primary" @click="$emit('action-primary')" :style="{backgroundColor:actionPrimaryBg}">
                    {{actionPrimary}}
                </view>
            </view>
        </view>
    </view>
</template>

<script>
export default {
    props: {
        actionPrimary: {
            type: String,
            default: '立即购买',
        },
        actionSecond: {
            type: String,
            default: '加入购物车',
        },
        actionSecondShow:{
            type:Boolean,
            default:true,
        },
        actionPrimaryBg:{
            type:String,
            default:'var(--color-primary)',
        },
        icons: {
            type: Array,
            default: () => {
                return [
                    // {
                    // 	icon: 'iconfont icon-cart',
                    // 	text: '购物车',
                    // 	badge: 2
                    // },
                ]
            }
        }
    },
    data() {
        return {

        }
    },
    methods: {

    }
}
</script>

<style lang="less">
.bui-bottom-action-placeholder {
    height: 2.5rem;
}

.bui-bottom-action {
    position: fixed;
    bottom: 0px;
    left: 0;
    right: 0;
    z-index: 1000;
    border-top: 1px solid #EEE;
    background: #FFF;
    display: flex;
    height: 2.5rem;

    .icons {
        padding: 0 0.5rem;
        display: flex;

        .item {
            height: 2.5rem;
            width: 2.5rem;
            padding: 0.5rem 0;
            text-align: center;
            position: relative;

            .icon {
                font-size: 1rem;
                line-height: 1rem;
            }

            .text {
                font-size: 0.5rem;
                line-height: 0.5rem;
            }

            .badge {
                background: red;
                width: 0.8rem;
                height: 0.8rem;
                border-radius: 50%;
                color: #FFF;
                position: absolute;
                right: 0.2rem;
                top: 0.2rem;
                font-size: 0.5rem;
                line-height: 0.8rem;
            }
        }
    }

    .actions {
        flex-grow: 1;
        text-align: right;
        display: flex;
        padding: 0.3rem 0.5rem;

        &.one-action{
            .item.primary{
                border-radius:0.9rem;
            }
        }

        .item {
            flex-grow: 1;
            height: 1.8rem;
            color: #FFF;
            line-height: 1.8rem;
            background: var(--color-primary);
            text-align: center;

            &.primary {
                border-radius: 0 0.9rem 0.9rem 0;
            }

            &.second {
                border-radius: 0.9rem 0 0 0.9rem;
                background-color: var(--color-warning);
            }
        }
    }
}
</style>
