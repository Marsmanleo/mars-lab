<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-h-full">
        <c-page-header title="收益明细"></c-page-header>
        <view class="ub-list no-bg">
            <view v-for="(item,itemIndex) in list.records" :key="item.id">
                <view class="tw-bg-white tw-m-2 tw-items-center tw-flex tw-p-3">
                    <view class="tw-w-10 tw-flex-shrink-0">
                        <view
                            class="tw-w-8 tw-h-8 ub-bg-primary tw-rounded-full ub-text-white tw-text-center tw-leading-8">
                            <text class="iconfont icon-gift"></text>
                        </view>
                    </view>
                    <view class="tw-flex-grow">
                        <view class="tw-leading-tight tw-pb-2">{{ item.remark }}</view>
                        <view class="ub-text-muted tw-text-sm">
                            {{ item.time }}
                        </view>
                    </view>
                    <view class="tw-text-lg">
                        <text class="ub-text-success" v-if="item.value>0">+{{ item.value }}</text>
                        <text class="ub-text-danger" v-else>{{ item.value }}</text>
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
import CConstantText from "../../components/c-constant-text";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "bonus_log",
    components: {CConstantText},
    mixins: [ListerMixin, StoreBaseUiMixin],
    data() {
        return {
            MemberDistributionOrderItemStatus,
            search: {},
        }
    },
    methods: {
        doList(page) {
            this.doListProcess('member_distribution/bonus_log', page)
        },
    }
}
</script>

