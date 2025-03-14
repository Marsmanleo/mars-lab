<template>
    <view class="pb-image-input">
        <view class="item" :style="{backgroundImage:'url('+value+')'}" @tap.stop="doPreview">
            <view v-if="!!value" class="delete iconfont icon-close" @tap.stop="doDelete"></view>
            <view v-if="!value" class="add iconfont icon-plus" @tap.stop="doSelect"></view>
        </view>
    </view>
</template>

<script>

export default {
    name: 'c-image-input',
    props: {
        value: {
            type: String,
            default: '',
        },
        url: {
            type: String,
            default: 'member_data/file_manager/image',
        },
    },
    methods: {
        doDelete() {
            this.$emit('input','')
        },
        doSelect() {
            uni.chooseImage({
                count: 1,
                success: (res) => {
                    const path = res.tempFilePaths[0];
                    this.uploading = true
                    this.$dialog.loadingOn('正在上传...')
                    this.$api.postUpload(this.url, {
                        filePath: path,
                        formData: {
                            action: 'uploadDirect',
                            fullPath: true
                        }
                    }, res => {
                        this.$dialog.loadingOff()
                        this.$emit('input', res.data.fullPath)
                        // this.handleChangeProcess(res.data.fullPath)
                    }, res => {
                        this.$dialog.loadingOff()
                    })
                },
            })
        },
        doPreview() {
            uni.previewImage({
                current: 0,
                urls: [this.value]
            })
        }
    }
}
</script>

<style lang="less" scoped>
@import "./../../config/theme";

.pb-image-input {
    padding: 20rpx 20rpx 20rpx 0;

    .item {
        width: unit(140, rpx);
        height: unit(140, rpx);
        border: 1px solid #EEE;
        border-radius: 3px;
        background: #FFF no-repeat center;
        background-size: contain;
        position: relative;

        .add {
            line-height: unit(140,rpx);
            width: unit(140,rpx);
            text-align: center;
            font-size: unit(30,rpx);
        }

        .delete {
            width: unit(40,rpx);
            height: unit(40,rpx);
            background: red;
            color: #FFF;
            display: block;
            position: absolute;
            top: unit(-20,rpx);
            right: unit(-20,rpx);
            border-radius: 50%;
            line-height: unit(40,rpx);
            text-align: center;
        }
    }
}
</style>
