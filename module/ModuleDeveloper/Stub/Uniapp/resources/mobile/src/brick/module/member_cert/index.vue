<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view>
        <c-page-header title="实名认证"/>
        <view class="tw-p-4" v-if="!individual&&!corp&&loadedCount>=2">
            <view class="tw-text-center tw-py-8">
                <view>
                    <text class="iconfont icon-individual" style="font-size:2rem;line-height:2rem;"></text>
                </view>
                <view class="tw-mt-2">
                    <view class="">未实名认证</view>
                </view>
            </view>
            <view class="tw-flex">
                <view class="btn btn-primary btn-round btn-lg tw-flex-grow tw-mx-2"
                      @click="$r.to('/brick/module/member_cert/edit_individual')">
                    <text class="iconfont icon-individual tw-mr-1"></text>
                    开始个人认证
                </view>
                <view class="btn btn-primary btn-round btn-lg tw-flex-grow tw-mx-2"
                      @click="$r.to('/brick/module/member_cert/edit_corp')"
                      v-if="config.MemberCert_CorpEnable">
                    <text class="iconfont icon-corp tw-mr-1"></text>
                    开始企业认证
                </view>
            </view>
        </view>
        <view v-if="loadedCount<2">
            <c-loading v-if="loading" page/>
        </view>
        <view class="tw-p-4" v-if="!!individual">
            <view class="tw-text-center tw-py-8">
                <view>
                    <text class="iconfont icon-individual" style="font-size:2rem;line-height:2rem;"></text>
                </view>
                <view class="tw-mt-2">
                    <view class="ub-text-warning" v-if="individual.status===IndividualStatus.VERIFYING.value">正在审核
                    </view>
                    <view class="ub-text-danger" v-if="individual.status===IndividualStatus.VERIFY_FAILED.value">审核失败
                    </view>
                    <view class="" v-if="individual.status===IndividualStatus.VERIFIED.value">实名认证成功</view>
                </view>
            </view>
            <view class="ub-m-list ub-box-shadow">
                <view class="ub-m-list-item no-icon">
                    <view class="title">
                        姓名
                    </view>
                    <view class="value">
                        {{ individual.name }}
                    </view>
                </view>
                <view class="ub-m-list-item no-icon">
                    <view class="title">
                        身份证
                    </view>
                    <view class="value">
                        {{ individual.idCard }}
                    </view>
                </view>
                <view class="ub-m-list-item no-icon" v-if="individual.idCardFront && individual.idCardBack">
                    <view class="title">
                        证件照
                    </view>
                    <view class="value">
                        <view class="ub-cover-1-1 tw-w-10 tw-bg-gray-100 tw-rounded tw-inline-block tw-ml-1"
                              :style="{backgroundImage:`url(${individual.idCardFront})`}"></view>
                        <view class="ub-cover-1-1 tw-w-10 tw-bg-gray-100 tw-rounded tw-inline-block tw-ml-1"
                              :style="{backgroundImage:`url(${individual.idCardBack})`}"></view>
                    </view>
                </view>
            </view>
        </view>
        <view class="tw-p-4" v-if="!!corp">
            暂不支持企业
        </view>
    </view>
</template>

<script>
import {StoreBaseConfigMixin, StoreBaseUiMixin} from "../../components/Common/mixins/store";

export const IndividualStatus = {
    "VERIFYING": {
        "key": "VERIFYING",
        "value": 2,
        "name": "正在审核"
    },
    "VERIFIED": {
        "key": "VERIFIED",
        "value": 3,
        "name": "已审核"
    },
    "VERIFY_FAILED": {
        "key": "VERIFY_FAILED",
        "value": 4,
        "name": "审核失败"
    }
};
export const CorpStatus = {
    "VERIFYING": {
        "key": "VERIFYING",
        "value": 2,
        "name": "正在审核"
    },
    "VERIFIED": {
        "key": "VERIFIED",
        "value": 3,
        "name": "已审核"
    },
    "VERIFY_FAILED": {
        "key": "VERIFY_FAILED",
        "value": 4,
        "name": "审核失败"
    }
};
export default {
    name: "index",
    mixins: [StoreBaseConfigMixin, StoreBaseUiMixin],
    data() {
        return {
            CorpStatus,
            IndividualStatus,
            loadedCount: 0,
            individual: null,
            corp: null,
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
                this.loadedCount++
            })
            this.$api.post('member_cert/corp_get', {}, res => {
                this.corp = res.data || null
                this.loadedCount++
            })
        },
    }
}
</script>

