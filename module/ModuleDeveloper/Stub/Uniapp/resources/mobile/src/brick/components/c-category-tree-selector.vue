<template>
    <view v-if="tree.length>0">
        <!--        <view>-->
        <!--            level:{{ level }}-->
        <!--            ids:{{ ids }}-->
        <!--            parentIds:{{ parentIds }}-->
        <!--            value:{{ value }}-->
        <!--        </view>-->
        <view class="margin-bottom">
            <view class="tw-rounded-3xl tw-bg-gray-100 tw-px-3 tw-leading-8 tw-inline-block tw-mr-1 tw-mb-1"
                  :class="{'ub-bg-primary':(ids.length<=0 || ids[ids.length-1]===0),'tw-text-white':(ids.length<=0 || ids[ids.length-1]===0)}"
                  @click="doSelectClear()">
                全部
            </view>
            <view v-for="(t,tIndex) in tree" :key="tIndex"
                  class="tw-rounded-3xl tw-bg-gray-100 tw-px-3 tw-leading-8 tw-inline-block tw-mr-1 tw-mb-1"
                  :class="{'ub-bg-primary':ids.includes(t.id),'tw-text-white':ids.includes(t.id)}"
                  @click="doSelect(t.id)">
                {{ t.title }}
            </view>
        </view>
        <block v-for="(t,tIndex) in tree" :key="tIndex" v-if="ids.includes(t.id)">
            <c-category-tree-selector :tree="t[keyChild]" :level="level+1"
                                      :parent-ids="idsParent"
                                      :value="modelValue"
                                      @input="onInput"/>
        </block>
    </view>
</template>

<script>
import CCategoryTreeSelector from './c-category-tree-selector'
import {Tree} from "../lib/tree";
import {VModelMixin} from "./Common/mixins/input";

export default {
    name: "c-category-tree-selector",
    components: {CCategoryTreeSelector},
    mixins: [VModelMixin],
    props: {
        tree: {
            type: Array,
            default: () => []
        },
        level: {
            type: Number,
            default: 0
        },
        keyChild: {
            type: String,
            default: '_child'
        },
        parentIds: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            ids: []
        }
    },
    computed: {
        idsParent() {
            let ids = []
            for (let i = 0; i < this.level + 1; i++) {
                ids.push(this.ids[i])
            }
            return ids
        }
    },
    watch: {
        parentIds: {
            handler(n, o) {
                this.ids = JSON.parse(JSON.stringify(n))
                this.ids.push(0)
            },
            immediate: true
        },
    },
    methods: {
        initTree(n) {
            if (!this.tree.length) {
                setTimeout(() => {
                    this.initTree(n)
                }, 100)
                return
            }
            const nodes = Tree.listAncestors(this.tree, n)
            const ids = nodes.map(o => o.id)
            this.ids = JSON.parse(JSON.stringify(this.parentIds))
            this.ids = this.ids.concat(ids)
            while (this.ids.length < this.level + 1) {
                this.ids.push(0)
            }
        },
        doSelect(id) {
            this.ids = JSON.parse(JSON.stringify(this.parentIds))
            this.ids.push(id)
            this.onInput(id)
        },
        doSelectClear() {
            if (this.ids.length <= 0) {
                this.ids = [0]
            } else {
                this.$set(this.ids, this.ids.length - 1, 0)
            }
            let id = 0
            if (this.ids.length >= 2) {
                id = this.ids[this.ids.length - 2]
            }
            this.onInput(id)
        },
    }
}
</script>

