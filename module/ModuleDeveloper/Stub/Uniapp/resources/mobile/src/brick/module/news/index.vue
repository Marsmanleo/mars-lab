<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="">

        <c-page-header title="新闻资讯"/>

        <view>
            <c-list-status-top :loading="listLoading" :records="list.records"/>
            <view class="ub-content-bg tw-rounded tw-m-2 tw-px-3" v-for="(item,itemIndex) in list.records"
                  :key="item.id">
                <NewsItem :item="item"/>
            </view>
            <c-list-status ref="listStatus" :loading="listLoading" :page="list.page" :records="list.records"/>
        </view>

    </view>
</template>

<script>

import {ListerMixin} from "../../components/Common/mixins/lister";
import NewsItem from "./components/NewsItem";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "index",
    components: {NewsItem},
    mixins: [ListerMixin, StoreBaseUiMixin],
    onShow() {
        this.$starter.boot()
    },
    data() {
        return {}
    },
    methods: {
        doList(page) {
            this.doListProcess('news', page)
        },
    }
}
</script>
