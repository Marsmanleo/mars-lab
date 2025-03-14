<template>
    <view :class="{'pb-label-selector':true,'c-4':rowCount===4,'c-3':rowCount===3,'c-2':rowCount===2,'c-1':rowCount===1,}">
        <view v-for="item in options" :key="item" :class="{item:true,active:datav===item}">
            <button class="btn" @click="doSelect(item)">{{item}}</button>
        </view>
    </view>
</template>

<script>
    export default {
        name: "SingleSelector",
        props: {
            value: null,
            datavName: {
                type: String,
                default: ''
            },
            options: {
                type: Array,
                default: () => []
            },
            rowCount: {
                type: Number,
                default: 2
            },
        },
        watch: {
            value: {
                handler(n, o) {
                    const str = JSON.stringify(this.value)
                    if (JSON.stringify(this.datav) !== str) {
                        this.datav = JSON.parse(str)
                    }
                },
                immediate: true
            }
        },
        data() {
            return {
                datav: null,
            }
        },
        mounted(){
            this.datav = this.value
        },
        methods: {
            doSelect(item) {
                this.datav = item
                this.$emit('input', {
                    target: {
                        dataset: {
                            name: this.datavName
                        }
                    },
                    detail: {
                        value: this.datav,
                    },
                })
            }
        }
    }
</script>

<style scoped lang="less">
    @import "./LabelSelectorStyle";
</style>