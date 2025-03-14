<template>
	<view class="tw-p-4 tw-my-2 tw-bg-white" @click="doSelect">
		<view v-if="!address">
			<text class="iconfont icon-address tw-mr-2"></text>
			点击选择地址
		</view>
		<view v-else>
			<view class="tw-flex tw-items-center">
				<view class="tw-flex-grow">
					<view>
						<text class="tw-text-lg ub-text-primary tw-mr-4 tw-font-bold">{{ address.name }}</text>
						<text class="tw-text-lg">{{ address.phone }}</text>
					</view>
					<view class="tw-mt-1">
						{{ address.area }}
					</view>
					<view>
						{{ address.detail }}
					</view>
				</view>
				<view class="tw-w-10 tw-text-right">
					<view>
						<text class="iconfont icon-angle-right" style="font-size:1rem;"></text>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				address: null
			}
		},
		mounted() {
			this.init()
		},
		methods: {
			init() {
				this.$api.post('member_address/get_default', {}, res => {
					this.address = res.data
					this.$emit('change', this.address)
				})
			},
			doSelect() {
				this.$r.startForCallback('/brick/module/member/address', null, {}, res => {
					if (res) {
						this.address = res
						this.$emit('change', this.address)
					}
				})
			}
		}
	}
</script>

<style>

</style>
