<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>

        <c-page-header title="修改头像"/>

        <view class="pb-member-profile-avatar ub-content-bg ub-h-full">
            <view class="ub-padding">
                <view class="tw-rounded tw-w-1_2 ub-cover-1-1 tw-m-auto tw-bg-gray-200"
                      :style="{'background-image':'url('+user.avatarBig+')'}"></view>
            </view>
            <view class="tw-p-10">
                <button class="btn btn-primary btn-lg btn-round btn-block margin-bottom" :disabled="loading"
                        :loading="loading"
                        @click="$refs.avatar.doSelectImage()">
                    <text class="iconfont icon-image tw-mr-2"></text>
                    选择图片
                </button>
                <!-- #ifdef MP-WEIXIN -->
                <button class="btn btn-primary btn-lg btn-round btn-block margin-bottom"
                        :disabled="loading" :loading="loading"
                        open-type="chooseAvatar"
                        @chooseavatar="onChooseAvatar"
                >
                    <text class="iconfont icon-wechat tw-mr-2"></text>
                    从选择微信头像
                </button>
                <!-- #endif -->
            </view>
            <ImageCropper @save="doSave" ref="avatar"/>
        </view>

    </view>

</template>

<script>
import ImageCropper from "../../components/ImageCropper";
import {EventBus} from "../../lib/event-bus";
import {mapState} from 'vuex'
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    components: {ImageCropper},
    computed: mapState({
        user: (state) => state.user.user
    }),
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            loading: false
        }
    },
    onShow() {
        this.$starter.boot()
    },
    methods: {
        doSave(res) {
            this.loading = true
            this.$api.post('member_profile/avatar', {type: 'cropper', avatar: res.data}, res => {
                this.loading = false
                this.$dialog.tipSuccess('修改成功')
                EventBus.$emitDelay('UpdateApp')
            }, res => {
                this.loading = false
            })
        },
        // #ifdef MP-WEIXIN
        onChooseAvatar(e) {
            this.$dialog.loadingOn()
            this.$api.postUpload('member_profile/avatar', {
                filePath: e.detail.avatarUrl,
                name: 'avatar',
                formData: {
                    type: 'file',
                }
            }, res => {
                this.$dialog.loadingOff()
                this.$dialog.tipSuccess('修改成功')
                EventBus.$emitDelay('UpdateApp')
            }, res => {
                this.$dialog.loadingOff()
            })
        }
        // #endif
    }
}
</script>

<style lang="less">
.pb-member-profile-avatar {
    text-align: center;
    padding: 1rem;

    .avatar-preview {
        width: 300 rpx;
        height: 300 rpx;
    }
}
</style>
