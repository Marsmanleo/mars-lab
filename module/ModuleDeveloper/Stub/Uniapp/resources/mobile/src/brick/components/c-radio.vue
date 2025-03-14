<template>
    <view @change="onChange">
        <picker :value="index" :range="labels" @change="onChange">
            <view class="">{{ labels[index]?labels[index]:'[请选择]' }}</view>
        </picker>
    </view>
</template>

<script>

export default {
    name: 'c-radio',
    props: {
        value: null,
        options: {
            type: Array,
            default: () => {
                return []
            }
        }
    },
    data() {
        return {
            index: 0,
        }
    },
    watch: {
        value(newValue) {
            this.index = this.options.findIndex(option => option.value === newValue)
        }
    },
    computed: {
        labels() {
            return this.options.map(option => {
                return option.label
            })
        },
    },
    methods: {
        onChange(e) {
            this.index = e.detail.value
            this.$emit('input', this.options[this.index].value)
        },
    }
}
</script>
