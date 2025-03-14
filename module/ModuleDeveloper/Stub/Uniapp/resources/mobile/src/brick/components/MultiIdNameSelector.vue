<template>
    <view :class="{'pb-label-selector':true,'c-4':rowCount===4,'c-3':rowCount===3,'c-2':rowCount===2,'c-1':rowCount===1,}">
        <view v-for="(item,itemIndex) in options" :key="item.id" :class="{item:true,active:datav.includes(item.id)}">
            <button class="btn" @click="doSelect(item.id)">{{item.name}}</button>
        </view>
    </view>
</template>

<script>
    export default {
        name: "MultiIdNameSelector",
        props: {
            value: {
                type: Array,
                default: () => []
            },
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
                default: 3
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
                datav: [],
            }
        },
        mounted() {
            this.datav = this.value
        },
        methods: {
            doSelect(id) {
                if (this.datav.includes(id)) {
                    this.datav.splice(this.datav.indexOf(id), 1)
                } else {
                    this.datav.push(id)
                }
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