<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="优惠券"/>
        <view class="tw-mt-1">
            <c-list-status-top :loading="listLoading" :records="list.records"/>
            <view v-for="(item,itemIndex) in list.records" :key="item.bizId" class="tw-p-2 tw-py-1">
                <view
                    class="tw-bg-white tw-shadow tw-rounded-lg tw-p-2 tw-h-24 tw-box-border">
                    <view class="tw-flex">
                        <view class="tw-flex-grow tw-overflow-hidden">
                            <view class="tw-text-red-600 tw-text-lg">
                                {{ item._voucher['showTag'] }}
                            </view>
                            <view class="tw-mt-1 ub-text-truncate tw-overflow-hidden">
                                {{ item._voucher['summary'] }}
                            </view>
                            <view class="ub-text-muted tw-text-sm">
                                {{ item.startTime }}~{{ item.expireTime }}
                            </view>
                        </view>
                        <view class="tw-relative tw-w-20 tw-flex-shrink-0">
                            <view
                                :class="{'tw-bg-red-400':item.status===VoucherItemStatus.VALID.value,'tw-bg-gray-300':item.status!==VoucherItemStatus.VALID.value}"
                                class="tw-text-white hover:tw-text-white tw-absolute tw--top-2 tw--right-2 tw-h-24 tw-w-24 tw-flex tw-flex-col tw-justify-center tw-rounded-lg">
                                <view class="tw-whitespace-nowrap tw-text-center">
                                    <view v-if="item.status===VoucherItemStatus.VALID.value" @click="doSubmit(item)">
                                        <view>立即</view>
                                        <view>使用</view>
                                    </view>
                                    <view v-else-if="item.status===VoucherItemStatus.EXPIRED.value">
                                        已过期
                                    </view>
                                    <view v-else-if="item.status===VoucherItemStatus.USED.value">
                                        已使用
                                    </view>
                                    <view v-else-if="item.status===VoucherItemStatus.DISABLED.value">
                                        已禁用
                                    </view>
                                </view>
                            </view>
                        </view>
                    </view>
                </view>
            </view>
            <c-list-status ref="listStatus" :loading="listLoading" :records="list.records"/>
        </view>
    </view>
</template>

<script>
import {ListerMixin} from "../../components/Common/mixins/lister";
import {VoucherItemStatus} from "./constant";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "member_voucher",
    mixins: [ListerMixin, StoreBaseUiMixin],
    data() {
        return {
            VoucherItemStatus
        }
    },
    methods: {
        init() {
            this.doSearch()
        },
        doList(page) {
            this.doListProcess('member_voucher/paginate', page)
        },
        doSubmit(item) {
            this.$dialog.loadingOn()
            this.$api.post('member_voucher/submit', {
                id: item.id
            }, res => {
                this.$dialog.loadingOff()
            }, res => {
                this.$dialog.loadingOff()
            })
        }
    }
}
</script>

