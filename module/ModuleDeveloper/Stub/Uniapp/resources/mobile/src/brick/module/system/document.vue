<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header :title="title"/>
        <view class="bg-white padding">
            <rich-text class="ub-html" :nodes="data.content || ''"/>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
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
            this.$api.post('document/get', {id: this.$r.getQuery('id')}, res => {
                this.loading = false
                this.$dialog.loadingOff()
                this.data = res.data
            })
        }
    }
}
</script>
