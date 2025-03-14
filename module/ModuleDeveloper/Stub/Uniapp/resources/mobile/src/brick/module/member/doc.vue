<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="pb-member-doc">
        <c-page-header :title="title"/>
        <c-loading v-if="loading" page/>
        <view v-else class="tw-bg-white tw-p-4" style="min-height:100vh;">
            <rich-text class="ub-html" :nodes="data.content || ''"/>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: 'doc',
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
                type: null,
                title: '',
                content: '',
            }
        }
    },
    methods: {
        init() {
            this.loading = true
            this.$dialog.loadingOn()
            this.$api.post('member_doc/get', {
                type: this.$r.getQuery('type')
            }, res => {
                this.loading = false
                this.$dialog.loadingOff()
                this.data = res.data
            })
        }
    }
}
</script>


<style lang="less">
.pb-member-doc {
    .cover {
        .image {
            width: 100%;
        }
    }

    .content {
    }
}
</style>
