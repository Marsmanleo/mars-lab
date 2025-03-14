<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="个人实名认证"/>
        <view class="tw-p-8">
            <view class="ub-input-simple">
                <view class="input with-title">
                    <view class="title">
                        <text class="ub-text-danger">
                            *
                        </text>
                        <text>
                            姓名
                        </text>
                    </view>
                    <view class="control">
                        <input v-model="data.name"/>
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
                            身份证号
                        </text>
                    </view>
                    <view class="control">
                        <input v-model="data.idCard"/>
                    </view>
                </view>
            </view>
            <view class="ub-input-action">
                <button :loading="loading" class="btn btn-lg btn-primary btn-block btn-round" @click="doSubmit()">
                    提交认证
                </button>
            </view>
        </view>
    </view>
</template>

<script>
import {StoreBaseUiMixin} from "../../components/Common/mixins/store";

export default {
    name: "edit_individual",
    mixins: [StoreBaseUiMixin],
    data() {
        return {
            loading: false,
            data: {
                name: '',
                idCard: '',
                idCardFront: '',
                idCardBack: ''
            }
        }
    },
    onReady() {
        this.$starter.boot(() => {
            this.init()
        })
    },
    methods: {
        init() {
            this.$api.post('member_cert/individual_get', {}, res => {
                this.individual = res.data || null
            })
        },
        doSubmit() {
            this.loading = true
            this.$api.post('member_cert/individual_save', this.data, res => {
                this.loading = false
                this.$dialog.tipSuccess('提交资料成功', () => {
                    this.$r.back()
                })
            }, res => {
                this.loading = false
            })
        }
    }
}
</script>

