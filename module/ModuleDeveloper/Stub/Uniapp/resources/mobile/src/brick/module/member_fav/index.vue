<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header :title="title"/>
        <view>
            <c-list-status-top :loading="listLoading" :records="list.records"/>
            <view v-for="(item,itemIndex) in list.records" :key="item.bizId" class="tw-bg-white tw-pt-4 tw-px-4">
                <view v-if="item.style==='coverTitle'&&item.cover">
                    <view
                        class="tw-pb-2 tw-flex tw-pb-4 tw-text-sm tw-border-0 tw-border-b tw-border-gray-100 tw-border-solid">
                        <view class="tw-mr-2 tw-w-10 tw-flex-shrink-0" @click="$r.to(item.url)">
                            <view class="tw-rounded ub-cover-1-1 contain tw-shadow"
                                  :style="{backgroundImage:'url('+item.cover+')'}"></view>
                        </view>
                        <view class="tw-flex-grow">
                            <view @click="$r.to(item.url)">
                                {{ item.title }}
                            </view>
                            <view class="ub-text-muted">
                                {{ item.created_at }}
                            </view>
                        </view>
                        <view class="tw-w-8 tw-text-right tw-flex tw-flex-col tw-justify-end"
                              @click="doCancel(item.biz,item.bizId)">
                            <text class="iconfont icon-star ub-text-primary"></text>
                        </view>
                    </view>
                </view>
                <view v-else>
                    <view class="tw-pb-2" @click="$r.to(item.url)">
                        {{ item.title }}
                    </view>
                    <view class="tw-flex tw-pb-4 tw-text-sm tw-border-0 tw-border-b tw-border-gray-100 tw-border-solid">
                        <view class="tw-flex-grow ub-text-muted">
                            {{ item.created_at }}
                        </view>
                        <view class="tw-w-8 tw-text-right" @click="doCancel(item.biz,item.bizId)">
                            <text class="iconfont icon-star ub-text-primary"></text>
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
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "index",
    mixins: [ListerMixin, StoreBaseUiMixin],
    data() {
        return {
            biz: '',
            title: '我的收藏'
        }
    },
    methods: {
        init() {
            this.biz = this.$r.getQuery('biz')
            this.$api.post('member_fav/info', {
                biz: this.biz
            }, res => {
                this.title = res.data.title
            })
            this.doSearch()
        },
        doList(page) {
            this.doListProcess('member_fav/paginate', page, {
                biz: this.biz
            })
        },
        doCancel(biz, bizId) {
            this.$api.post('member_fav/cancel', {
                biz,
                bizId
            }, res => {
                this.$dialog.tipSuccess('取消收藏成功')
                this.doList()
            })
        }
    }
}
</script>
