<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="意见反馈"/>

        <view class="tw-p-4 ub-content-bg ub-h-full">
            <view class="ub-input-simple">
                <view class="input with-title">
                    <view class="title">
                        <text class="ub-text-danger">
                            *
                        </text>
                        <text>
                            主题
                        </text>
                    </view>
                    <view class="control">
                        <c-input v-model="data.title" placeholder="输入反馈内容主题"></c-input>
                    </view>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input with-title">
                    <view class="title">
                        <text class="ub-text-danger">
                            *
                        </text>
                        <text>
                            内容
                        </text>
                    </view>
                    <view class="control">
                        <c-textarea v-model="data.content" placeholder="输入反馈内容"></c-textarea>
                    </view>
                </view>
            </view>
            <view class="ub-input-simple">
                <view class="input with-title">
                    <view class="title">
                        <text>
                            联系
                        </text>
                    </view>
                    <view class="control">
                        <c-input placeholder="留下联系方式方便我们联系您" v-model="data.contact"></c-input>
                    </view>
                </view>
            </view>
            <view class="ub-input-action">
                <button class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">
                    提交
                </button>
            </view>
        </view>

    </view>
</template>

<script>

import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    mixins: [StoreBaseUiMixin],
    onReady() {
        this.$starter.boot()
    },
    data() {
        return {
            data: {
                title: '',
                content: '',
                contact: '',
            }
        }
    },
    methods: {
        doSubmit() {
            this.$dialog.loadingOn()
            this.$api.post('feedback/submit', this.data, res => {
                this.$dialog.loadingOff()
                this.$dialog.tipSuccess('提交成功', () => {
                    this.$r.back()
                })
            })
        }
    }
}
</script>
