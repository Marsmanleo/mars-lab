<template>
	<view class="item" :class="{open:open}">
		<view class="item-drawer">
			<view class="item-drawer-title" @tap="doOpen()">
				<view class="text">{{title}}</view>
				<view class="icon iconfont">&#xe651;</view>
			</view>
			<view class="item-drawer-mask">
				<view class="item-drawer-content">
					<view class="item-drawer-content-body" :style="{maxHeight:maxHeightCalc}">
						<slot></slot>
					</view>
					<view class="item-drawer-content-foot" v-if="hasReset||hasSubmit">
						<button class="btn btn-lg btn-round" v-if="hasReset" @tap="doReset">重置</button>
						<button class="btn btn-lg btn-primary btn-round" v-if="hasSubmit" @tap="doSubmit">确认</button>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import {
		PageHeaderMixin
	} from './Common/mixins/header.js'
	export default {
		name: "c-list-head-tools-item",
		mixins: [PageHeaderMixin],
		props: {
			title: {
				type: String,
				default: 'title'
			},
			hasReset: {
				type: Boolean,
				default: true
			},
			hasSubmit: {
				type: Boolean,
				default: true
			},
			maxHeight: {
				type: String,
				default: '',
			}
		},
		data() {
			return {
				open: false,
			}
		},
		computed: {
			maxHeightCalc() {
				if (!this.maxHeight) {
					return 'calc(100vh - 2.0rem - 3.0rem - ' + this.headerHeight + ' )'
				}
				return this.maxHeight
			}
		},
		methods: {
			doHide() {
				this.open = false
                // #ifdef H5
                document.body.classList.remove('body-scroll-lock')
                // #endif
			},
			doOpen() {
				if (!this.open) {
					this.$parent.$children.forEach(o => {
						o.doHide()
					})
				}
				this.open = !this.open
                // #ifdef H5
                if (this.open) {
                    document.body.classList.add('body-scroll-lock')
                } else {
                    document.body.classList.remove('body-scroll-lock')
                }
                // #endif
			},
			doReset() {
				let $vm = this
				for (let i = 0; i < 10; i++) {
					if (!$vm) {
						break
					}
					$vm.$emit('reset')
					$vm = $vm.$parent
				}
				this.doHide()
			},
			doSubmit() {
				let $vm = this
				for (let i = 0; i < 10; i++) {
					if (!$vm) {
						break
					}
					$vm.$emit('submit')
					$vm = $vm.$parent
				}
				this.doHide()
			}
		}
	}
</script>
