<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-h-full">
        <c-page-header title="团队粉丝"></c-page-header>
        <view class="ub-list no-bg tw-pt-2">
            <view class="ub-content-box margin-bottom" v-for="(item,itemIndex) in list.records" :key="item.id">
                <view class="tw-flex">
                    <view class="tw-flex-grow">
                        <bui-member-user :member="item._user"></bui-member-user>
                    </view>
                    <view class="ub-text-muted">
                        {{ item.time }}
                    </view>
                </view>
                <view class="tw-pt-2 ub-text-muted">
                    消费
                    <text class="ub-tag tw-mx-1">{{ item.orderTotal }}</text>
                    单共
                    <text class="ub-tag tw-mx-1">￥{{ item.orderFeeTotal }}</text>
                </view>
            </view>
            <c-list-status ref="listStatus" :loading="listLoading" :records="list.records"/>
        </view>
    </view>
</template>

<script>

import {ListerMixin} from "../../components/Common/mixins/lister";
import {MemberDistributionOrderItemStatus} from "./constant";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "bonus_log",
    mixins: [ListerMixin, StoreBaseUiMixin],
    data() {
        return {
            MemberDistributionOrderItemStatus,
            search: {},
        }
    },
    methods: {
        doList(page) {
            this.doListProcess('member_distribution/user', page)
        },
    }
}
</script>

