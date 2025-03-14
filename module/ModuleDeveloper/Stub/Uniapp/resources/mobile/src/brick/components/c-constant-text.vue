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
	export default {
		name: "c-constant-text",
		props: {
			value: null,
			types: {
				type: Object,
				default: {},
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
                Object.keys(this.types).forEach(k => {
                    vs['v' + this.types[k].value] = {
                        name: this.types[k].name,
                        value: this.types[k].value,
                        key: k
                    }
                })
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
						}
						let ret = {}
						ret[`ub-text-${cls}`] = true
						return ret
				}
			}
		}
	}
</script>

