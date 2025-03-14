<template>
    <view>
        <view class="ub-list-head-tools" :style="{top:headTop}">
            <slot></slot>
        </view>
        <view class="ub-list-head-tools-placeholder"></view>
    </view>
</template>

<script>
import {
    PageHeaderMixin
} from './Common/mixins/header.js'

export default {
    name: "c-list-head-tools",
    mixins: [PageHeaderMixin],
    props: {
        top: {
            type: String,
            default: null
        },
        topGap: {
            type: String,
            default: null,
        }
    },
    computed: {
        headTop() {
            // console.log('headTop', this.topGap, this.top)
            if (this.top === null || this.top === '') {
                if (!this.topGap) {
                    return this.headerHeight
                }
                return `calc( ${this.headerHeight} + ${this.topGap} )`
            }
            if (null===this.topGap) {
                return this.top
            }
            if(null===this.top){
                return this.topGap
            }
            return `calc( ${this.top} + ${this.topGap} )`
        }
    }
}
</script>

<style lang="less">
.ub-list-head-tools {
    c-list-head-tools-item {
        display: flex;
        flex-grow: 1;
    }
}
</style>
