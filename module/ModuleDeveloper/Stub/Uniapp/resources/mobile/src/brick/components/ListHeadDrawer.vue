<template>
    <view class="pb-list-head-drawer">
        <button class="ub-list-head-drawer-button" @tap.stop="doTrigger">
            {{title}}
            <text class="iconfont down" v-if="!visible">&#xe651;</text>
            <text class="iconfont up" v-if="visible">&#xe64b;</text>
        </button>
        <view class="pb-list-head-drawer-mask" v-if="visible">
            <view class="pb-list-head-drawer-content" :class="{'no-submit':!hasSubmit&&!hasReset}">
                <view class="pb-list-head-drawer-content-body">
                    <block v-if="selectList.length>0">
                        <TuiListView class="select-list">
                            <TuiListCell v-for="(item,itemIndex) in selectList"
                                         :key="itemIndex"
                                         @click="doSelect(item,itemIndex)">
                                <view class="select-list-item" :class="itemIndex===selectIndex?'active':''">
                                    <view>{{item.name}}</view>
                                    <view class="checked iconfont">&#xe7be;</view>
                                </view>
                            </TuiListCell>
                        </TuiListView>
                    </block>
                    <slot></slot>
                </view>
                <view class="pb-list-head-drawer-content-foot" v-if="hasSubmit || hasReset">
                    <button v-if="hasReset" class="cu-btn block lg" @click="doReset">清空条件</button>
                    <button v-if="hasSubmit" class="btn btn-lg btn-primary btn-block" @click="doSubmit">确认</button>
                </view>
            </view>
        </view>
    </view>
</template>

<script>
    import TuiListView from "../tui/list-view/list-view";
    import TuiListCell from "../tui/list-cell/list-cell";
    export default {
        name: "ListHeadDrawer",
        components: {TuiListCell, TuiListView},
        props: {
            hasReset: {
                type: Boolean,
                default: true
            },
            hasSubmit: {
                type: Boolean,
                default: true
            },
            title: {
                type: String,
                default: ''
            },
            selectList:{
                type:Array,
                default:()=>[]
            }
        },
        data() {
            return {
                visible: false,
                selectIndex: -1,
            }
        },
        methods: {
            hide() {
                this.visible = false
            },
            doTrigger() {
                this.visible = !this.visible
                this.$emit(this.visible ? 'open' : 'close')
            },
            doReset() {
                this.visible = false
                this.$emit('reset')
            },
            doSubmit() {
                this.visible = false
                this.$emit('submit')
            },
            doSelect(item,itemIndex){
                if(this.selectIndex === itemIndex){
                    this.selectIndex = -1
                    this.$emit('select', {item: null, index: -1})
                }else{
                    this.selectIndex = itemIndex
                    this.$emit('select', {item: item, index: itemIndex})
                }
                setTimeout(() => {
                    this.visible = false
                }, 100)
            }
        }
    }
</script>

<style lang="less">
    @import "../../config/theme";

    .pb-list-head-drawer-mask {
        position: absolute;
        top: 43px;
        left: 0;
        right: 0;
        height: calc(100vh - 44px - 100rpx - var(--status-bar-height));
        background: rgba(0, 0, 0, 0.5);
        z-index: 10000;
    }

    .pb-list-head-drawer-content {
        position: relative;
        padding-bottom: 58px;
        background: #FFF;
        &.no-submit {
            padding-bottom: 0;
            .pb-list-head-drawer-content-body{
                max-height: calc(100vh - 44px - var(--status-bar-height) - 100rpx);
            }
        }
    }

    .pb-list-head-drawer-content-body {
        max-height: calc(100vh - 44px - 120rpx - var(--status-bar-height) - 100rpx);
        overflow: auto;
        background: #FFF;
        .select-list{
            .select-list-item{
                font-size:28rpx !important;
                display: flex;
                flex:1;
                &.active{
                    color:var(--color-primary) !important;
                    .checked{
                        visibility: visible;
                    }
                }
                .checked{
                    flex:1;
                    text-align: right;
                    font-size:36rpx;
                    line-height:54rpx;
                    height:54rpx;
                    overflow: hidden;
                    visibility: hidden;
                }
            }
        }
    }

    .pb-list-head-drawer-content-foot {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: #FFF;
        border-top: 1px solid #EEE;
        padding: 20rpx;
        display: flex;
        justify-content: space-between;
        .cu-btn{
            width: 49%;
        }
    }
</style>
