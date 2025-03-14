<template>
    <checkbox-group class="tw-inline-block tw-w-auto tui-checkbox" @change="onChange">
        <checkbox :checked="modelValue" value="checked" color="#FFFFFF" :disabled="disabled"></checkbox>
        <view class="tw-ml-1 tw-inline-block tw-align-middle" @click="onInput(!modelValue)">
            <slot></slot>
        </view>
    </checkbox-group>
</template>

<script>
import {VModelMixin} from "./Common/mixins/input";

export default {
    name: 'c-checkbox',
    mixins: [VModelMixin],
    props: {
        disabled: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        onChange(e) {
            // console.log('onChange',e.detail.value.length > 0);
            this.onInput(e.detail.value.length > 0)
        },
    }
}
</script>
