<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="ub-h-full">
        <c-page-header title="分享好友"></c-page-header>
        <c-loading v-if="loading" page/>
        <view v-else class="tw-p-2">
            <view class="ub-content-box margin-bottom tw-text-center">
                <image :src="image" mode="widthFix"/>
            </view>
            <view class="ub-content-box margin-bottom">
                <view>
                    分享说明
                </view>
                <view>
                    <rich-text class="ub-html" :nodes="content || ''"/>
                </view>
            </view>
            <view class="tw-h-20"></view>
            <view class="tw-fixed ub-content-bg tw-p-3 tw-left-0 tw-right-0 tw-bottom-0">
                <view class="btn btn-lg btn-round btn-primary btn-block">
                    长按保存图片到相册
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "bonus_log",
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            loading: true,
            content: '',
            image: '',
        }
    },
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    methods: {
        init() {
            this.$api.post('member_invite/share', {}, (res) => {
                this.loading = false
                this.content = res.data.content
                this.image = res.data.image
            })
        },
    }
}
</script>

