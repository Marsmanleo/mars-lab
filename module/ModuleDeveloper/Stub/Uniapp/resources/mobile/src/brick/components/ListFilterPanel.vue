<template>
    <view class="cu-modal drawer-modal justify-end" :class="{show:visible}" @tap="visible=false">
        <view class="cu-dialog basis-lg pb-list-filter-panel"
          @tap.stop=""
          :style="[{top:'0px',height:'100vh'}]">
            <view class="head" :style="[{height:headHeight+'px',lineHeight:headHeight+'px'}]">
                筛选
            </view>
            <view class="body">
                <slot></slot>
            </view>
            <view class="foot">
                <button class="cu-btn primary" @tap="doConfirm">确定</button>
                <button class="cu-btn" @tap="doCancel">取消</button>
            </view>
        </view>
    </view>
</template>

<script>
    export default {
        name: "ListFilterPanel",
        computed: {
            headHeight(){
                return this.SystemConfig.HeadHeight
            }
        },
        data() {
            return {
                visible: false,
                singleSelect:null,
                mulSelect:['选项1'],
            };
        },
        watch:{
            mulSelect:{
                handler(n,o){
                    console.log(JSON.stringify(this.mulSelect))
                },
                immediate:true
            }
        },
        methods: {
            show() {
                this.visible = true
            },
            doConfirm(){
                this.visible = false
            },
            doCancel(){
                this.visible = false
            }
        }
    }
</script>

<style lang="less" scoped>
    .pb-list-filter-panel{
        text-align: left;
        .head{
            padding:0 10rpx;
            color:#999;
            background: #EEE;
        }
        .body{
        }
        .foot{
            padding:10rpx;
            position: absolute;
            bottom:0px;
            left:0px;
            right:0px;
            border-top:1px solid #EEE;
            text-align: center;
            .cu-btn{
                width:30%;
                margin:0 10rpx;
            }
        }
    }
</style>
