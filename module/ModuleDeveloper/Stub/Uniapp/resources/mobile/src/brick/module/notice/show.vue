<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="pb-news-view">
        <c-page-header :title="title"/>
        <c-loading page v-if="loading"/>
        <view class="tw-bg-white tw-p-4">
            <rich-text class="ub-html" :nodes="data.content || ''"/>
        </view>
    </view>
</template>

<script>
import {HtmlUtil} from "../../lib/html";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: 'news_view',
    mixins: [StoreBaseUiMixin],
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    computed: {
        title() {
            if (this.loading) {
                return '正在加载...'
            }
            return this.data.title
        }
    },
    data() {
        return {
            loading: true,
            data: {
                id: null,
                title: '',
                cover: '',
                content: '',
            }
        }
    },
    methods: {
        init() {
            this.loading = true
            this.$dialog.loadingOn()
            this.$api.post('notice/get', {id: this.$r.getQuery('id')}, res => {
                this.loading = false
                this.$dialog.loadingOff()
                res.data.record.content = HtmlUtil.adapt(res.data.record.content)
                this.data = res.data.record
            })
        }
    }
}
</script>

<style lang="less">
.pb-news-view {
    .cover {
        .image {
            width: 100%;
        }
    }

    .content {
    }
}
</style>
