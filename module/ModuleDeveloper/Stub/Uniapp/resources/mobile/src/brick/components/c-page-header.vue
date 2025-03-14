<template>
    <view class="ub-header-mobile" :class="shadow?'':'no-shadow'">
        <view class="header-status" :style="{backgroundColor:backgroundColor,height:headerStatusHeight}"></view>
        <view class="header-container"
              :style="{backgroundColor:backgroundColor,height:headerContainerHeight,top:headerStatusHeight}">
            <view class="body" :style="{paddingRight:headerBodyPaddingRight}">
                <view class="back" :style="{color:color}" v-if="hasBack&&pageCanBack"
                      @click="doBack">
                    <text class="iconfont icon-angle-left"></text>
                </view>
                <view class="title"
                      :style="{color:color,paddingLeft:headerTitlePaddingLeft,paddingRight:headerTitlePaddingRight,textAlign:headerTitleTextAlign}">
                    <slot name="title">
                        {{ title }}
                    </slot>
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
    name: "c-page-header",
    mixins: [PageHeaderMixin],
    data() {
        return {
            cName: 'c-page-header',
        }
    },
}
</script>

<style lang="less">
</style>
