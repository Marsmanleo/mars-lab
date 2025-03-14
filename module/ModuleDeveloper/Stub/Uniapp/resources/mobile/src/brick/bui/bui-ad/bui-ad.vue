<template>
    <view class="bui-ad" :class="{'bui-ad-round':round}">
        <view v-if="mode==='simple' && record">
            <view v-if="record.type==='Image'" @click="doClick(record)">
                <image mode="widthFix" class="image" :src="record.image"/>
            </view>
            <!-- #ifdef MP-WEIXIN -->
            <view v-if="record.type==='WeixinMpAd'">
                <view v-if="record.param.type==='banner'">
                    <ad :unit-id="record.param.unitId"></ad>
                </view>
                <view v-else-if="record.param.type==='video'">
                    <ad :unit-id="record.param.unitId" ad-type="video" ad-theme="white"></ad>
                </view>
                <view v-else-if="record.param.type==='rewardedVideo'">
                    <image mode="widthFix" class="image" :src="record.param.image"
                           @click="doWeixinMpRewardedVideo(record)"/>
                </view>
            </view>
            <!-- #endif -->
        </view>
    </view>
</template>

<script>
import {SecureUtil} from "../../lib/secure";
import {StoreUserMixin} from "../../components/Common/mixins/store";

export default {
    name: "bui-ad",
    mixins: [StoreUserMixin],
    props: {
        position: {
            type: String,
            default: 'home'
        },
        mode: {
            type: String,
            default: 'simple'
        },
        round: {
            type: Boolean,
            default: true,
        }
    },
    data() {
        return {
            records: [],
            weixinMpRewardedVideoAd: null,
        }
    },
    computed: {
        record() {
            return this.records.length > 0 ? this.records[0] : null
        }
    },
    mounted() {
        this.init()
    },
    methods: {
        init() {
            const types = [
                // #ifdef APP-PLUS || H5
                'Image',
                // #endif
                // #ifdef MP-WEIXIN
                'WeixinMpAd',
                // #endif
            ]
            this.loading = true
            this.$api.post('axd/get_mobile', {
                position: this.position,
                types: types,
                mode: this.mode
            }, res => {
                this.loading = false
                this.records = res.data.records
                // this.records = [
                //     {
                //         type:'WeixinMpAd',
                //         param:{
                //             type:'banner',
                //             unitId:'adunit-e0fed0bc550d14ad'
                //         }
                //     }
                // ]
            })
        },
        doClick(record) {
            if (!record.link) {
                return
            }
            this.$r.to(record.link)
        },
        doReport(type, record, param) {
            this.$api.post('axd/report', {
                data: SecureUtil.aes.encode(JSON.stringify({
                    id: record.id,
                    type: type,
                    param: Object.assign({}, param)
                }))
            }, res => {
                setTimeout(() => {
                    this.$emit('play-end', {
                        record: record,
                    })
                }, 1000)
            })
        },
        // #ifdef MP-WEIXIN
        doWeixinMpRewardedVideo(record) {
            if (!wx || !wx.createRewardedVideoAd) {
                this.$dialog.tipError('当前环境不支持')
                return
            }
            this.requireUserLogin(() => {
                if (!this.weixinMpRewardedVideoAd) {
                    this.weixinMpRewardedVideoAd = wx.createRewardedVideoAd({adUnitId: record.param.unitId})
                    this.weixinMpRewardedVideoAd.onLoad(() => {
                        console.log('doWeixinMpRewardedVideo.onLoad')
                    })
                    this.weixinMpRewardedVideoAd.onError((err) => {
                        console.log('doWeixinMpRewardedVideo.onError', err)
                    })
                    this.weixinMpRewardedVideoAd.onClose((res) => {
                        console.log('doWeixinMpRewardedVideo.onClose', res)
                        if (res && res.isEnded) {
                            this.doReport('PlayEnd', record)
                        }
                    })
                }
                this.weixinMpRewardedVideoAd.show().then(() => {
                    console.log('doWeixinMpRewardedVideo.show.success')
                }).catch(err => {
                    console.log('doWeixinMpRewardedVideo.show.fail', err)
                })
            }, true)
        }
        // #endif
    }
}
</script>

<style lang="less" scoped>
.bui-ad {
    background-color: var(--color-content-bg);
    padding: 0;

    &.bui-ad-round {
        border-radius: 0.5rem;

        .image {
            border-radius: 0.5rem;
        }
    }

    .image {
        width: 100%;
        padding: 0;
        margin: 0;
        display: block;
    }
}

</style>
