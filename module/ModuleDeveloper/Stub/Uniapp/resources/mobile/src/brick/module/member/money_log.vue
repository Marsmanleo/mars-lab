<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="资金明细"/>
        <c-list-head-tools @submit="doSearch" @reset="doSearchReset">
            <c-list-head-tools-item title="筛选">
                <view class="ub-form vertical">
                    <view class="line">
                        <view class="label">
                            类型
                        </view>
                        <view class="field">
                            <c-single-id-name-selector
                                :options="[{id:'',name:'全部'},{id:'income',name:'收入'},{id:'payout',name:'支出'}]"
                                v-model="search.type"
                            ></c-single-id-name-selector>
                        </view>
                    </view>
                </view>
            </c-list-head-tools-item>
        </c-list-head-tools>
        <view>
            <c-list-status-top :loading="listLoading" :records="list.records"/>
            <view v-for="(item,itemIndex) in list.records" :key="item.id"
                  class="tw-bg-white tw-m-2 tw-items-center tw-flex tw-p-3">
                <view class="tw-w-10 tw-flex-shrink-0">
                    <view class="tw-w-8 tw-h-8 ub-bg-primary tw-rounded-full ub-text-white tw-text-center tw-leading-8">
                        <text class="iconfont icon-gift"></text>
                    </view>
                </view>
                <view class="tw-flex-grow">
                    <view class="tw-leading-tight tw-pb-2">{{ item.remark }}</view>
                    <view class="ub-text-muted tw-text-sm">
                        {{ item.created_at }}
                    </view>
                </view>
                <view class="tw-text-lg">
                    <text class="ub-text-success" v-if="item.change>0">+{{ item.change }}</text>
                    <text class="ub-text-danger" v-else>{{ item.change }}</text>
                </view>
            </view>
            <c-list-status ref="listStatus" :loading="listLoading" :records="list.records"/>
        </view>

    </view>
</template>

<script>
import {ListerMixin} from "../../components/Common/mixins/lister";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "message",
    mixins: [ListerMixin, StoreBaseUiMixin],
    data() {
        return {
            search: {
                type: ''
            },
        }
    },
    methods: {
        doList(page) {
            this.doListProcess('member_money/log', page)
        },
        doSearchReset() {
            this.search.type = ''
            this.doSearch()
        }
    }
}
</script>
