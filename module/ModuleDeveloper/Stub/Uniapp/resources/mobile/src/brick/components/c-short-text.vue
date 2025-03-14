<template>
    <view>
        {{ value }}
    </view>
</template>

<script>
export default {
    name: "c-short-text",
    props: {
        text: {
            type: String,
            default: ''
        },
        prefixLength: {
            type: Number,
            default: 6
        },
        suffixLength: {
            type: Number,
            default: 3
        }
    },
    computed: {
        value: function () {
            // convert aaabbbccc to aaa***ccc
            // concat prefix and suffix
            if (this.text < this.prefixLength + this.suffixLength) {
                return this.text
            }
            let prefix = this.text.substring(0, this.prefixLength)
            let suffix = this.text.substring(this.text.length - this.suffixLength)
            return prefix + '***' + suffix
        }
    }
}
</script>

