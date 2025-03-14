<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header :title="title">
            <block slot="action">
                <view class="item iconfont icon-upload" v-if="mode==='manager' || mode==='fileSelect'"
                      @tap.stop="doUpload" :style="{fontSize:'58rpx'}"></view>
            </block>
        </c-page-header>
        <view class="pb-data-manage-toolbar">
            <view class="left">
                <view class="item iconfont icon-folder-add" @tap.stop="doEditCategory"></view>
                <view v-if="currentCategoryId>0" class="item iconfont icon-angle-up"
                      @tap.stop="doChangeCategoryUp"></view>
                <scroll-view scroll-x class="item scroll">
                    <block v-for="(item,itemIndex) in categoryChain" :key="item.id">
                        <view class="category" @tap.stop="doChangeCategory(item)">{{ item.name }}</view>
                    </block>
                </scroll-view>
            </view>
            <view class="right" v-if="mode==='manage'">
                <view class="empty"></view>
                <view class="item iconfont icon-check" v-if="manageMode==='view'"
                      @tap.stop="doChangeManageMode('select')"></view>
                <view class="item iconfont icon-close" v-if="manageMode==='select'"
                      @tap.stop="doChangeManageMode('view')"></view>
            </view>
        </view>
        <view class="pb-data-manage-toolbar-placeholder"></view>
        <view class="pb-data-manage-list">
            <view class="item" v-for="(item,itemIndex) in currentCategories" :key="item.id">
                <view class="box" :class="selectedCategoryIds.includes(item.id)?'selected':''"
                      @click="doCategoryClick(item,false)" @longpress="doCategoryClick(item,true)">
                    <view class="thumb iconfont icon-folder"></view>
                    <view class="name">{{ item.name }}</view>
                    <view v-if="(mode==='manage' && manageMode==='select')"
                          class="checked iconfont icon-selectcheckboxpre"></view>
                </view>
            </view>
            <view class="item" v-for="(item,itemIndex) in list.records" :key="item.id">
                <view class="box" :class="selectedFileIds.includes(item.id)?'selected':''"
                      @click="doFileClick(item,false)" @longpress="doFileClick(item,true)">
                    <view v-if="previewImage(item)" class="cover"
                          :style="{backgroundImage:'url('+item.preview+')'}"></view>
                    <view v-else class="thumb iconfont icon-fill222"></view>
                    <view class="name">{{ item.filename }}</view>
                    <view v-if="(mode==='manage' && manageMode==='select')||(mode==='fileSelect')"
                          class="checked iconfont icon-selectcheckboxpre"></view>
                </view>
            </view>
        </view>
        <c-list-status ref="listStatus" :loading="listLoading" :records="list.records" :total="list.total"
                       :simple="true"/>

        <view
            v-if="(mode==='manage' && manageMode==='select' && (selectedFileIds.length+selectedCategoryIds.length>0)) || (mode==='folderSelect') || (mode==='fileSelect')"
            class="pb-data-manage-select-confirm-placeholder"></view>
        <view
            v-if="(mode==='manage' && manageMode==='select' && (selectedFileIds.length+selectedCategoryIds.length>0)) || (mode==='folderSelect') || (mode==='fileSelect')"
            class="pb-data-manage-select-confirm">
            <view class="item" v-if="mode==='manage'" :class="isEditEnable?'':'disabled'" @tap.stop="doEdit">
                <view class="icon iconfont icon-edit"></view>
                <view class="text">修改</view>
            </view>
            <view class="item" v-if="mode==='manage'" :class="isDeleteEnable?'':'disabled'" @tap.stop="doDelete">
                <view class="icon iconfont icon-trash"></view>
                <view class="text">删除</view>
            </view>
            <view v-if="mode==='folderSelect'" class="item" @tap.stop="doFolderSelectConfirm">
                <view class="icon iconfont icon-check"></view>
                <view class="text">确认</view>
            </view>
            <view v-if="mode==='manage'" :class="isMoveEnable?'':'disabled'" class="item" @tap.stop="doMove">
                <view class="icon iconfont icon-right"></view>
                <view class="text">移动</view>
            </view>
            <view v-if="mode==='fileSelect'" class="item" @click="doFileSelectConfirm">
                <view class="icon iconfont icon-check text-primary" :style="{fontSize:'50rpx'}"></view>
                <view class="text">确认({{ selectedFileIds.length }})</view>
            </view>

            <view v-if="false" class="item" @tap.stop="confirmMoreVisible=!confirmMoreVisible">
                <view class="icon iconfont icon-ellipsis2"></view>
                <view class="text">更多</view>
            </view>
            <div class="more-menu-mask" v-if="false && confirmMoreVisible" @tap.stop="confirmMoreVisible=false">
                <view class="more-menu">
                    <view class="more-menu-item disabled">重命名</view>
                    <view class="more-menu-item">重命名</view>
                </view>
            </div>
        </view>

        <TuiModal :show="categoryEditDialogVisible" :custom="true">
            <view>
                <view class="flex">
                    <view class="flex-sub">输入分类名称</view>
                </view>
                <view>
                    <c-input v-model="categoryEdit.title" placeholder="点击填写"></c-input>
                </view>
                <view class="flex padding-top">
                    <button class="flex-sub cu-btn bg-gray lg" @tap.stop="categoryEditDialogVisible=false">取消</button>
                    <view :style="{width:'20rpx'}"></view>
                    <button class="flex-sub cu-btn primary lg" @tap.stop="doEditCategorySubmit">确定</button>
                </view>
            </view>
        </TuiModal>

    </view>
</template>

<script>
import {RouterStartForCallbackMixin} from "../../components/Common/mixins/router";
import {StoreBaseUiMixin, StoreUserMixin} from "../../components/Common/mixins/store";
import TuiModal from "../../tui/modal/modal";

export default {
    name: 'data_manage',
    components: {TuiModal},
    mixins: [RouterStartForCallbackMixin, StoreUserMixin, StoreBaseUiMixin],
    onReady() {
        this.$starter.boot(() => {
            this.mode = this.$r.getQuery('mode')
            if (!['manage', 'fileSelect', 'folderSelect'].includes(this.mode)) {
                this.mode = 'manage'
            }
            this.user.id && this.init()
        })
    },
    computed: {
        // 启用条件
        // - 1个分类0个文件
        isEditEnable() {
            return (this.selectedCategoryIds.length === 1 && !this.selectedFileIds.length)
        },
        // 启用条件
        // - 1个分类0个文件
        // - 0个分类>=1个文件
        isDeleteEnable() {
            return (this.selectedCategoryIds.length === 1 && !this.selectedFileIds.length) || (!this.selectedCategoryIds.length && this.selectedFileIds.length)
        },
        // 启用条件
        // - 1个分类0个文件
        // - 0个分类>=1个文件
        isMoveEnable() {
            return (this.selectedCategoryIds.length === 1 && !this.selectedFileIds.length) || (!this.selectedCategoryIds.length && this.selectedFileIds.length)
        },
        title() {
            if (this.config && this.config.title) {
                return this.config.title
            }
            switch (this.mode) {
                case 'fileSelect':
                    switch (this.category) {
                        case 'image':
                            return '选择图片'
                        default:
                            return '选择文件'
                    }
                case 'folderSelect':
                    return '选择目录'
                default:
                    switch (this.category) {
                        case 'image':
                            return '图片管理'
                        default:
                            return '文件管理'
                    }
            }
        },
        apiUrl() {
            return this.url + '/' + this.category
        },
        currentCategories() {
            return this.categories.filter(o => o.pid === this.currentCategoryId)
        },
        categoryChain() {
            let chain = []
            let current = this.currentCategoryId
            for (let i = 0; i < 1000; i++) {
                if (current === 0) {
                    break
                }
                for (let j = 0; j < this.categories.length; j++) {
                    if (this.categories[j].id === current) {
                        chain.push(this.categories[j])
                        current = this.categories[j].pid
                        break
                    }
                }
            }
            chain.push({id: 0, name: '所有'})
            return chain.reverse()
        }
    },
    watch: {
        user: {
            handler(n, o) {
                n.id && this.init()
            }
        },
        currentCategoryId: {
            handler(n, o) {
                this.doList(1)
            },
            immediate: true
        }
    },
    data() {
        return {
            url: 'member_data/select_dialog',
            category: 'image',
            // fileSelect   : 文件选择（一个或多个）
            // folderSelect : 文件夹选择（一个，用于移动文件时使用）
            // manage       : 文件管理
            mode: null,
            // view         : 查看模式
            // select       : 选择模式
            manageMode: 'view',
            confirmMoreVisible: false,

            // category
            isCategoryInit: false,
            currentCategoryId: 0,
            categories: [],
            selectedCategoryIds: [],
            categoryEditDialogVisible: false,
            categoryEdit: {
                id: 0,
                pid: 0,
                title: '',
            },

            listLoading: false,
            list: {
                page: 1,
                pageSize: 20,
                records: [],
                total: 0,
            },
            selectedFileIds: [],
        }
    },
    onPullDownRefresh(e) {
        this.categories = []
        this.initCategory()
        this.doList(1)
    },
    onReachBottom(e) {
        this.doNextPage()
    },
    methods: {
        init() {
            this.$refs.listStatus && this.$refs.listStatus.init()
            !this.isCategoryInit && this.initCategory()
        },
        doNextPage() {
            if (!this.$refs.listStatus.shouldNext()) {
                return
            }
            this.doList(this.list.page + 1)
        },
        doList(page) {
            if (!this.mode) {
                setTimeout(() => {
                    this.doList(page)
                }, 100)
                return
            }
            if (!['manage', 'fileSelect'].includes(this.mode)) {
                return
            }
            page = page || this.list.page
            this.list.page = page
            if (this.list.page === 1) {
                this.list.records = []
            }
            this.listLoading = true
            this.$api.post(this.apiUrl, {
                action: 'list',
                categoryId: this.currentCategoryId === 0 ? -1 : this.currentCategoryId,
                page: this.list.page,
                pageSize: this.list.pageSize
            }, res => {
                this.listLoading = false
                uni.stopPullDownRefresh()
                this.list.page = res.data.page
                this.list.pageSize = res.data.pageSize
                if (this.list.page === 1) {
                    this.list.records = []
                }
                this.list.records = this.list.records.concat(res.data.list)
                this.list.total = res.data.total
                if (res.data.list.length !== res.data.pageSize) {
                    this.$refs.listStatus && this.$refs.listStatus.noMore()
                }
            }, res => {
                this.listLoading = false
                uni.stopPullDownRefresh()
            })
        },
        doFileClick(item, isLongPress) {
            // console.log('doCategoryClick', this.mode, this.manageMode, JSON.stringify(item))
            switch (this.mode) {
                case 'manage':
                    switch (this.manageMode) {
                        case 'view':
                            if (isLongPress) {
                                this.doChangeManageMode('select')
                                this.selectedFileIds.push(item.id)
                            } else {
                                switch (item.type) {
                                    case 'png':
                                    case 'jpg':
                                    case 'jpeg':
                                        uni.previewImage({
                                            current: 0,
                                            urls: [item.preview]
                                        })
                                        break
                                    default:
                                        this.$dialog.tipError('该文件在不支持预览')
                                }
                            }
                            break
                        case 'select':
                            const found = this.selectedFileIds.indexOf(item.id)
                            if (found >= 0) {
                                this.selectedFileIds.splice(found, 1)
                                if (this.selectedCategoryIds.length + this.selectedFileIds.length === 0) {
                                    this.doChangeManageMode('view')
                                }
                            } else {
                                this.selectedFileIds.push(item.id)
                            }
                            break
                    }
                    break
                case 'fileSelect':
                    const found = this.selectedFileIds.indexOf(item.id)
                    if (found >= 0) {
                        this.selectedFileIds.splice(found, 1)
                    } else {
                        if (this.config && this.config.max && this.config.max > 0 && this.selectedFileIds.length >= this.config.max) {
                            this.$dialog.tipError(`最多选择${this.config.max}个文件`)
                            return
                        }
                        this.selectedFileIds.push(item.id)
                    }
                    break
            }
        },
        doUpload() {
            switch (this.category) {
                case 'image':
                    uni.chooseImage({
                        success: (res) => {
                            const tempFilePaths = res.tempFilePaths;
                            const total = tempFilePaths.length
                            let current = 0
                            const upload = () => {
                                if (tempFilePaths.length === 0) {
                                    this.$dialog.loadingOff()
                                    this.$dialog.tipSuccess('上传完成', () => {
                                        this.doList(1)
                                    })
                                    return
                                }
                                current++;
                                this.$dialog.loadingOn(`正在上传 ${current}/${total}`)
                                this.$api.postUpload(this.apiUrl, {
                                    filePath: tempFilePaths.shift(),
                                    formData: {
                                        action: 'upload_file',
                                        categoryId: this.currentCategoryId,
                                    }
                                }, res => {
                                    upload()
                                }, res => {
                                    upload()
                                })
                            }
                            upload()
                        },
                    })
                    break
                default:
                    this.$dialog.tipError('暂不支持')
                    break
            }
        },
        initCategory() {
            this.isCategoryInit = true
            this.$dialog.loadingOn()
            this.$api.post(this.apiUrl, {action: 'category'}, res => {
                this.$dialog.loadingOff()
                this.categories = res.data.categories
            })
        },
        doCategoryClick(item, isLongPress) {
            // console.log('doCategoryClick', isLongPress, this.mode, this.manageMode, JSON.stringify(item))
            switch (this.mode) {
                case 'manage':
                    switch (this.manageMode) {
                        case 'view':
                            if (isLongPress) {
                                this.doChangeManageMode('select')
                                this.selectedCategoryIds.push(item.id)
                            } else {
                                this.currentCategoryId = item.id
                                this.selectedCategoryIds = []
                            }
                            break
                        case 'select':
                            const found = this.selectedCategoryIds.indexOf(item.id)
                            if (found >= 0) {
                                this.selectedCategoryIds.splice(found, 1)
                                if (this.selectedCategoryIds.length + this.selectedFileIds.length === 0) {
                                    this.doChangeManageMode('view')
                                }
                            } else {
                                this.selectedCategoryIds.push(item.id)
                            }
                            break
                    }
                    break
                case 'folderSelect':
                    this.currentCategoryId = item.id
                    break
                case 'fileSelect':
                    this.currentCategoryId = item.id
                    this.selectedFileIds = []
                    break
            }
        },
        doChangeManageMode(manageMode) {
            this.manageMode = manageMode
            this.selectedCategoryIds = []
            this.selectedFileIds = []
            switch (this.manageMode) {
                case 'view':
                    break
                case 'select':
                    break
            }
        },
        doChangeCategory(item) {
            this.currentCategoryId = item.id
            this.selectedCategoryIds = []
        },
        doChangeCategoryUp() {
            for (let i = 0; i < this.categories.length; i++) {
                if (this.categories[i].id === this.currentCategoryId) {
                    this.currentCategoryId = this.categories[i].pid
                    break
                }
            }
            this.selectedCategoryIds = []
        },
        previewImage(item) {
            switch (item.type) {
                case 'png':
                case 'jpg':
                case 'jpeg':
                    return item.preview
            }
            return null
        },
        doDelete() {
            if (!this.isDeleteEnable) {
                return
            }
            if (this.selectedCategoryIds.length === 1) {
                this.doDeleteCategory()
            } else if (this.selectedFileIds.length > 0) {
                this.doDeleteFile()
            }
        },
        doDeleteCategory() {
            this.$dialog.confirm('确认删除（所有文件会被移动到根分类）？', () => {
                this.$api.post(this.apiUrl, {action: 'categoryDelete', id: this.selectedCategoryIds[0]}, res => {
                    this.$dialog.loadingOff()
                    this.doChangeManageMode('view')
                    this.$dialog.tipSuccess('删除成功', () => {
                        this.initCategory()
                        this.currentCategoryId === 0 && this.doList(1)
                    })
                })
            })
        },
        doDeleteFile() {
            this.$dialog.confirm('确认删除（所有引用到文件的地方都不可用）？', () => {
                this.$dialog.loadingOn()
                this.$api.post(this.apiUrl, {
                    'action': 'fileDelete',
                    id: this.selectedFileIds.join(',')
                }, res => {
                    this.$dialog.loadingOff()
                    this.$dialog.tipSuccess('删除成功')
                    this.doChangeManageMode('view')
                    this.doList(1)
                })
            })
        },
        doEdit() {
            if (!this.isEditEnable) {
                return
            }
            if (this.selectedCategoryIds.length === 1) {
                this.doEditCategory()
            } else if (this.selectedFileIds.length === 1) {
                this.doEditFile()
            }
        },
        doEditCategoryAssign() {
            if (this.selectedCategoryIds.length > 0) {
                const founds = this.categories.filter(o => o.id === this.selectedCategoryIds[0]);
                if (founds.length !== 1) {
                    this.$dialog.tipError('分类不存在')
                    return
                }
                this.categoryEdit.id = founds[0].id
                this.categoryEdit.pid = founds[0].pid
                this.categoryEdit.title = founds[0].name
            } else {
                this.categoryEdit.id = 0
                this.categoryEdit.pid = this.currentCategoryId
                this.categoryEdit.title = ''
            }
        },
        doEditCategory() {
            this.doEditCategoryAssign()
            this.categoryEditDialogVisible = true
        },
        doEditCategorySubmit() {
            this.$dialog.loadingOn()
            this.$api.post(this.apiUrl, Object.assign(this.categoryEdit, {action: 'categoryEdit'}), res => {
                this.$dialog.loadingOff()
                this.categoryEditDialogVisible = false
                this.doChangeManageMode('view')
                this.$dialog.tipSuccess('保存成功', () => {
                    this.initCategory()
                })
            })
        },
        doEditFile() {
            //TODO
        },
        doEditFileSubmit() {
            //TODO
        },
        doMove() {

            if (!this.isMoveEnable) {
                return
            }
            if (this.selectedCategoryIds.length) {
                this.doMoveCategory()
            } else if (this.selectedFileIds.length) {
                this.doMoveFile()
            }
        },
        doMoveCategory() {
            this.doEditCategoryAssign()
            if (!this.categoryEdit.id) {
                return
            }
            this.$r.startForCallback('/brick/module/system/data_manage', 0, {title: '选择需要移动到的目录'}, (data) => {
                this.categoryEdit.pid = data
                this.$dialog.loadingOn()
                this.$api.post(this.apiUrl, Object.assign(this.categoryEdit, {action: 'categoryEdit'}), res => {
                    this.$dialog.loadingOff()
                    this.doChangeManageMode('view')
                    this.$dialog.tipSuccess('移动成功', () => {
                        this.initCategory()
                    })
                })
            }, () => {
                this.$dialog.tipError('操作已取消')
            }, {mode: 'folderSelect'})
        },
        doMoveFile() {
            this.$r.startForCallback('/brick/module/system/data_manage', 0, {title: '选择需要移动到的目录'}, (data) => {
                this.$dialog.loadingOn()
                this.$api.post(this.apiUrl, {
                    action: 'fileEdit',
                    id: this.selectedFileIds.join(','),
                    categoryId: data
                }, res => {
                    this.$dialog.loadingOff()
                    this.doChangeManageMode('view')
                    this.$dialog.tipSuccess('移动成功', () => {
                        this.doList(1)
                    })
                })
            }, () => {
                this.$dialog.tipError('操作已取消')
            }, {mode: 'folderSelect'})
        },
        doFolderSelectConfirm() {
            this.callbackConfirm(this.currentCategoryId)
        },
        doFileSelectConfirm() {
            let records = this.list.records.filter(o => this.selectedFileIds.includes(o.id)).map(o => {
                    return {
                        category: o.category,
                        filename: o.filename,
                        path: o.path,
                        preview: o.preview,
                        type: o.type,
                    }
                }
            )
            if (this.config && this.config.min && this.config.min > 0 && this.selectedFileIds.length < this.config.min) {
                this.$dialog.tipError(`最少选择${this.config.min}个文件`)
                return
            }
            this.callbackConfirm(records)
        }
    }
}
</script>

<style lang="less" scoped>
@import "./styles/data_manage";
</style>
