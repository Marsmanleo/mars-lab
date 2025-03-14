<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header :title="pageTitle" titleAlign="center" :hasAction="true" :customBackCallback="true"
                       @back="doCancel">
            <block slot="action">
                <text class="item ub-text-primary" @click="mode='edit'" v-if="mode==='view'">
                    编辑
                </text>
                <text class="item ub-text-primary" @click="doSave" v-if="mode==='edit'">
                    保存
                </text>
            </block>
        </c-page-header>
        <view :class="{'rich-view-editor-hide':mode==='edit'}">
            <view class="tw-p-4">
                <view class="view-container">
                    <rich-text v-if="!!value" class="ub-html" :nodes="value"/>
                    <c-empty v-else/>
                </view>
            </view>
        </view>
        <view :class="{'rich-view-editor-hide':mode==='view'}">
            <view class="tw-p-4">
                <view class="editor-container">
                    <editor id="editor" class="ql-container" @input="onInput" :placeholder="placeholder" show-img-size
                            show-img-toolbar show-img-resize @ready="onEditorReady"></editor>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {RouterStartForCallbackMixin} from "../../components/Common/mixins/router";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "rich_view_editor",
    mixins: [RouterStartForCallbackMixin, StoreBaseUiMixin],
    computed: {
        pageTitle() {
            if (this.mode === 'view') {
                return this.titleView
            }
            return this.titleEdit
        }
    },
    data() {
        return {
            mode: 'view',
            titleView: '加载中...',
            titleEdit: '加载中...',
            placeholder: '输入内容...',
            editorCtx: null,
        }
    },
    methods: {
        init() {
            this.titleView = this.config.titleView || '查看内容'
            this.titleEdit = this.config.titleEdit || '编辑内容'
            if (!this.value) {
                this.mode = 'edit'
            }
            if (this.config.mode) {
                this.mode = this.config.mode
            }
            if (this.value) {
                if (this.editorCtx) {
                    this.editorCtx.setContents({
                        html: this.value
                    })
                }
            }
        },
        onEditorReady() {
            // #ifdef MP-BAIDU
            this.editorCtx = requireDynamicLib('editorLib').createEditorContext('editorId');
            this.editorCtx.setContents({
                html: this.value
            })
            // #endif
            // #ifdef APP-PLUS || H5 ||MP-WEIXIN
            uni.createSelectorQuery().select('#editor').context((res) => {
                this.editorCtx = res.context
                this.editorCtx.setContents({
                    html: this.value
                })
            }).exec()
            // #endif
        },
        onInput(e) {
            this.value = e.detail.html
        },
        doSave() {
            this.callbackConfirm(this.value)
        },
        doCancel() {
            this.callbackCancel()
        }
    }
}
</script>

<style lang="less" scoped>
.rich-view-editor-hide {
    height: 0px;
    overflow: hidden;
}

.view-container, .editor-container {
    background: #FFF;
    border-radius: 0.5rem;
    padding: 0.5rem;
    height: calc(100vh - 150px)
}

#editor {
    width: 100%;
    height: 300px;
}

button {
    margin-top: 10px;
}
</style>
