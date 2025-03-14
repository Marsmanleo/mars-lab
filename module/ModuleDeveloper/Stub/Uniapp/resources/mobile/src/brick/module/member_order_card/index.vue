<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="充值卡" :shadow="false"></c-page-header>
        <c-loading v-if="loading" page/>
        <view v-else class="tw-pt-2 tw-px-2">
            <view class="tw-flex tw-flex-wrap">
                <view v-for="(r,rIndex) in records"
                      :key="r.id"
                      class="tw-flex-grow tw-px-1 margin-bottom"
                      style="max-width:50%;">
                    <view @click="recordId=r.id"
                          class="pb-item ub-content-box ub-border tw-shadow-lg tw-text-center tw-flex tw-items-center"
                          :class="{active:recordId===r.id}">
                        <view class="tw-w-6 tw-flex-shrink-0 tw-mr-2">
                            <c-cover border-radius="0.25rem" :src="r.cover"/>
                        </view>
                        <view class="tw-flex-grow ub-text-truncate">
                            {{ r.title }}
                        </view>
                    </view>
                </view>
            </view>
            <view class="tw-px-1" v-if="currentRecord">
                <view class="ub-content-box tw-shadow-lg">
                    <view class="tw-text-lg margin-bottom">
                        {{ currentRecord.title }}
                    </view>
                    <view class="margin-bottom">
                        <view class="ub-text-primary tw-text-lg tw-inline-block tw-mr-2">
                            ￥{{ currentRecord.price }}
                        </view>
                        <view class="tw-line-through ub-text-muted tw-inline-block">
                            ￥{{ currentRecord.priceMarketing }}
                        </view>
                    </view>
                    <view>
                        {{ currentRecord.summary }}
                    </view>
                </view>
            </view>
            <view class="tw-h-10"></view>
            <view class="tw-bottom-0 tw-fixed tw-left-0 tw-p-3 tw-right-0 tw-shadow ub-border-top ub-content-bg">
                <view class="btn btn-primary btn-lg btn-block btn-round"
                      @click="doSubmit">
                    ￥
                    {{ currentRecord.price }}
                    立即支付
                </view>
            </view>
        </view>
        <PayCenterPanel ref="payCenter"/>
    </view>
</template>

<script>
import PayCenterPanel from "../pay_center/components/PayCenterPanel";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "index",
    components: {PayCenterPanel},
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            loading: true,
            records: [],
            recordId: 0,
        }
    },
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    computed: {
        currentRecord() {
            return this.records.find(r => r.id === this.recordId)
        },
    },
    methods: {
        init() {
            this.$api.post('member_order_card/all', {}, res => {
                if (res.data.records.length > 0) {
                    this.recordId = res.data.records[0].id
                }
                this.records = res.data.records
                this.loading = false
            })
        },
        doSubmit() {
            this.$dialog.loadingOn()
            this.$api.post('member_order_card/submit', {
                orderId: this.recordId,
                redirect: this.$r.pathToUrl('/pages/member'),
            }, res => {
                this.$dialog.loadingOff()
                this.$refs.payCenter.show(res.data.orderSecretId)
            }, res => {
                this.$dialog.loadingOff()
            })
        }
    }
}
</script>

<style lang="less">
.pb-item {
    &.active {
        border-color: var(--color-primary);
        outline: 0.05rem solid var(--color-primary);
    }
}
</style>
