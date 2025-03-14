<template>
	<page-meta :root-font-size="ui.rootFontSize"/>
	<view>
		<c-page-header title="系统设置" />

		<view class="tw-p-4">
            <view class="ub-m-list">
                <view class="ub-m-list-item no-icon" v-if="config.Member_AgreementEnable"
                      @tap="$r.to('/brick/module/member/doc',{type:'agreement'})">
                    <view class="title">
                        {{config.Member_AgreementTitle}}
                    </view>
                    <view class="angle">
                        <text class="iconfont icon-angle-right"></text>
                    </view>
                </view>
                <view class="ub-m-list-item no-icon" v-if="config.Member_PrivacyEnable"
                      @tap="$r.to('/brick/module/member/doc',{type:'privacy'})">
                    <view class="title">
                        {{config.Member_PrivacyTitle}}
                    </view>
                    <view class="angle">
                        <text class="iconfont icon-angle-right"></text>
                    </view>
                </view>
                <view class="ub-m-list-item no-icon"  v-if="$hasModule('Feedback')"
                      @tap="$r.to('/brick/module/system/feedback')">
                    <view class="title">
                        意见反馈
                    </view>
                    <view class="angle">
                        <text class="iconfont icon-angle-right"></text>
                    </view>
                </view>
                <!-- #ifdef MP-WEIXIN -->
                <view class="ub-m-list-item no-icon"
                      @tap="doSetting">
                    <view class="title">
                        权限设置
                    </view>
                    <view class="angle">
                        <text class="iconfont icon-angle-right"></text>
                    </view>
                </view>
                <!-- #endif -->
                <view class="ub-m-list-item no-icon"  @click="doClearCache">
                    <view class="title">
                        清理缓存
                    </view>
                    <view class="angle">
                        <text class="iconfont icon-angle-right"></text>
                    </view>
                </view>
            </view>

            <view class="ub-m-list" v-if="darkModeEnable">
                <view class="ub-m-list-item no-icon">
                    <view class="title">
                        暗黑模式
                    </view>
                    <view>
                        <c-switch :value="ui.darkMode" @input="doDarkMode"></c-switch>
                    </view>
                </view>
            </view>

		</view>



		<view class="pb-version">
			<view>
				<image class="img" mode="aspectFit" :src="config.siteLogo" />
			</view>
			<view>系统版本：{{version}}</view>
		</view>

	</view>
</template>

<script>
	import {
		StoreBaseConfigMixin,
        StoreBaseUiMixin
	} from "../../components/Common/mixins/store";
	import {
		VersionManager
	} from "../../lib/version";
	import {
	    SystemSetting
    } from "@/config/setting";

    export default {
		mixins: [StoreBaseConfigMixin,StoreBaseUiMixin],
		onReady() {
			this.$starter.boot(() => {
			})
		},
		computed: {
			version() {
				return VersionManager.version()
			},
            darkModeEnable(){
			    return !!SystemSetting.uiDarkModeSwitchEnable
            }
		},
		methods: {
			doClearCache() {
				//TODO
				this.$dialog.tipSuccess('清理成功')
			},
            doDarkMode(v){
                this.$store.commit('SET_DARK_MODE',v)
            },
            doSetting(){
			    // #ifdef MP-WEIXIN
                wx.openSetting()
                // #endif
            }
		}
	}
</script>

<style lang="less" scoped>
	.pb-version {
		text-align: center;
		color: var(--color-muted);
		padding: 40rpx 0;
		position: fixed;
		bottom: 0;
		left: 0;
		right: 0;

		.img {
			max-width: 300rpx;
			max-height: 100rpx;
		}
	}
</style>
