<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="通知公告"/>
        <view class="tw-p-3">
            <c-list-status-top :loading="listLoading" :records="list.records"/>
            <view v-for="(item,itemIndex) in list.records" :key="item.id">
                <view class="tw-p-3 tw-rounded-lg ub-content-bg tw-mb-3 ub-box-shadow"
                      @click="$r.to('/brick/module/notice/show',{id:item.id})">
                    <view class="tw-font-bold" :class="{'ub-text-danger':item.highlight}">
                        <text class="iconfont icon-bell tw-mr-2"></text>
                        {{ item.title }}
                    </view>
                    <view class="ub-text-muted tw-pt-2">
                        <text class="iconfont icon-time"></text>
                        {{ item.day }}
                    </view>
                </view>
            </view>
            <c-list-status ref="listStatus" :loading="listLoading" :page="list.page" :records="list.records"/>
        </view>

    </view>
</template>

<script>

import {ListerMixin} from "../../components/Common/mixins/lister";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "news",
    mixins: [ListerMixin, StoreBaseUiMixin],
    onShow() {
        this.$starter.boot()
    },
    data() {
        return {}
    },
    methods: {
        doList(page) {
            this.doListProcess('notice/paginate', page)
        },
    }
}
</script>
