<template>
    <view class="pb-label-selector">
        <view v-for="item in options" :key="item.id"
              class="tw-rounded-3xl tw-bg-gray-100 tw-px-3 tw-leading-8 tw-inline-block tw-mr-1 tw-mb-1"
              :class="{'ub-bg-primary':modelValue===item.id,'ub-text-white':modelValue===item.id}"
              @click="onInput(item.id)">
            {{ item.name }}
        </view>
    </view>
</template>

<script>
import {VModelMixin} from "./Common/mixins/input";

export default {
    name: "c-single-id-name-selector",
    mixins: [VModelMixin],
    props: {
        options: {
            type: Array,
            default: () => []
        },
    }
}
</script>

