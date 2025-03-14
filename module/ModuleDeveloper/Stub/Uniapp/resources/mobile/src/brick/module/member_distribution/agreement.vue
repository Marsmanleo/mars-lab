<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-h-full">
        <c-page-header :title="title"/>
        <c-loading v-if="loading" page/>
        <view class="ub-content-box">
            <rich-text class="ub-html" :nodes="data.content || ''"/>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "agreement",
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
                title: '',
                content: '',
            }
        }
    },
    methods: {
        init() {
            this.loading = true
            this.$dialog.loadingOn()
            this.$api.post('member_distribution/agreement', {}, res => {
                this.loading = false
                this.$dialog.loadingOff()
                this.data = res.data
            })
        }
    }
}
</script>
