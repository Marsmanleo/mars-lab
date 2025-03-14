<template>
    <page-meta :root-font-size="ui.rootFontSize"/>
    <view class="pb-member-vip">
        <c-page-header-trans title="VIP会员" :has-back="mode!=='tabbar'"></c-page-header-trans>
        <view>
            <view class="top-bg tw-pt-20 tw-px-8 tw-pb-8"
                  :style="{marginTop:headMarginTop}">
                <view v-if="loading" class="tw-h-13"></view>
                <view v-else class="tw-flex tw-text-white tw-items-center">
                    <view class="tw-w-12">
                        <view>
                            <image class="ub-cover tw-w-10 tw-h-10 tw-rounded-full" :src="user.avatar"/>
                        </view>
                    </view>
                    <view class="tw-flex-grow">
                        <view class="ub-color-vip">
                            {{ user.viewName }}
                        </view>
                        <view class="tw-text-sm">
                            <text class="ub-tag warning ub-text-bold tw-mr-1">
                                {{ user.vip ? user.vip.title : '游客' }}
                            </text>
                            <text class="tw-mr-1" v-if="user.vipExpire">{{ user.vipExpire }}过期</text>
                        </view>
                    </view>
                </view>
            </view>
            <c-loading page v-if="loading"/>
            <div class="vip-list-container" v-else>
                <scroll-view class="vip-list" scroll-x="true" :scrollIntoView="scrollIntoView" scroll-with-animation>
                    <view v-for="(vip,vipIndex) in vips" :key="vipIndex" class="item"
                          :id="'item'+vipIndex"
                          @click="doVipActive(vip.id)"
                          :class="{active:vipActiveId===vip.id}">
                        <view class="title">
                            {{ vip.title }}
                        </view>
                        <view class="price">
                            ￥{{ vip.price }}
                            <text class="ub-text-sm">元</text>
                        </view>
                        <view class="tw-line-through tw-text-gray-400">
                            ￥{{ vip.priceMarket }}
                        </view>
                        <view class="desc">
                            {{ vip.desc }}
                        </view>
                        <view
                            v-if="vip.priceMarket-vip.price>0"
                            class="item-active-show tw-absolute tw--left-1 tw--top-3 tw-p-1 tw-text-sm tw-bg-red-500 tw-rounded-lg tw-text-white">
                            优惠立减{{ (vip.priceMarket - vip.price).toFixed(2) }}元
                        </view>
                    </view>
                </scroll-view>
                <view class="nav-item left" @click="doPrev()">
                    <text class="iconfont icon-angle-left"></text>
                </view>
                <view class="nav-item right" @click="doNext()">
                    <text class="iconfont icon-angle-right"></text>
                </view>
            </div>
            <view class="margin-bottom tw-px-3">
                <c-rolling-text :values="openUsersList"/>
            </view>
            <view v-if="rightsSelected.length>0"
                  class="ub-content-bg tw-mx-3 margin-bottom tw-rounded-lg tw-px-3 tw-pt-3">
                <view class="row">
                    <view v-for="(r,rIndex) in rightsSelected" :key="r.title" class="col-6">
                        <view class="tw-flex tw-items-center tw-mb-3">
                            <image class="tw-w-6 tw-h-6 tw-mr-2" mode="cover" :src="r.image"/>
                            <view class="tw-flex-grow">
                                <view class="tw-text-sm">{{ r.title }}</view>
                                <view class="tw-text-sm ub-text-muted">{{ r.desc }}</view>
                            </view>
                        </view>
                    </view>
                </view>
            </view>
            <view v-if="vipSelected" class="tw-mx-3 ub-panel">
                <view class="head">
                    <view class="title">
                        {{ vipSelected.title }}
                    </view>
                </view>
                <view class="body">
                    <rich-text class="ub-html" :nodes="vipSelected.content || ''"/>
                </view>
            </view>
            <view v-if="content" class="tw-mx-3 ub-panel">
                <view class="head">
                    <view class="title">
                        VIP开通说明
                    </view>
                </view>
                <view class="body">
                    <rich-text class="ub-html" :nodes="content || ''"/>
                </view>
            </view>
            <view class="tw-h-32"></view>
            <view class="tw-bg-white tw-fixed tw-left-0 tw-right-0 tw-shadow-lg"
                  :class="{'tw-bottom-12':mode==='tabbar','tw-bottom-0':mode!=='tabbar'}">
                <view class="tw-text-center tw-px-3 tw-py-1 tw-bg-yellow-100" v-if="countDown>0">
                    <text class="iconfont icon-time tw-mr-1"></text>
                    优惠剩余
                    <text class="ub-text-warning tw-mx-1">{{ countDownTime }}</text>
                </view>
                <view v-if="user.id>0" class="tw-flex tw-items-center tw-p-3">
                    <view class="tw-flex-grow">
                        <view class="tw-text-sm tw-leading-6">
                            <text class="ub-text-warning tw-mr-1 tw-text-lg">{{ vipCalc.type }}</text>
                            <text class="tw-mr-1">支付</text>
                            <text class="ub-text-warning tw-mr-1 tw-text-lg">{{ vipCalc.price }}</text>
                            <text class="tw-mr-1">元</text>
                        </view>
                        <view v-if="0" class="tw-text-sm tw-leading-6">
                            购买后
                            <text class="ub-text-warning tw-mx-1">{{ vipCalc.expire }}</text>
                            过期
                        </view>
                    </view>
                    <view class="">
                        <view class="btn btn-vip btn-lg btn-block btn-round" @tap="doSubmit">确认支付</view>
                    </view>
                </view>
                <view v-else class="tw-p-3">
                    <view class="btn btn-vip btn-lg btn-block btn-round" @tap="doLogin">
                        登录开通
                    </view>
                </view>
            </view>
        </view>
        <app-tab-bar v-if="mode==='tabbar'"/>
    </view>
</template>

<script>
import {StoreBaseConfigMixin, StoreBaseUiMixin, StoreUserMixin} from "../../components/Common/mixins/store";

export default {
    name: "vip",
    mixins: [StoreUserMixin, StoreBaseConfigMixin, StoreBaseUiMixin],
    data() {
        return {
            // 显示模式 tabbar | null
            mode: null,
            loading: true,
            content: '',
            countDownTimer: null,
            countDown: -1,
            countDownEnd: 0,
            countDownTime: '00:00:00.0',
            openUsers: [],
            vips: [],
            rights: [],
            vipActiveId: 0,
            scrollIntoView: '',
            vipCalc: {
                type: '-',
                price: '-',
                expire: '-',
            },
        }
    },
    computed: {
        openUsersList() {
            return this.openUsers.map(o => {
                return {
                    title: [o.name, o.time, '开通了', o.title].join(' ')
                }
            })
        },
        headMarginTop() {
            return (-this.SystemConfig.HeadHeightTotal) + 'px'
        },
        vipSelected() {
            return this.vips.find(o => o.id === this.vipActiveId)
        },
        rightsSelected() {
            if (!this.rights) {
                return []
            }
            return this.rights.filter(o => o.vipIds.includes(this.vipActiveId))
        },
    },
    onReady() {
        this.mode = this.$r.getQuery('mode')
        this.$starter.boot(() => this.init())
    },
    mounted() {
        this.countDownTimer = setInterval(() => {
            if (this.countDown > 0) {
                const left = this.countDownEnd - new Date().getTime();
                if (left <= 0) {
                    this.countDownTime = '00:00:00.0';
                } else {
                    const h = Math.floor(left / 1000 / 60 / 60);
                    const m = Math.floor(left / 1000 / 60 % 60);
                    const s = Math.floor(left / 1000 % 60);
                    const ms = Math.floor(left % 1000 / 100);
                    this.countDownTime = (h < 10 ? '0' + h : h) + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s) + '.' + (ms < 10 ? '0' + ms : ms);
                }
            }
        }, 100)
    },
    destroyed() {
        clearInterval(this.countDownTimer)
    },
    methods: {
        doLogin() {
            this.requireUserLogin(() => {
            }, true)
        },
        init() {
            this.$api.post('member_vip/info', {}, res => {
                this.title = res.data.title
                this.content = res.data.content
                this.countDown = res.data.countDown
                if (this.countDown > 0) {
                    this.countDownEnd = new Date().getTime() + this.countDown * 1000
                }
                this.vips = res.data.vips.filter(o => !o.isDefault)
                this.rights = res.data.rights
                this.openUsers = res.data.openUsers || []
                for (const v of this.vips) {
                    this.vipActiveId = v.id
                    this.doVipActive(v.id)
                    break
                }
                this.loading = false
            })
        },
        doVipActive(vipId) {
            this.vipCalc.type = '-'
            this.vipCalc.price = '-'
            this.vipCalc.expire = '-'
            this.vipActiveId = vipId
            this.$api.post('member_vip/calc', {
                vipId: this.vipActiveId
            }, (res) => {
                this.vipCalc = res.data
            })
            const index = this.vips.findIndex(o => o.id === vipId)
            this.scrollIntoView = 'item' + index;
        },
        doPrev() {
            let index = this.vips.findIndex(o => o.id === this.vipActiveId)
            if (index > 0) {
                this.doVipActive(this.vips[index - 1].id)
            }
        },
        doNext() {
            let index = this.vips.findIndex(o => o.id === this.vipActiveId)
            if (index < this.vips.length - 1) {
                this.doVipActive(this.vips[index + 1].id)
            }
        },
        doSubmit() {
            this.$dialog.loadingOn()
            this.$api.post('member_vip/buy', {
                vipId: this.vipActiveId,
                redirect: this.$r.pathToUrl('/pages/member'),
            }, (res) => {
                this.$dialog.loadingOff()
                this.$r.to(`/brick/module/pay_center/pay?order=${res.data.orderSecretId}`)
            }, res => this.$dialog.loadingOff())
        }
    }
}
</script>

<style lang="less">
@import "./../../../config/theme";

.pb-member-vip /deep/ .ub-header-mobile .header-container .body .title {
    color: #FECA95 !important;
}

.pb-member-vip {

    .top-bg {
        background: linear-gradient(135deg, #0e081e, #030109);
    }

    .vip-list-container {
        position: relative;

        .nav-item {
            position: absolute;
            top: 50%;
            background: rgba(0, 0, 0, 0.2);
            color: #FFF;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            font-size: 1.5rem;
            line-height: 2rem;
            text-align: center;
            margin-top: -1rem;

            &.left {
                left: 0.25rem;
            }

            &.right {
                right: 0.25rem;
            }
        }
    }

    .vip-list {
        padding: 0.5rem 0.5rem;
        white-space: nowrap;

        .item {
            margin: 0.5rem;
            background: #fff;
            box-shadow: 0 0 8px 0 #e5e5e5;
            border-radius: 0.5rem 1.5rem 0.5rem 0.5rem;
            cursor: pointer;
            border: 3px solid #fff;
            position: relative;
            padding: 0.5rem;
            width: 8rem;
            flex-shrink: 0;
            text-align: center;
            color: #552f07;
            display: inline-block;

            .item-active-show {
                display: none;
            }

            &.active {
                border: 3px solid #FECA95;
                background: #fcf1e6;

                .item-active-show {
                    display: block;
                }
            }

            .title {
                font-weight: bold;
            }

            .price {
                font-size: 1rem;
            }

            .desc {
                font-size: 0.6rem;
                height: 1rem;
                line-height: 1rem;
                overflow: hidden;
                color: var(--color-tertiary);
            }
        }
    }
}
</style>
