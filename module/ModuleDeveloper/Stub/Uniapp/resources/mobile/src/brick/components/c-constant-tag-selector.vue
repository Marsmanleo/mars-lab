<template>
    <view class="pb-constant-tag-selector">
        <block v-for="(item,itemIndex) in values" :key="itemIndex">
            <text v-if="modelValue===item.value" class="ub-tag primary tw-mr-1">
                {{ item.name }}
            </text>
            <text v-else class="ub-tag info tw-mr-1" @tap="doTap(item)">
                {{ item.name }}
            </text>
        </block>
    </view>
</template>

<script>
import {VModelMixin} from "./Common/mixins/input";

const Constants = require('./../../config/constant')
export default {
    name: "c-constant-tag-selector",
    mixins: [VModelMixin],
    props: {
        name: {
            type: String,
            default: '',
        },
        types: {
            type: Object,
            default: null,
        }
    },
    computed: {
        values() {
            if (null !== this.types) {
                return Object.values(this.types)
            }
            let vs = []
            if (this.name in Constants) {
                const v = Constants[this.name]
                Object.keys(v).forEach(k => {
                    vs.push({
                        name: v[k].name,
                        value: v[k].value,
                        key: k
                    })
                })
            }
            return vs
        }
    },
    methods: {
        doTap(item) {
            this.onInput(item.value)
        }
    }
}
</script>

<style lang="less" scoped>
.pb-constant-tag-selector {
    display: flex;
    flex-wrap: wrap;
}
</style>
