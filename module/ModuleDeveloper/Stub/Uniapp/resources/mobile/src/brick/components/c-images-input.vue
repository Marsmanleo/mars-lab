<template>
    <view class="pb-images-input">
        <block v-for="(item,itemIndex) in value" :key="itemIndex">
            <view class="item" :style="{backgroundImage:'url('+item+')'}" @tap.stop="doPreview(itemIndex)">
                <view class="delete iconfont icon-close" @tap.stop="doDelete(itemIndex)"></view>
            </view>
        </block>
        <view class="item" v-if="value.length<limit">
            <view class="add iconfont icon-plus" @tap.stop="doSelect"></view>
        </view>
    </view>
</template>

<script>
    export default {
        name: 'c-images-input',
        props: {
            value:{
                type:Array,
                default:()=>{
                    return []
                }
            },
            url: {
                type: String,
                default: 'member_data/file_manager/image',
            },
            limit: {
                type: Number,
                default: 5,
            }
        },
        methods: {
            doDelete(index) {
                let images = JSON.parse(JSON.stringify(this.value))
                images.splice(index, 1)
                this.$emit('input',images)
            },
            doSelect() {
                if (this.value.length >= this.limit) {
                    return
                }
                const max = this.limit - this.value.length
                uni.chooseImage({
                    count: max,
                    success: (res) => {
                        let tempFilePaths = res.tempFilePaths;
                        if (tempFilePaths.length > max) {
                            this.$dialog.tipError(`最多只能 ${max} 个图片`)
                            return
                        }
                        const total = tempFilePaths.length
                        let current = 0
                        const upload = () => {
                            if (tempFilePaths.length === 0) {
                                this.$dialog.loadingOff()
                                this.$dialog.tipSuccess('上传完成')
                                return
                            }
                            current++;
                            this.$dialog.loadingOn(`正在上传 ${current}/${total}`)
                            this.$api.postUpload(this.url, {
                                filePath: tempFilePaths.shift(),
                                formData: {
                                    action: 'uploadDirect',
                                    fullPath: true,
                                }
                            }, res => {
                                let images = JSON.parse(JSON.stringify(this.value))
                                images.push(res.data.fullPath)
                                this.$emit('input',images)
                                upload()
                            }, res => {
                                upload()
                            })
                        }
                        upload()
                    },
                })
            },
            doPreview(index) {
                uni.previewImage({
                    current: index,
                    urls: this.value
                })
            }
        }
    }
</script>

<style lang="less" scoped>
    @import "./../../config/theme";

    .pb-images-input {
        padding: 20rpx 20rpx 0 0;
        overflow: hidden;

        .item {
            width: unit(140, rpx);
            height: unit(140, rpx);
            border: 1px solid #EEE;
            border-radius: 3px;
            background: #FFF no-repeat center;
            background-size: contain;
            position: relative;
            float: left;
            margin: 0 20rpx 20rpx 0;

            .add {
                line-height: unit(140, rpx);
                width: unit(140, rpx);
                text-align: center;
                font-size: unit(30, rpx);
            }

            .delete {
                width: unit(40, rpx);
                height: unit(40, rpx);
                background: red;
                color: #FFF;
                display: block;
                position: absolute;
                top: unit(-20, rpx);
                right: unit(-20, rpx);
                border-radius: 50%;
                line-height: unit(40, rpx);
                text-align: center;
            }
        }
    }
</style>
