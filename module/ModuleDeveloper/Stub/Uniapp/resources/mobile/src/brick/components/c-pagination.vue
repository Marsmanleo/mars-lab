<template>
    <view class="ub-paginate-mobile">
        <view class="ub-paginate-mobile__btn"
              :class="currentIndex === 1 ? 'ub-paginate-mobile--disabled' : 'ub-paginate-mobile--enabled'"
              :hover-class="currentIndex === 1 ? '' : 'ub-paginate-mobile--hover'" :hover-start-time="20"
              :hover-stay-time="70"
              @click="clickLeft">
            <template v-if="showIcon">
                <text class="iconfont icon-angleleft"></text>
            </template>
            <template v-else>
                <text class="ub-paginate-mobile__child-btn">{{ prevText }}</text>
            </template>
        </view>
        <view class="ub-paginate-mobile__num">
            <view class="ub-paginate-mobile__num-page">
                <text class="ub-paginate-mobile__num-page-text current">{{ currentIndex }}</text>
                <text class="ub-paginate-mobile__num-page-text">/{{ maxPage || 0 }}</text>
            </view>
        </view>
        <view class="ub-paginate-mobile__btn"
              :class="currentIndex === maxPage ? 'ub-paginate-mobile--disabled' : 'ub-paginate-mobile--enabled'"
              :hover-class="currentIndex === maxPage ? '' : 'ub-paginate-mobile--hover'" :hover-start-time="20"
              :hover-stay-time="70"
              @click="clickRight">
            <template v-if="showIcon">
                <text class="iconfont icon-angle2"></text>
            </template>
            <template v-else>
                <text class="ub-paginate-mobile__child-btn">{{ nextText }}</text>
            </template>
        </view>
    </view>
</template>

<script>
    export default {
        name: 'c-pagination',
        props: {
            prevText: {
                type: String,
                default: '上一页'
            },
            nextText: {
                type: String,
                default: '下一页'
            },
            page: {
                type: Number,
                default: 1
            },
            total: {
                type: Number,
                default: 0
            },
            pageSize: {
                type: Number,
                default: 10
            },
            showIcon: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                currentIndex: 1
            }
        },
        computed: {
            maxPage() {
                let maxPage = 1
                let total = Number(this.total)
                let pageSize = Number(this.pageSize)
                if (total && pageSize) {
                    maxPage = Math.ceil(total / pageSize)
                }
                return maxPage
            }
        },
        watch: {
            page(val) {
                this.currentIndex = val
            }
        },
        created() {
            this.currentIndex = this.page
        },
        methods: {
            clickLeft() {
                if (Number(this.currentIndex) === 1) {
                    return
                }
                this.currentIndex -= 1
                this.change('prev')
            },
            clickRight() {
                if (Number(this.currentIndex) === this.maxPage) {
                    return
                }
                this.currentIndex += 1
                this.change('next')
            },
            change(e) {
                this.$emit('change', {
                    type: e,
                    page: this.currentIndex
                })
                this.$emit('current-change', this.currentIndex)
            }
        }
    }
</script>

