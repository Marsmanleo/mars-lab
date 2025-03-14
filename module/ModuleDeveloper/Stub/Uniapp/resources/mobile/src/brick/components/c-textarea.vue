<template>
<textarea :maxlength="maxlength"
          :placeholder="placeholder"
          @input="onInput"
          :value="modelValue"></textarea>
</template>

<script>
import {VModelMixin} from "./Common/mixins/input";

export default {
    name: "c-textarea",
    mixins: [VModelMixin],
    props: {
        placeholder: {
            type: String,
            default: ""
        },
        maxlength: {
            type: Number,
            default: -1
        }
    }
}
</script>

