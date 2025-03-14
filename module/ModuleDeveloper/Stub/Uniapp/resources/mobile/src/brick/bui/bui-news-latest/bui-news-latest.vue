<template>
    <view class="ub-panel">
        <view class="head">
            <view class="more" @tap.stop="$r.to('/brick/module/news/index')">
                <view class="iconfont icon-ellipsis tw-transform tw-scale-150"></view>
            </view>
            <view class="title">
                新闻资讯
            </view>
        </view>
        <view class="body" style="padding:0 0.75rem 0.75rem 0.75rem;">
            <NewsItem v-for="(item,itemIndex) in list" :key="item.id" :item="item"
                      class="ub-border-bottom"
            />
        </view>
    </view>
</template>

<script>
import NewsItem from "../../module/news/components/NewsItem";

export default {
    name: "bui-news-latest",
    components: {NewsItem},
    data() {
        return {
            loading: true,
            list: []
        }
    },
    mounted() {
        this.init()
    },
    methods: {
        init() {
            this.loading = true
            this.$api.post('news/latest', {}, res => {
                this.loading = false
                this.list = res.data.records
            })
        }
    }
}
</script>
