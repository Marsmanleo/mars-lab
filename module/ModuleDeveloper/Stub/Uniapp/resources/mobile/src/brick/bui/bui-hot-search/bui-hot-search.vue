<template>
    <view class="ub-hot-search tw-flex tw-px-3 tw-py-1">
        <view class="tw-font-bold tw-w-10 tw-flex-shrink-0">
            热搜
        </view>
        <view class="tw-flex-grow tw-h-6 tw-overflow-hidden">
            <view v-for="(k,ki) in keywordsList" class="tw-inline-block tw-mr-2"
                  :key="ki"
                  @click="doSearch(k)">
                {{ k }}
            </view>
        </view>
    </view>
</template>

<script>
export default {
    data() {
        return {
            keywordsList: [
                // '关键词1',
                // '关键词2',
                // '关键词3',
                // '关键词4',
            ]
        }
    },
    mounted() {
        this.$api.post('hot_search/all', {}, res => {
            this.keywordsList = res.data.keywordsList
        })
    },
    methods: {
        doSearch(keywords) {
            this.$emit('search', keywords)
        }
    }
}
</script>
