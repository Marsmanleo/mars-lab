<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-content-bg ub-h-full">
        <c-page-header :title="title"/>
        <c-loading v-if="loading" page/>
        <view v-if="data.cover" class="ub-content-bg">
            <image class="image" mode="widthFix" style="width:100%" :src="data.cover"></image>
        </view>
        <view class="ub-content-bg tw-p-4">
            <rich-text class="ub-html" :nodes="data.content || ''"/>
        </view>
    </view>
</template>

<script>
import {HtmlUtil} from "../../lib/html";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: 'show',
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
            this.$api.post('news/get', {id: this.$r.getQuery('id')}, res => {
                this.loading = false
                this.$dialog.loadingOff()
                res.data.content = HtmlUtil.adapt(res.data.content)
                this.data = res.data
            })
        }
    }
}
</script>
