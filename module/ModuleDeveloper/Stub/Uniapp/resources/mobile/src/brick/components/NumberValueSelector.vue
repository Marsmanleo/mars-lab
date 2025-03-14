<template>
    <view class="pb-number-value-selector">
        <block v-for="(item,itemIndex) in items" :key="itemIndex">
            <text v-if="value===item.value"
                  class="tui-tag tui-tag-fillet tui-primary">
                {{item.name || item.value}}
            </text>
            <text v-else
                  class="tui-tag tui-tag-fillet tui-btn-gray"
                @tap="doTap(item)">
                {{item.name || item.value}}
            </text>
        </block>
    </view>
</template>

<script>
    import {NumberInputMixin} from "./Common/mixins/input";

    const Constants = require('./../../config/constant')
    export default {
        name: "NumberValueSelector",
        mixins: [NumberInputMixin],
        props: {
            items: {
                type: Array,
                default:()=>{
                    return []
                }
            }
        },
        methods:{
            doTap(item){
                this.handleChangeProcess(item.value)
            }
        }
    }
</script>

<style lang="less" scoped>
    .pb-number-value-selector{
        display:flex;
        flex-wrap: wrap;
        .tui-tag{
            margin-right:10rpx;
            margin-bottom:10rpx;
        }
    }
</style>