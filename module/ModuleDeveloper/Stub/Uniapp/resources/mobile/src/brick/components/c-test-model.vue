<template>
    <view>
        <uni-easyinput v-model="modelValue" />
        <view>
            {{value}}
        </view>
    </view>
</template>

<script>
import {VModelMixin} from "./Common/mixins/input";

export default {
    name: "c-test-model",
    mixins: [VModelMixin]
}
</script>

