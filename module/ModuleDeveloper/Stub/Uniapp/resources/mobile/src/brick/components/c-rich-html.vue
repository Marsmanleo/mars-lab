<template>
    <view>
        <!-- #ifdef H5 -->
        <view class="ub-html" v-html="valueHtml"></view>
        <!-- #endif -->
        <!-- #ifdef MP-WEIXIN -->
        <rich-text class="ub-html" space="ensp" :nodes="valueHtml"></rich-text>
        <!-- #endif -->
    </view>
</template>

<script>
import {HtmlUtil} from "../lib/html";

export default {
    name: "c-rich-html",
    props: {
        value: {
            type: String,
            default: ''
        }
    },
    computed: {
        valueHtml() {
            return HtmlUtil.adapt(this.value)
        }
    }
}
</script>


