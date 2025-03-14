<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="pb-module-system-search" style="min-height:100vh;">
        <c-page-header-holder title="搜索"/>
        <view class="ub-search-mobile-bar page" :style="headStyle">
            <view class="left">
                <view class="item" @click="doCancel">
                    <view class="icon iconfont icon-angle-left"></view>
                </view>
            </view>
            <view class="center">
                <view class="box">
                    <text class="icon iconfont icon-search"></text>
                    <input type="text" class="input"
                           :value="value"
                           @input="onInput"
                           :placeholder="inputPlaceholder"
                           confirm-type="search"
                           @confirm="doConfirmSearch"
                           placeholder-class="ub-text-muted"/>
                </view>
            </view>
            <view class="right" v-if="!!value" @tap="doConfirm">
                <view class="item ub-text-primary">
                    确定
                </view>
            </view>
            <view class="right" v-if="!value" @tap="doCancel">
                <view class="item">
                    取消
                </view>
            </view>
            <view class="right" v-if="!!value" @tap="doClearSearch">
                <view class="item">
                    清除
                </view>
            </view>
        </view>
        <view>
            <view class="ub-panel">
                <view class="head">
                    <view class="more">
                        <view class="ub-text-tertiary tw-w-10 tw-h-8 tw-text-right tw-inline-block"
                              @click="isDeleting=!isDeleting"
                              v-if="keywordsList.length>0">
                            <text class="iconfont icon-cog"></text>
                        </view>
                        <view class="ub-text-tertiary tw-w-10 tw-h-8 tw-text-right tw-inline-block" @click="doClear"
                              v-if="keywordsList.length>0">
                            <text class="iconfont icon-trash"></text>
                        </view>
                    </view>
                    <view class="title">
                        历史搜索
                    </view>
                </view>
                <view class="body" v-if="keywordsList.length>0">
                    <view v-for="(k,kIndex) in keywordsList" @tap="doSearch(k)" :key="kIndex"
                          class="search-item tw-bg-gray-100 tw-inline-block tw-px-3 tw-rounded-2xl tw-text-gray-800 tw-mr-2 tw-mb-1 tw-max-w-full tw-relative">
                        <view class="tw-truncate tw-leading-8">
                            {{ k ? k : '[空]' }}
                        </view>
                        <view
                            v-if="isDeleting"
                            class="tw-w-4 tw-h-4 tw-leading-4 tw-text-white tw-bg-gray-300 tw-rounded tw-text-center tw-rounded-full tw-absolute tw--right-1 tw--top-1"
                            @tap="doDelete(kIndex)"
                        >
                            <text class="iconfont icon-close"></text>
                        </view>
                    </view>
                </view>
                <view class="body" v-else>
                    <c-empty text="暂无搜索记录"/>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
import {
    RouterStartForCallbackMixin
} from '../../components/Common/mixins/router.js'
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "search",
    mixins: [RouterStartForCallbackMixin, StoreBaseUiMixin],
    data() {
        return {
            placeholder: '搜索关键词',
            keywordsList: [],
            isDeleting: false,
        }
    },
    computed: {
        inputPlaceholder() {
            if (this.config && this.config.placeholder) {
                return this.config.placeholder
            }
            return this.placeholder
        },
        headStyle() {
            return [
                'padding-right: ' + this.SystemConfig.HeadMenuWidth + 'px'
            ].join(';')
        }
    },
    methods: {
        init() {
            this.keywordsList = this.$storage.getArray('SearchKeywordHistory', [])
        },
        doSaveKeywords() {
            this.$storage.set('SearchKeywordHistory', this.keywordsList)
        },
        onInput(e) {
            this.value = e.detail.value
        },
        doPutKeywords(value) {
            if (!value) {
                return
            }
            const index = this.keywordsList.indexOf(value)
            if (index >= 0) {
                this.keywordsList.splice(index, 1)
            }
            this.keywordsList.unshift(value)
            this.doSaveKeywords();
        },
        doDelete(index) {
            this.keywordsList.splice(index, 1)
            this.doSaveKeywords();
        },
        doConfirmSearch() {
            this.$nextTick(() => {
                this.doConfirm()
            })
        },
        doConfirm() {
            this.doPutKeywords(this.value)
            this.callbackConfirm(this.value)
        },
        doCancel() {
            this.callbackCancel()
        },
        doSearch(value) {
            if (this.isDeleting) {
                return;
            }
            this.doPutKeywords(value)
            this.callbackConfirm(value)
        },
        doClearSearch() {
            this.doSearch('')
        },
        doClear() {
            this.$dialog.confirm('确认清空搜索历史？', () => {
                this.keywordsList = []
                this.$storage.set('SearchKeywordHistory', this.keywordsList)
            })
        }
    },
}
</script>

<style lang="less" scoped>
// #ifdef MP-WEIXIN
.ub-search-mobile-bar {
    .center .box .input {
        top: 0;
    }
}

// #endif
.pb-module-system-search {
    background-color: var(--color-body-bg);

    .ub-panel {
        background-color: var(--color-body-bg);

        .search-item {
            background: var(--color-content-bg);
            color: var(--color-text);
        }
    }
}
</style>
