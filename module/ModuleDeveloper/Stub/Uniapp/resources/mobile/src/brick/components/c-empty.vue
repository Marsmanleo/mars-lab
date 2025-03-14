<template>
    <view :style="{height:height}" class="c-empty">
        <view class="ub-empty">
            <view class="icon">
                <text :class="icon"></text>
            </view>
            <view class="text">{{ text }}</view>
            <view v-if="actions.length>0" class="action tw-mt-4">
                <view v-for="(action,actionIndex) in actions" :key="actionIndex" class="btn btn-round"
                      @click="$emit('action',action)">
                    {{ action.title }}
                </view>
            </view>
        </view>
    </view>
</template>

<script>
export default {
    name: "c-empty",
    props: {
        text: {
            type: String,
            default: '暂无数据'
        },
        icon: {
            type: String,
            default: 'iconfont icon-empty-box'
        },
        height: {
            type: String,
            default: 'auto'
        },
        actions: {
            type: Array,
            default: () => {
                return []
            }
        }
    }
}
</script>

<style lang="less">
.c-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6rem 0;
}
</style>
