<template>
    <view class="pb-member-center-top">
        <slot></slot>
        <view v-if="user.id" class="container">
            <c-smart-link class="avatar" to="/brick/module/member/profile">
                <image class="img" :src="user.avatar"/>
            </c-smart-link>
            <view class="name name-box">
                <view class="box">
                    <text class="text" v-if="user.nickname" @click="$r.to('/brick/module/member/profile')">
                        {{ user.nickname }}
                    </text>
                    <text class="text" v-if="!user.nickname" @click="$r.to('/brick/module/member/profile')">
                        {{ user.username }}
                    </text>
                    <view class="addition">
                        <slot name="addition"></slot>
                    </view>
                </view>
            </view>
            <c-smart-link class="icon" to="/brick/module/member/profile">
                <text class="icon-text">我的资料</text>
                <text class="iconfont icon-angle-right"></text>
            </c-smart-link>
        </view>
        <view v-if="!user.id" class="container">
            <c-smart-link class="avatar" to="/brick/module/member/login">
                <image class="img" :src="user.avatar"/>
            </c-smart-link>
            <c-smart-link class="name name-box" to="/brick/module/member/login">
                <view class="box">
                    <text class="text">点我登录</text>
                    <view class="addition">
                        登录享受更多权益
                    </view>
                </view>
            </c-smart-link>
            <c-smart-link class="icon" to="/brick/module/member/login">
                <text class="iconfont">&#xe609;</text>
            </c-smart-link>
        </view>
    </view>
</template>

<script>
import {
    mapState
} from 'vuex'

export default {
    name: "MemberCenterTop",
    computed: mapState({
        user: (state) => state.user.user
    }),
    onShow() {
    }
}
</script>

<style scoped lang="less">
@import "./../../../config/theme";

.pb-member-center-top {
    background: linear-gradient(to bottom, var(--color-primary-dark), var(--color-primary-light));
    padding: 2rem 1rem;
    color: #FFF;

    .container {
        display: flex;
    }

    .avatar {
        height: 2.5rem;
        width: 2.5rem;
        margin-right: 0.5rem;
        display: block;
        background: #CCC;
        border-radius: 0.75rem;

        .img {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            border: 2px solid #FFF;
            box-sizing: border-box;
        }
    }

    .name {
        flex-grow: 1;
        display: block;

        &.name-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .box {
            display: flex;
            flex-direction: column;
            justify-content: center;

            .text {
                line-height: 1.25rem;
                display: block;
                font-weight: bold;
                font-size: var(--font-size-medium);
            }

            .addition {
                font-size: var(--font-size-small);
                padding-top: 0.25rem;
            }
        }
    }

    .icon {
        line-height: 2.5rem;
        font-size: 0.6rem;
        display: flex;
        justify-items: center;

        .iconfont {
            display: inline-block;
            font-size: 1rem;
        }
    }
}
</style>
