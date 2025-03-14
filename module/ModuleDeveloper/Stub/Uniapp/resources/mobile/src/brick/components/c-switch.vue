<template>
    <view>
        <switch :checked="value" @change="onChange" color="var(--color-primary)" />
    </view>
</template>

<script>
export default {
    name: "c-switch",
    props: {
        value: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        onChange(e) {
            this.$emit("input", e.detail.value);
        }
    }
}
</script>
