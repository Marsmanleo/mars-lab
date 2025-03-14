<template>
	<view v-if="config.retrievePhoneEnable || config.retrieveEmailEnable">
		<view class="retrieve" v-if="!config.retrieveDisable">
			<text class="tw-mr-2">忘记密码?</text>
			<c-smart-link to="/brick/module/member/retrieve" type="link">找回密码</c-smart-link>
		</view>
	</view>
</template>

<script>
	import {
		mapState
	} from 'vuex'
	export default {
		name: "Retrieve",
		computed: mapState({
			config: (state) => state.base.config
		}),
	}
</script>

<style lang="less" scoped>
	.retrieve {
		margin-top: 40rpx;
		margin-bottom: 40rpx;
		text-align: center;
		position: fixed;
		bottom: 0;
		left: 0;
		right: 0;
	}
</style>
