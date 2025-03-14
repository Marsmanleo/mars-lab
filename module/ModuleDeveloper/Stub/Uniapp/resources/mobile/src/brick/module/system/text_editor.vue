<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="tw-bg-white tw-min-h-screen">
        <c-page-header :title="title" titleAlign="center" :hasAction="true" :customBackCallback="true"
                       @back="doCancel"/>
        <view class="tw-p-4">
            <uni-easyinput type="textarea" v-model="value" autoHeight :placeholder="placeholder"></uni-easyinput>
        </view>
        <view class="tw-p-4">
            <view class="btn btn-primary btn-lg btn-block btn-round" @click="doConfirm">
                {{ submitText }}
            </view>
        </view>
    </view>
</template>

<script>
import {RouterStartForCallbackMixin} from "../../components/Common/mixins/router";
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "text_editor",
    mixins: [RouterStartForCallbackMixin, StoreBaseUiMixin],
    data() {
        return {
            title: '加载中...',
            submitText: '加载中...',
            placeholder: '输入内容...',
        }
    },
    methods: {
        init() {
            this.title = this.config.title || '发表内容'
            this.submitText = this.config.submitText || '确认提交'
        },
        onInput(e) {
            this.value = e.detail.value
        },
        doConfirm() {
            this.callbackConfirm(this.value)
        },
        doCancel() {
            this.callbackCancel()
        }
    }
}
</script>

