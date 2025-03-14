<template>
	<text>
		<text :class="colorCls" v-if="values['v'+value]">{{values['v'+value].name}}</text>
		<text class="ub-text-muted" v-if="!value">-</text>
	</text>
</template>

<script>
	import {
		ConstantColorGuessMap
	} from "./ConstantScript";

	const Constants = require('./../../config/constant')
	export default {
		name: "c-constant-text-field",
		props: {
			value: null,
			name: {
				type: String,
				default: '',
			},
			colors: {
				type: Object,
				default: () => {}
			},
			mode: {
				type: String,
				default: 'guess'
			}
		},
		computed: {
			values() {
				let vs = {}
				if (this.name in Constants) {
					const v = Constants[this.name]
					Object.keys(v).forEach(k => {
						vs['v' + v[k].value] = {
							name: v[k].name,
							value: v[k].value,
							key: k
						}
					})
				}
				return vs
			},
			colorCls() {
				switch (this.mode) {
					default:
						const k = `v${this.value}`
						let cls = 'text'
						if (k in this.values) {
							const valueKey = this.values[k].key.toLowerCase()
							ConstantColorGuessMap.forEach(o => {
								if (o[0].test(valueKey)) {
									cls = o[1]
								}
							})
                            // console.log('ConstantColorGuessMap',valueKey, cls)
						}
						let ret = {}
						ret[`ub-text-${cls}`] = true
						return ret
				}
			}
		}
	}
</script>

