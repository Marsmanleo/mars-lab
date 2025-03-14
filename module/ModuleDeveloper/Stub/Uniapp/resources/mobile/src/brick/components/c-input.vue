<template>
    <input :placeholder="placeholder"
           :value="modelValue"
           @input="onInput"/>
</template>

<script>
import {VModelMixin} from "./Common/mixins/input";

export default {
    name: "c-input",
    mixins: [VModelMixin],
    props: {
        placeholder: {
            type: String,
            default: ""
        }
    }
}
</script>

