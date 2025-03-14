<template>
    <text>{{time}}</text>
</template>

<script>
    import {format, render, cancel, register} from 'timeago.js';

    export default {
        name: 'c-time-ago-field',
        computed: {
            time() {
                if (!this.value) {
                    return '-'
                }
                return format(this.value, 'zh_CN');
            }
        },
        props: {
            value: null
        }
    }
</script>
