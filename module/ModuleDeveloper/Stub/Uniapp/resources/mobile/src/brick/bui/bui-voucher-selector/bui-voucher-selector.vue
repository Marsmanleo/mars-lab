<template>
	<view>
		<view
			class="tw-rounded tw-block ub-text-default tw-leading-10 tw-py-2 tw-flex tw-border-0 tw-border-b tw-border-dashed tw-border-gray-100">
			<view>
				优惠券
			</view>
			<view class="tw-text-right ub-text-primary tw-flex-grow" v-if="records.length>0" @click="visible=true">
				<view v-if="usingRecords.length>0">
					{{ usingRecords.map(o => o._title).join(',') }}
				</view>
				<view v-else>
					{{ records.length }}张可用
				</view>
			</view>
			<view class="tw-text-right ub-text-muted tw-flex-grow" v-else>
				暂无
			</view>
		</view>
		<tui-drawer mode="bottom" :visible="visible">
			<view class="tw-p-4 tw-relative">
				<view class="tw-absolute tw-right-0 tw-top-0 tw-h-10 tw-w-10 tw-leading-10 tw-text-center"
					@click="visible=false">
					<text class="iconfont icon-close"></text>
				</view>
				<view class="tw-text-center tw-text-lg">
					优惠券
				</view>
				<view style="max-height:60vh;overflow:auto;">
					<view v-for="(record,recordIndex) in recordsCopy" :key="recordIndex">
						<view class="tw-flex tw-items-center tw-mb-2" style="background-color:#FEF3F2;">
							<view class="tw-w-10 tw-text-center">
                                <c-checkbox v-model="recordsCopy[recordIndex]._checked" />
							</view>
							<view class="ub-text-primary tw-text-2xl tw-w-20"
								@click="recordsCopy[recordIndex]._checked=!record._checked">
								{{ record._voucher.showTag }}
							</view>
							<view class="tw-flex-grow tw-py-2"
								@click="recordsCopy[recordIndex]._checked=!record._checked">
								<view class="tw-font-bold">
									{{ record._voucher.title }}
								</view>
								<view class="tw-text-sm ub-text-muted">
									{{ record._voucher.summary }}
								</view>
								<view class="tw-text-sm ub-text-muted">
									{{ record.startTime }}
									-
									{{ record.expireTime }}
								</view>
							</view>
						</view>
					</view>
				</view>
			</view>
			<view class="tw-p-4">
				<view class="btn btn-block btn-lg btn-round btn-primary" @click="doSubmit">
					确定使用
				</view>
			</view>
			<view style="height:100rpx;">
			</view>
		</tui-drawer>
	</view>
</template>

<script>
	export default {
		props: {
			records: {
				type: Array,
				default: () => {
					return []
				}
			},
		},
		watch: {
			records: {
				handler(n, o) {
					let records = JSON.parse(JSON.stringify(n))
					if (!records) {
						records = []
					}
					this.recordsCopy = records.map(o => {
						o._checked = false
						return o
					})
				},
				immediate: true
			}
		},
		data() {
			return {
				visible: false,
				loading: true,
				recordsCopy: [],
				usingRecords: [],
			}
		},
		computed: {
			checkedRecords() {
				return this.recordsCopy.filter(o => o._checked)
			}
		},
		mounted() {
			this.init()
		},
		methods: {
			init() {
				this.loading = false
			},
			doSubmit() {
				this.usingRecords = JSON.parse(JSON.stringify(this.checkedRecords))
				this.visible = false
				this.$emit('change', this.checkedRecords)
			}
		}
	}
</script>
