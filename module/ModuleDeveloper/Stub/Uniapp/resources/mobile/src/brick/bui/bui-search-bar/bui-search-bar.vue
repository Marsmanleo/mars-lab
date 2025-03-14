<template>
    <view class="ub-search-mobile-bar" @tap="doJump">
        <view class="left" v-if="hasBack" @click="doBack">
            <view class="item">
                <text class="icon iconfont icon-angle-left"></text>
            </view>
        </view>
        <view class="center" :style="{paddingRight:centerPaddingRight}">
            <view class="box">
                <text class="icon iconfont icon-search"></text>
                <input type="text" class="input tw-text-center"
                       :placeholder="placeholder"
                       :value="value"
                       @input="doInput"
                       placeholder-class="ub-text-muted"/>
            </view>
        </view>
    </view>
</template>

<script>
export default {
    props: {
        placeholder: {
            type: String,
            default: '输入关键词搜索'
        },
        value: {
            type: String,
            default: '',
        },
        hasBack: {
            type: Boolean,
            default: false,
        },
        menuPaddingRightFix: {
            type: Boolean,
            default: false,
        },
        inputKeywordsInPage: {
            type: Boolean,
            default: true,
        }
    },
    data() {
        return {
            keywords: '',
        }
    },
    computed: {
        centerPaddingRight() {
            if (this.menuPaddingRightFix) {
                return this.SystemConfig.HeadMenuWidth + 'px'
            }
            return 0
        }
    },
    methods: {
        doBack() {
            this.$r.back()
        },
        doInput(e) {
            this.keywords = e.detail.value
            this.$emit('input', this.keywords)
        },
        doJump() {
            if (!this.inputKeywordsInPage) {
                return
            }
            // console.log('doJump',this.keywords)
            this.$r.startForCallback('/brick/module/system/search', this.value, {
                placeholder: this.placeholder
            }, data => {
                // console.log('bui-search-bar.doJump.callback',data);
                this.keywords = data
                // ios 环境下，如果直接在 @search 中跳转，会出现跳转失败的问题
                setTimeout(() => {
                    this.$emit('search', this.keywords)
                }, 500)
            })
        }
    }
}
</script>

<style lang="less" scoped>
// #ifdef MP-WEIXIN
.ub-search-mobile-bar {
    .center {
        .box {
            .input {
                top: 0;
            }
        }
    }
}
// #endif
.ub-search-mobile-bar {
    .center {
        .box {
            .input {
                padding-right: 1rem;
            }
        }
    }
}
</style>
