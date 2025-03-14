<template>
	<view class="ub-smart-link" :class="type" @click.stop="go">
		<slot></slot>
	</view>
</template>

<script>
	import {
		Dialog
	} from "../lib/dialog";
	import {
		Router
	} from "../lib/router";

	export default {
		name: "c-smart-link",
		props: {
			to: {
				type: String,
				default: null,
			},
			confirm: {
				type: String,
				default: null,
			},
			// primary
			type: {
				type: String,
				default: ''
			}
		},
		data() {
			return {}
		},
		methods: {
			go() {
				let _go = () => {
					if (this._events.click && this._events.click[0]) {
						this._events.click[0]()
					}
					if (undefined === this.to) {
						return
					}
					if (this.to.indexOf('[url]') === 0) {
						window.location.href = this.to.substring(5)
					} else {
						Router.to(this.to)
					}
				}
				if (this.confirm) {
					Dialog.confirm(this.confirm, () => _go())
				} else {
					_go()
				}
			}
		}
	}
</script>

<style lang="less" scoped>
	@import "./../../config/theme";

	.ub-smart-link {
		display: inline-block;

		&.link {
			color: var(--color-link);
		}

		&.primary {
			color: var(--color-primary);
		}
	}
</style>
