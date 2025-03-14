<template>
    <c-modal ref="modal" :title="title">
        <view>
            <c-image :src="src" preview @on-preview="onPreview"/>
        </view>
        <view class="tw-text-center margin-top" v-if="!!download">
            <view class="btn btn-round btn-lg" @click="doDownload">
                <text class="iconfont icon-download tw-mr-1"></text>
                点击下载
            </view>
        </view>
    </c-modal>
</template>

<script>
import {DateUtil} from "../lib/date-util";

export default {
    name: "c-image-preview",
    props: {
        download: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            src: '',
            title: ''
        }
    },
    methods: {
        show(src, title) {
            title = title || '图片预览'
            this.src = src;
            this.title = title;
            this.$refs.modal.show();
        },
        hide() {
            this.$refs.modal.close();
        },
        onPreview() {
            this.$refs.modal.close();
        },
        doDownload() {
            let ext = 'png'
            if (this.download) {
                ext = this.download.split('.').pop()
            }
            // console.log('doDownload', this.src)
            // #ifdef H5
            const a = document.createElement('a');
            a.href = this.src;
            a.target = '_blank';
            a.download = this.download
            a.click();
            // #endif
            // #ifdef MP-WEIXIN
            const isBase64 = (this.src.indexOf('data:image') === 0);
            this.$dialog.loadingOn('保存中...')
            const downloadFail = (res) => {
                console.error('downloadFail.fail', res)
                this.$dialog.loadingOff()
                this.$dialog.tipError('保存失败')
            }
            const downloadSuccess = () => {
                this.$dialog.loadingOff()
                this.$dialog.tipSuccess('保存成功')
            }
            if (isBase64) {
                const fileManager = uni.getFileSystemManager();
                const path = `${wx.env.USER_DATA_PATH}/${DateUtil.timestampInMs()}.${ext}`;
                fileManager.writeFile({
                    filePath: path,
                    data: this.src.replace(/^data:image\/\w+;base64,/, ''),
                    encoding: 'base64',
                    success: (res) => {
                        uni.saveImageToPhotosAlbum({
                            filePath: path,
                            success: () => {
                                fileManager.unlink({
                                    filePath: path
                                })
                                downloadSuccess()
                            },
                            fail: downloadFail
                        })
                    },
                    fail: downloadFail
                });
            } else {
                uni.downloadFile({
                    url: this.src,
                    success: (res) => {
                        if (res.statusCode === 200) {
                            uni.saveImageToPhotosAlbum({
                                filePath: res.tempFilePath,
                                success: downloadSuccess,
                                fail: downloadFail
                            })
                        } else {
                            downloadFail(res)
                        }
                    },
                    fail: downloadFail
                });
            }
            // #endif
        }
    }
}
</script>

<style scoped>

</style>
