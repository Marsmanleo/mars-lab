<template>
</template>

<script>
    import {LazyValue} from "../lib/lazyvalue";

    export default {
        name: "LazyValue",
        props: {
            url: {
                type: String,
                default: '',
            }
        },
        data() {
            return {
                loading: false,
                value: {}
            }
        },
        methods: {
            start() {
                this.loading = true
                this.sync()
                new LazyValue()
                    .url(this.url)
                    .fetch((url, cb) => {
                        this.$api.post(url, {}, res => {
                            cb(res)
                        }, res => {
                            cb(res)
                        })
                    })
                    .update(value => {
                        this.value = value
                        this.sync()
                    })
                    .finish(() => {
                        this.loading = false
                        this.sync()
                    })
                    .start()
            },
            sync() {
                this.$emit('update', this.loading, this.value)
            }
        }
    }
</script>
