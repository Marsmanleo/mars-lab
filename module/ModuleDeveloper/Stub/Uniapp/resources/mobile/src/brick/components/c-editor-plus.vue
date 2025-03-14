<template>
    <view class="tw-border tw-border-solid tw-border-gray-100 tw-p-2 tw-bg-white">
        <view v-for="(item,itemIndex) in items" :key="itemIndex" class="tw-bg-white">
            <view class="tw-flex">
                <view class="tw-flex-grow">
                    <text v-if="item.type==='text'">文字</text>
                    <text v-if="item.type==='image'">图片</text>
                    <text v-if="item.type==='images'">多图</text>
                </view>
                <view class="tw-text-right">
                    <view class="btn btn-round btn-sm tw-ml-1" v-if="itemIndex>0" @click="doMove('up',itemIndex)">
                        上移
                    </view>
                    <view class="btn btn-round btn-sm tw-ml-1" v-if="itemIndex<items.length-1" @click="doMove('down',itemIndex)">
                        下移
                    </view>
                    <view class="btn btn-round btn-sm tw-ml-1" v-if="itemIndex>0" @click="doMove('top',itemIndex)">
                        置顶
                    </view>
                    <view class="btn btn-round btn-sm tw-ml-1" @click="doMove('delete',itemIndex)">
                        删除
                    </view>
                </view>
            </view>
            <view class="tw-mt-2">
                <view v-if="item.type==='text'">
                    <uni-easyinput type="textarea" autoHeight v-model="item.data.content"
                                   @input="doTextInput(itemIndex,$event)" placeholder="请输入内容"/>
                </view>
                <view v-if="item.type==='image'">
                    <view v-if="item.data.image" @click="doImageReplace(item,itemIndex)"
                          class="tw-border tw-border-solid tw-border-gray-100 tw-rounded">
                        <image v-if="item.data.image" :src="item.data.image" class="tw-w-full" mode="widthFix"/>
                    </view>
                    <view v-else @click="doImageAdd(item,itemIndex)"
                          class="tw-border tw-border-solid tw-border-gray-100 tw-rounded">
                        <c-empty title="添加图片" icon="iconfont icon-plus"/>
                    </view>
                </view>
                <view v-if="item.type==='images'">
                    <view class="row">
                        <view class="col-4 tw-relative" v-for="(itemImage,itemImageIndex) in item.data.images" :key="itemImageIndex">
                            <view class="ub-cover-1-1 contain tw-border tw-border-solid tw-border-gray-100 tw-rounded"
                                  :style="{backgroundImage:`url(${itemImage})`}"></view>
                            <view
                                class="tw-w-5 tw-h-5 tw-leading-5 tw-text-center tw-rounded-full tw-bg-red-600 tw-text-white tw-absolute tw-right-0 tw-top-0"
                                @click="doImagesDelete(item,itemIndex,itemImageIndex)">
                                <text class="iconfont icon-close"></text>
                            </view>
                        </view>
                        <view class="col-4">
                            <view
                                class="ub-cover-1-1 tw-relative tw-border tw-border-solid tw-border-gray-100 tw-rounded"
                                @click="doImagesAdd(item,itemIndex)">
                                <view
                                    class="tw-absolute tw-left-0 tw-right-0 tw-top-0 tw-bottom-0 tw-flex tw-flex-col tw-justify-center tw-text-center">
                                    <view>
                                        <text class="iconfont icon-plus tw-text-gray-400"
                                              style="font-size:1rem;line-height:1rem;"></text>
                                    </view>
                                    <view class="tw-text-gray-400">
                                        添加图片
                                    </view>
                                </view>
                            </view>
                        </view>
                    </view>
                </view>
            </view>
        </view>
        <view class="tw-p-4 tw-bg-white tw-text-center tw-mt-2">
            <view class="row">
                <view class="col-4" @click="doAdd({type:'image',data:{image:''}})">
                    <view class="text">
                        <text class="iconfont icon-image" style="font-size:1rem;line-height:1rem;"></text>
                    </view>
                    <view>
                        大图
                    </view>
                </view>
                <view class="col-4" @click="doAdd({type:'images',data:{images:[]}})">
                    <view class="text">
                        <text class="iconfont icon-images" style="font-size:1rem;line-height:1rem;"></text>
                    </view>
                    <view>
                        多图
                    </view>
                </view>
                <view class="col-4" @click="doAdd({type:'text',data:{content:''}})">
                    <view class="text">
                        <text class="iconfont icon-description" style="font-size:1rem;line-height:1rem;"></text>
                    </view>
                    <view>
                        文字
                    </view>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
export default {
    name: "c-editor-plus",
    props: {
        value: {
            type: Array,
            default: []
        },
        imageUrl: {
            type: String,
            default: 'member_data/file_manager/image',
        },
    },
    computed: {
        items() {
            return this.value ? this.value : []
        }
    },
    methods: {
        doImageUpload(cb) {
            uni.chooseImage({
                count: 1,
                success: (res) => {
                    const path = res.tempFilePaths[0];
                    this.uploading = true
                    this.$dialog.loadingOn('正在上传...')
                    this.$api.postUpload(this.imageUrl, {
                        filePath: path,
                        formData: {
                            action: 'uploadDirect',
                            fullPath: true
                        }
                    }, res => {
                        this.$dialog.loadingOff()
                        cb(res)
                    }, res => {
                        this.$dialog.loadingOff()
                    })
                },
            })
        },
        doAdd(item) {
            let newItems = JSON.parse(JSON.stringify(this.items))
            newItems.push(item)
            this.$emit('input', newItems)
        },
        doTextInput(itemIndex, text) {
            let newItems = JSON.parse(JSON.stringify(this.items))
            newItems[itemIndex].data.content = text
            this.$emit('input', newItems)
        },
        doImageReplace(item, itemIndex) {
            this.$dialog.confirm('确认要替换该图片吗？', () => {
                this.doImageAdd(item, itemIndex)
            })
        },
        doImageAdd(item, itemIndex) {
            this.doImageUpload((res) => {
                let newItems = JSON.parse(JSON.stringify(this.items))
                newItems[itemIndex].data.image = res.data.fullPath
                this.$emit('input', newItems)
            })
        },
        doImagesAdd(item, itemIndex) {
            this.doImageUpload((res) => {
                let newItems = JSON.parse(JSON.stringify(this.items))
                newItems[itemIndex].data.images.push(res.data.fullPath)
                this.$emit('input', newItems)
            })
        },
        doImagesDelete(item, itemIndex, imageIndex) {
            let newItems = JSON.parse(JSON.stringify(this.items))
            newItems[itemIndex].data.images.splice(imageIndex, 1)
            this.$emit('input', newItems)
        },
        doMove(direction, index) {
            let newItems = JSON.parse(JSON.stringify(this.items)), pcs
            switch (direction) {
                case 'up':
                    if (index > 0) {
                        pcs = newItems.splice(index, 1)
                        newItems.splice(index - 1, 0, pcs[0])
                    }
                    break
                case 'down':
                    if (index < newItems.length - 1) {
                        pcs = newItems.splice(index, 1)
                        newItems.splice(index + 1, 0, pcs[0])
                    }
                    break
                case 'top':
                    if (index > 0) {
                        pcs = newItems.splice(index, 1)
                        newItems.splice(0, 0, pcs[0])
                    }
                    break
                case 'delete':
                    newItems.splice(index, 1)
                    break
            }
            this.$emit('input', newItems)
        }
    }
}
</script>

