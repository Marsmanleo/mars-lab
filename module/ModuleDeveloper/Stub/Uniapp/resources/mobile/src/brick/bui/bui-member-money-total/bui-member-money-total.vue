<template>
    <view class="tw-inline-block">
        {{ total?'￥'+total:'-' }}
    </view>
</template>

<script>
export default {
    name: "bui-member-money-total",
    data(){
        return {
            total:''
        }
    },
    mounted() {
        this.$api.post('member_money/get', {}, res => {
            this.total = res.data.total
        })
    }
}
</script>

