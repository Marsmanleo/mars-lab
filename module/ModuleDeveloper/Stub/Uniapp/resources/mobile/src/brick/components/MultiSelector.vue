<template>
    <view :class="{'pb-label-selector':true,'c-4':rowCount===4,'c-3':rowCount===3,'c-2':rowCount===2,'c-1':rowCount===1,}">
        <view v-for="(item,itemIndex) in options" :key="item" :class="{item:true,active:datav.includes(item)}">
            <button class="btn" @click="doSelect(itemIndex)">{{item}}</button>
        </view>
    </view>
</template>

<script>
    export default {
        name: "MultiSelector",
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
                datav: [],
            }
        },
        mounted(){
            this.datav = this.value
        },
        methods: {
            doSelect(index) {
                if(this.datav.includes(this.options[index])){
                    this.datav.splice(this.datav.indexOf(this.options[index]),1)
                }else{
                    this.datav.push(this.options[index])
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