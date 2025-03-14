<template>
    <view class="pb-work-care">
        {{text}}
    </view>
</template>

<script>
    export default {
        name: 'WorkCare',
        computed: {
            text() {
                const d = new Date()
                const h = d.getHours()
                switch(h){
                    case 0:case 1:case 2:case 3:case 4:
                        return '夜深了，早点休息吧～'
                    case 5:case 6:
                        return '早起的你，一定会成功~'
                    case 7:case 8:case 9:
                        return '新的一天，加油！'
                    case 10:case 11:
                        return '不要等机会，而要创造机会'
                    case 12:
                        return '中午好，再忙也别忘机吃午餐~'
                    case 13:case 14:case 15:case 16:case 17:case 18:
                        return '不要等机会，而要创造机会'
                    case 19:case 20:case 21:case 22:case 23:
                        return '晚上好，明天的你，会感谢今天努力的自己~'
                }
            }
        }
    }
</script>

<style lang="less" scoped>
    @import "./../../config/theme";
    .pb-work-care{
        background: #FFF;
        padding:0 20rpx;
        font-size:28rpx;
        color:#999;
    }
</style>
