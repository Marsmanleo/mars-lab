<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-h-full">
        <c-page-header title="分销订单"></c-page-header>
        <view class="ub-list no-bg tw-mt-2">
            <view class="ub-content-box margin-bottom" v-for="(item,itemIndex) in list.records" :key="item.id">
                <view class="tw-flex">
                    <view class="tw-flex-grow">
                        <bui-member-user :member="item._memberUser"></bui-member-user>
                    </view>
                    <view class="ub-text-muted">
                        <view class="tw-bg-red-400 tw-rounded-xl tw-text-white tw-px-2 tw-leading-5 tw-inline-block">
                            佣金￥{{ item.bonus }}
                        </view>
                    </view>
                </view>
                <view class="tw-pt-2">
                    <view class="tw-inline-block ub-tag tw-mr-2">
                        {{ item.level }}级
                    </view>
                    <view class="tw-inline-block ub-tag tw-mr-2">
                        {{ item.bizName }}
                    </view>
                    <view class="tw-inline-block ub-tag tw-mr-2">
                        ￥{{ item.orderFee }}
                    </view>
                </view>
                <view class="tw-pt-1 ub-text-muted">
                    {{ item.orderSubject }}
                </view>
                <view class="tw-pt-2 tw-flex tw-items-center">
                    <view class="ub-text-muted">
                        {{ item.time }}
                    </view>
                    <view class="tw-flex-grow tw-text-right">
                        <c-constant-text :value="item.status" :types="MemberDistributionOrderItemStatus"/>
                    </view>
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
    name: "order_item",
    mixins: [ListerMixin, StoreBaseUiMixin],
    data() {
        return {
            MemberDistributionOrderItemStatus,
            search: {},
        }
    },
    methods: {
        doList(page) {
            this.doListProcess('member_distribution/order_item', page)
        },
    }
}
</script>

