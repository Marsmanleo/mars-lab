<template>
	<view class="pb-list-status-top">
		<!--
        {{loading ? 'loading' : '~loading'}}
        {{records.length}}
        {{isInit ? 'isInit' : '~isInit'}}
        {{isNoMore ? 'isNoMore' : '~isNoMore'}}
		-->
		<view class="loading" v-if="records.length>0" :class="{show:loading}">
			<text v-if="loading" class="iconfont spin">&#xe6d4;</text>
			<text v-if="loading">正在加载中...</text>
			<text v-if="!loading" class="iconfont">&#xe632;</text>
			<text v-if="!loading">加载完成</text>
		</view>
	</view>
</template>

<script>
	export default {
		name: "c-list-status-top",
		props: {
			records: {
				type: Array,
				default: () => [],
			},
			loading: {
				type: Boolean,
				default: false,
			},
		},
		data() {
			return {}
		},
		methods: {}
	}
</script>

<style lang="less" scoped>
	@import "./../../config/theme";

	.pb-list-status-top {
		text-align: center;

		.success,
		.loading {
			text-align: center;
			color: #999;
			line-height: 80rpx;
			height: 0rpx;
			overflow: hidden;
			transition: height 0.2s linear 0.5s;

			&.show {
				height: 80rpx;
				transition: height 0s linear 0s;
			}
		}

		.success {
			.iconfont {
				margin-right: 10rpx;
			}
		}

		.loading {
			.iconfont {
				display: inline-block;
				margin: 0;
				padding: 0;
				margin-right: 10rpx;
				width: 40rpx;
				height: 40rpx;
				text-align: center;
				line-height: 40rpx;

				&.spin {
					-webkit-animation: load-progress-spinner 2s linear infinite;
					animation: load-progress-spinner 2s linear infinite;
				}
			}
		}

	}

	@-webkit-keyframes load-progress-spinner {
		0% {
			-webkit-transform: rotate(0);
			transform: rotate(0);
		}

		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}

	@keyframes load-progress-spinner {
		0% {
			-webkit-transform: rotate(0);
			transform: rotate(0);
		}

		100% {
			-webkit-transform: rotate(360deg);
			transform: rotate(360deg);
		}
	}
</style>
