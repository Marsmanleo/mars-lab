<template>
    <view>
        <view v-if="config.Member_VipEnable&&((!showWhenLogin)||(showWhenLogin&&user.id))"
              class="bui-member-vip-widget">
            <view class="action">
                <text class="btn" v-if="!user.vip || user.vip.isDefault" style="margin-top:0.6rem;"
                      @tap="$r.to('/brick/module/member/vip')">立即开通
                </text>
                <text class="btn" v-else style="margin-top:0.6rem;"
                      @tap="$r.to('/brick/module/member/vip')">续费/升级
                </text>
            </view>
            <view class="title" v-if="user.vip">{{ user.vip.title }}</view>
            <view class="title" v-else>游客</view>
            <view class="desc" v-if="!user.vip || user.vip.isDefault">开通高级会员解锁更多功能</view>
            <view class="desc" v-else-if="!user.vipExpire">您已是尊贵的会员（永久）</view>
            <view class="desc" v-else>您已是尊贵的会员（{{ user.vipExpire }}过期）</view>
        </view>
    </view>
</template>

<script>
import {UserRouterTo} from '../../lib/ui.js'
import {StoreBaseConfigMixin, StoreUserMixin} from "../../components/Common/mixins/store";

export default {
    name: 'bui-member-vip-widget',
    mixins: [StoreBaseConfigMixin, StoreUserMixin],
    props: {
        showWhenLogin: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {}
    },
    methods: {
        UserRouterTo
    }
}
</script>

<style lang="less" scoped>
.bui-member-vip-widget {
    background: #F7E9D9;
    border-radius: var(--size-radius);
    padding: var(--size-margin);
    margin: -1rem 0.5rem 0.5rem 0.5rem;

    .icon {
        float: left;
        width: 1.4rem;
        margin-right: 0.5rem;
        margin-top: 0.3rem;

        .img {
            width: 1.4rem;
            height: 1.4rem;
        }
    }

    .action {
        float: right;

        .btn {
            border-radius: 1rem;
            margin-top: 0.3rem;
            background: #EED8AE;
            color: #6D4E2B;
            border-color: #EED8AE;
        }
    }

    .title {
        font-weight: bold;
        color: #685127;
        font-size: 0.715rem;
    }

    .desc {
        color: #BC9E73;
        font-size: 0.5rem;
    }
}
</style>
