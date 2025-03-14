<template>
    <view class="ub-header-mobile ub-header-mobile-trans" :class="{'no-shadow':!shadow,'absolute':absolute}">
        <view class="header-status" :style="{height:headerStatusHeight}"></view>
        <view class="header-container"
              :style="{height:headerContainerHeight,top:headerStatusHeight}">
            <view class="body" :style="{paddingRight:headerBodyPaddingRight}">
                <view class="back" :style="{color:color}" v-if="hasBack&&pageCanBack"
                      @click="doBack">
                    <text class="iconfont icon-angle-left"></text>
                </view>
                <text class="title" v-if="hasTitle"
                      :style="{paddingLeft:headerTitlePaddingLeft,paddingRight:headerTitlePaddingRight,textAlign:headerTitleTextAlign}">
                    <slot name="title">
                        {{ title }}
                    </slot>
                </text>
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
    name: "c-page-header-trans",
    mixins: [PageHeaderMixin],
    props: {
        absolute: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            cName: 'c-page-header-trans',
        }
    },
}
</script>

<style lang="less">
.ub-header-mobile-trans {

    &.absolute {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
    }

    .header-status {
        background: transparent;
        color: #FFF;
    }

    .header-container {
        background: transparent;
        box-shadow: none;
        position: absolute;

        .body {
            .back {
                .iconfont{
                    &:before {
                        background: rgba(255, 255, 255, 0.5);
                        border-radius: 50%;
                    }
                }
            }

            .title {
                color: #FFF;
            }
        }
    }
}
</style>
