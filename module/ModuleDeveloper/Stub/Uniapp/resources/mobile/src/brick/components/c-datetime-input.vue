<template>
    <view>
        <view v-if="!value" class="text-gray" @tap.stop="show">[点击选择]</view>
        <view v-if="!!value" @tap.stop="show">{{ value }}</view>
        <tui-datetime ref="datetime" :type="1" :set-date-time="setDateTime" @confirm="handleChange"/>
    </view>
</template>

<script>
export default {
    name: 'c-datetime-input',
    props: {
        value: {
            type: String,
            default: null
        },
    },
    data() {
        return {
            setDateTime: '',
        }
    },
    methods: {
        show() {
            if (this.value) {
                this.setDateTime = this.value.substr(0, 16)
            } else {
                this.setDateTime = ''
            }
            this.$refs.datetime.show()
        },
        handleChange(e) {
            this.$emit('input', e.result + ':00')
        }
    }
}
</script>