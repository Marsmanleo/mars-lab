<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="提现申请"/>
        <view>
            <c-list-status-top :loading="listLoading" :records="list.records"/>
            <view v-for="(item,itemIndex) in list.records" :key="item.id"
                  class="tw-flex tw-bg-white tw-m-2 tw-p-2 tw-items-center tw-rounded">
                <view class="tw-w-10 tw-flex-shrink-0">
                    <view class="tw-w-8 tw-h-8 ub-bg-primary tw-rounded-full ub-text-white tw-text-center tw-leading-8">
                        <text class="iconfont icon-gift"></text>
                    </view>
                </view>
                <view class="tw-flex-grow">
                    <view class="">{{ item.remark }}</view>
                    <view class="ub-text-muted tw-text-sm">
                        {{ item.created_at }}
                    </view>
                </view>
                <view class="tw-text-right">
                    <view class="tw-text-lg">
                        ￥{{ item.moneyAfterTax }}
                    </view>
                    <view class="ub-text-small">
                        <text class="ub-text-warning" v-if="item.status===1">正在审核</text>
                        <text class="ub-text-success" v-if="item.status===2">提现成功</text>
                    </view>
                </view>
            </view>
            <c-list-status ref="listStatus" :loading="listLoading" :records="list.records"/>
        </view>

    </view>
</template>

<script>
import ListFilterPanel from "../../components/ListFilterPanel";
import {ListerMixin} from "../../components/Common/mixins/lister";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "money_cash_log",
    mixins: [ListerMixin, StoreBaseUiMixin],
    components: {
        ListFilterPanel,
    },
    data() {
        return {
            search: {
                type: ''
            },
        }
    },
    methods: {
        doList(page) {
            this.doListProcess('member_money/cash/log', page)
        },
        doSearchReset() {
            this.search.type = ''
            this.doSearch()
        }
    }
}
</script>
