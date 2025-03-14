<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="我的地址" :shadow="false"></c-page-header>
        <view v-if="!loading && records.length===0">
            <c-empty text="暂无地址"/>
        </view>
        <view class="tw-p-2">
            <view v-for="(address,addressIndex) in records" :key="addressIndex"
                  class="ub-content-box margin-bottom">
                <view
                    class="tw-flex tw-items-center">
                    <view class="tw-flex-grow">
                        <view @click="doSelect(address)">
                            <text class="tw-text-lg ub-text-primary tw-mr-4 tw-font-bold">{{ address.name }}</text>
                            <text class="tw-text-lg">{{ address.phone }}</text>
                        </view>
                        <view @click="doSelect(address)" class="tw-mt-1">
                            {{ address.area }}
                        </view>
                        <view @click="doSelect(address)">
                            {{ address.detail }}
                        </view>
                        <view v-if="address.isDefault" class="ub-text-primary" @click="doSelect(address)">
                            <text class="iconfont icon-check tw-mr-1"></text>
                            默认地址
                        </view>
                    </view>
                    <view class="tw-w-10 tw-text-right">
                        <view @click="doEdit(address)">
                            <text class="iconfont icon-edit" style="font-size:1rem;"></text>
                        </view>
                    </view>
                </view>
            </view>
        </view>
        <view class="tw-h-20"></view>
        <view class="tw-h-20 tw-p-4 tw-fixed tw-bottom-0 tw-left-0 tw-right-0">
            <view class="btn btn-primary btn-round btn-lg btn-block" @click="doAdd">
                <text class="iconfont icon-plus"></text>
                新建地址
            </view>
        </view>
        <tui-loading v-if="loading"/>
    </view>
</template>

<script>
import {RouterStartForCallbackMixin} from "../../components/Common/mixins/router";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "member_address",
    mixins: [RouterStartForCallbackMixin, StoreBaseUiMixin],
    data() {
        return {
            loading: true,
            records: []
        }
    },
    methods: {
        init() {
            this.$api.post('member_address/all', {}, res => {
                this.records = res.data
                this.loading = false
            })
        },
        doEdit(address) {
            this.$r.startForCallback('/brick/module/member/address_edit', address, {}, res => {
                this.init()
            })
        },
        doAdd() {
            this.$r.startForCallback('/brick/module/member/address_edit', null, {}, res => {
                this.init()
            })
        },
        doSelect(address) {
            this.callbackConfirm(address)
        }
    }
}
</script>

