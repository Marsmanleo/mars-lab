<template>
    <view>
        <view @click="visible=true" class="tw-py-2">
            <text class="iconfont icon-address"></text>
            {{ value ? value : '点击选择' }}
        </view>
        <tui-drawer mode="bottom" :visible="visible">
            <view class="tw-p-4 tw-relative">
                <view class="tw-absolute tw-right-0 tw-top-0 tw-h-10 tw-w-10 tw-leading-10 tw-text-center"
                      @click="visible=false">
                    <text class="iconfont icon-close"></text>
                </view>
                <view class="tw-text-center tw-text-lg">
                    选择地址
                </view>
                <view class="tw-mt-2" v-if="inited">
                    <tui-cascade-selection height="500rpx"
                                           :itemList="addressList"
                                           :defaultItemList="defaultValue"
                                           @complete="onComplete"></tui-cascade-selection>
                </view>
            </view>
        </tui-drawer>
    </view>
</template>

<script>
import {Tree} from "../../lib/tree";
import {VModelMixin} from "../../components/Common/mixins/input";

export default {
    name: "bui-area-china-selector",
    mixins: [VModelMixin],
    data() {
        return {
            addressList: [],
            visible: false,
            inited: false,
        }
    },
    computed: {
        defaultValue() {
            if (!this.value) {
                return []
            }
            return this.value.split(',')
        }
    },
    mounted() {
        this.init()
    },
    methods: {
        init() {
            this.$api.post('area/china', {}, res => {
                let nodes = res.data.map(o => {
                    o.text = o.title
                    o.value = o.id
                    return o
                })
                this.addressList = Tree.tree(nodes, 0, 'id', 'pid', 'sort', 'children')
                this.inited = true
            })
        },
        onComplete(e) {
            this.visible = false
            this.onInput(e.result.map(o => o.text).join(','))
        }
    }
}
</script>

