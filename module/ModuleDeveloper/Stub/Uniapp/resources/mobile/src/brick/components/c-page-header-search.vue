<template>
    <view class="ub-header-mobile" :class="shadow?'':'no-shadow'">
        <view class="header-status" :style="{backgroundColor:backgroundColor,height:headerStatusHeight}"></view>
        <view class="header-container"
              :style="{backgroundColor:backgroundColor,height:headerContainerHeight,top:headerStatusHeight}">
            <view class="body" :style="{paddingRight:headerBodyPaddingRight}">
                <view class="back iconfont icon-angle-left" :style="{color:color}" v-if="hasBack&&pageCanBack"
                      @click="doBack"></view>
                <view class="title"
                      :style="{color:color,paddingLeft:headerSearchTitlePaddingLeft,paddingRight:headerSearchTitlePaddingRight,textAlign:headerTitleTextAlign}">
                    <view class="pb-page-header-search-input" @click="doSearch">
                        <view class="search-input" :class="{placeholder:!searchKeywords}">
                            {{ searchKeywords ? searchKeywords : searchPlaceholder }}
                        </view>
                        <view class="search-btn">
                            <view class="iconfont icon-search"></view>
                        </view>
                    </view>
                </view>
                <view class="action" v-if="hasAction" :style="{color:color}">
                    <slot name="action"></slot>
                </view>
            </view>
        </view>
        <view class="header-container-placeholder" :style="{height:headerHeight}"></view>
    </view>
</template>

<script>
import {
    PageHeaderMixin
} from './Common/mixins/header.js'

export default {
    name: "c-page-header-search",
    mixins: [PageHeaderMixin],
    props: {
        searchKeywords: {
            type: String,
            default: ''
        },
        searchPlaceholder: {
            type: String,
            default: '输入关键词搜索'
        }
    },
    computed: {
        headerSearchTitlePaddingLeft() {
            return `calc( ${this.headerTitlePaddingLeft} + 10px )`
        },
        headerSearchTitlePaddingRight() {
            return `calc( ${this.headerTitlePaddingRight} + 10px )`
        }
    },
    data() {
        return {
            cName: 'c-page-header-search',
        }
    },
    methods: {
        doSearch() {
            this.$r.startForCallback('/brick/module/system/search', this.searchKeywords, {
                placeholder: this.searchPlaceholder
            }, data => {
                this.keywords = data
                this.$emit('search', this.keywords)
            })
        }
    }
}
</script>

<style lang="less">
.ub-header-mobile {
    .header-container {
        .body {
            .pb-page-header-search-input {
                background: var(--color-body-bg);
                line-height: 30px;
                height: 30px;
                margin-top: 10px;
                border-radius: 15px;
                display: flex;
                color: var(--color-muted);

                .search-input {
                    flex-grow: 1;
                    line-height: 30px;
                    height: 30px;
                    color: var(--color-tertiary);
                    margin-left: 30px;
                    overflow-x:scroll;
                    overflow-y: hidden;

                    &.placeholder {
                        color: var(--color-muted);
                    }
                }

                .search-btn {
                    width: 30px;
                    flex-shrink: 0;
                    line-height: 30px;
                    text-align: center;

                    .iconfont {
                        width: 30px;
                        height: 30px;
                        display: block;
                        line-height: 30px;
                    }
                }
            }
        }
    }
}
</style>
