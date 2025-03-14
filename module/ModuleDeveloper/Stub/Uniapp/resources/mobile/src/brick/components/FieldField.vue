<template>
    <view v-if="!!field" class="pb-field-field">
        <block v-if="field.type==='Date' || field.type==='Decimal' || field.type==='Datetime' || field.type==='Time' || field.type==='Text' || field.type==='Textarea' || field.type==='Select'" class="action text-left">
            {{value || ''}}
        </block>
        <block v-if="field.type==='Number'">
            {{value}}
        </block>
        <block v-if="field.type==='RichText'">
            <rich-text class="ub-html" :nodes="value || ''"></rich-text>
        </block>
        <block v-if="field.type==='MultiSelect'">
            {{value.join(', ')}}
        </block>
        <block v-if="field.type==='Image'">
            <view class="image">
                <ImageField :value="value" />
            </view>
        </block>
    </view>
</template>

<script>
    import ImageField from "./ImageField";
    export default {
        name: 'FieldField',
        components: {ImageField},
        props: {
            value: null,
            field: {
                type: Object,
                default: () => null
            },
        },
    }
</script>

<style lang="less" scoped>
    @import "./../../config/theme";
    .pb-field-field{
        width:550rpx;
        text-align: left;
        .image{
            padding-top:20rpx;
        }
    }
</style>
